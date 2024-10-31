<?php

/**
 * Representation of a Podiant podcast.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

require_once dirname(__FILE__) . '/class-podiant-action-queue.php';
require_once dirname(__FILE__) . '/class-podiant-notfound-exception.php';

/**
 * Representation of a Podiant podcast.
 *
 * This class represents Podiant podcasts, allowing them to be added,
 * updated and removed. It's a wrapper around the WordPress `get_option`
 * and `update_option` functions.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Podcast {
    /**
     * The ID of the podcast.
     *
     * @since 1.0
     * @access public
     * @var string $id The ID of the podcast.
     */
    public $id;

    /**
     * The name of the podcast.
     *
     * @since 1.0
     * @access public
     * @var string $name The name of the podcast.
     */
    public $name;

    /**
     * The podcast's API key.
     *
     * @since 1.0
     * @access public
     * @var string $pull_key The podcast's API key.
     */
    public $pull_key;

    /**
     * The taxonomy term ID.
     *
     * @since 1.0
     * @access public
     * @var integer $term_id The taxonomy term ID,
     */
    protected $term_id;

    /**
     * Instantiates a new Podiant_Podcast object.
     *
     * @since 1.0
     * @param array $fields The attributes used to create the podcast object.
     */
    public function __construct($fields) {
        foreach ($fields as $key => $value) {
            switch ($key) {
                case 'id':
                    $this->id = $value;
                    break;

                case 'name':
                    $this->name = $value;
                    break;

                case 'pull_key':
                    $this->pull_key = $value;
                    break;

                case 'term_id':
                    $this->term_id = $value;
                    break;

                default:
                    throw new Exception(
                        "Unrecognised property: $key"
                    );
            }
        }

        if (!isset($this->id)) {
            throw new Exception('Missing required attribute id.');
        }

        if (!isset($this->name)) {
            throw new Exception('Missing required attribute name.');
        }

        if (!isset($this->pull_key)) {
            throw new Exception('Missing required attribute pull_key.');
        }
    }

    /**
     * Gets or sets the option array.
     *
     * @since 1.0
     * @access protected
     * @return array
     */
    protected static function store($value=null) {
        if ($value !== null) {
            update_option(PODIANT_PULL_KEYS_OPTION_NAME, $value);
        }

        return get_option(PODIANT_PULL_KEYS_OPTION_NAME, array());
    }

    /**
     * Returns a list of podcasts.
     *
     * @since 1.0
     * @return array
     */
    public static function list() {
        $pull_keys = Podiant_Podcast::store();
        $yield = array();

        foreach($pull_keys as $key => $fields) {
            $yield[] = new Podiant_Podcast($fields);
        }

        return $yield;
    }

    /**
     * Returns a single podcast by ID.
     *
     * @since 1.0
     * @param string $id The podcast's ID;
     * @return Podiant_Podcast
     */
    public static function get($id) {
        $pull_keys = Podiant_Podcast::store();

        if (isset($pull_keys[$id])) {
            $fields = $pull_keys[$id];
            if (is_array($fields)) {
                return new Podiant_Podcast($fields);
            }
        }

        throw new Exception("Podcast not found with ID '$id'.");
    }

    /**
     * Returns a single podcast by WordPress taxonomy ID.
     *
     * @since 1.0
     * @param int $id The taxonomy ID.
     * @return Podiant_Podcast
     */
    public static function get_from_wp($id) {
        foreach (Podiant_Podcast::list() as $podcast) {
            if ($podcast->term_id == $id) {
                return $podcast;
            }
        }

        throw new Podiant_NotFound_Exception("Episode not found with ID '$id'.");
    }

    /**
     * Returns the number of stored podcasts.
     *
     * @since 1.0
     * @return int
     */
    public static function count() {
        $pull_keys = Podiant_Podcast::store();
        return count($pull_keys);
    }

    /**
     * Creates a new podcast object from the attribute list.
     *
     * @since 1.0
     * @param array $fields The attributes used to create the podcast object.
     * @return Podiant_Podcast
     */
    public static function create($fields) {
        $podcast = new Podiant_Podcast($fields);
        $podcast->save();

        return $podcast;
    }

    /**
     * Returns an array for better message passing.
     *
     * @since 1.0
     * @access protected
     * @return array
     */
    protected function dict() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'pull_key' => $this->pull_key,
            'term_id' => $this->term_id
        );
    }

    /**
     * Saves the podcast to the option array.
     *
     * @since 1.0
     * @return boolean
     */
    public function save() {
        $pull_keys = Podiant_Podcast::store();
        $sync = false;

        if (!isset($pull_keys[$this->id])) {
            $sync = true;
        }

        if (!$this->term_id) {
            $this->get_term_id();
        }

        $pull_keys[$this->id] = $this->dict();
        Podiant_Podcast::store($pull_keys);

        if ($sync) {
            $this->sync();
        }

        return true;
    }

    /**
     * Starts a background process to sync podcast episodes.
     *
     * @since 1.0
     * @return boolean
     */
    public function sync($dispatch=true) {
        $plugin = podiant_plugin();
        $queue = $plugin->get_sync_queue();

        $queue->push_to_queue(
            array(
                'action' => 'podiant_sync_podcast',
                'args' => array($this->id)
            )
        );

        $queue->save();

        $plugin->log("{$this->id}: Added to sync queue", PODIANT_LOG_DEBUG);
        if ($dispatch) {
            $plugin->log('Dispatching sync queue', PODIANT_LOG_DEBUG);
            $queue->dispatch();
        }

        return true;
    }

    /**
     * Removes the podcast from the option array.
     *
     * @since 1.0
     * @return boolean
     */
    public function delete() {
        $pull_keys = Podiant_Podcast::store();

        if (isset($pull_keys[$this->id])) {
            unset($pull_keys[$this->id]);
            Podiant_Podcast::store($pull_keys);

            if ($this->term_id) {
                $result = wp_delete_term(
                    $this->term_id,
                    'podcast',
                    array('force_default' => true)
                );

                if (is_wp_error($result)) {
                    wp_die($result);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Fetches episode data from the API.
     *
     * @since 1.0
     * @param callable $callback The function to execute on each episode.
     * @return boolean
     */
    public function fetch_episodes($callback) {
        podiant_log("{$this->id}: Fetching episodes", PODIANT_LOG_DEBUG);

        $api = new Podiant_API($this->pull_key);
        $api->iterate('episodes', $callback);
    }

    /**
     * Returns the taxonomy term ID
     *
     * @since 1.0
     * @param array $episode The episode data.
     * @return WP_Post
     */
    public function get_term_id() {
        if (!$this->term_id) {
            $result = wp_insert_term(
                $this->name,
                'podcast',
                array(
                    'slug' => $this->id
                )
            );

            if (is_wp_error($result)) {
                wp_die($result);
            }

            $this->term_id = $result['term_id'];
            flush_rewrite_rules();
        }

        return $this->term_id;
    }

    /**
     * Creates new episode posts or updates existing ones.
     *
     * @since 1.0
     * @param array $episode The episode data.
     * @return WP_Post
     */
    public function create_or_update_post($episode) {
        return Podiant_Episode::create_or_update($this, $episode);
    }
}
