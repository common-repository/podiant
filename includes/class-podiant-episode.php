<?php

/**
 * Representation of a Podiant episode.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

require_once dirname(__FILE__) . '/class-podiant-notfound-exception.php';

/**
 * Representation of a Podiant episode.
 *
 * This class represents Podiant podcast episodes, allowing them to be saved
 * as WordPress posts.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Episode {
    /**
     * The podcast the episode belongs to.
     *
     * @since 1.0
     * @access public
     * @var Podiant_Podcast $podcast The podcast the episode belongs to.
     */
    public $podcast;

    /**
     * The ID of the episode.
     *
     * @since 1.0
     * @access public
     * @var string $id The ID of the episode.
     */
    public $id;

    /**
     * The attributes of the episosde.
     *
     * @since 1.0
     * @access public
     * @var array $attributes The attributes of the episosde.
     */
    public $attributes;

    /**
     * The links for the episosde.
     *
     * @since 1.0
     * @access public
     * @var array $links The links for the episosde.
     */
    public $links;

    /**
     * The relationships to other data for the episosde.
     *
     * @since 1.0
     * @access public
     * @var array $relationships The relationships to other data for the episosde.
     */
    public $relationships;

    /**
     * Returns a single episode by ID.
     *
     * @since 1.0
     * @param Podiant_Podcast $podcast The podcast the episode belongs to.;
     * @param string $id The episode's ID.
     * @return Podiant_Episode
     */
    public static function get($podcast, $id) {
        $query = new WP_Query(
            array(
                'post_type' => 'episode',
                'meta_query' => array(
                    array(
                        'key' => '_podiant_id',
                        'value' => $id
                    )
                ),
                'post_status' => 'any'
            )
        );

        while ($query->have_posts()) {
            $query->the_post();
            $mid = get_post_meta($query->post->ID, '_podiant_id', true);

            if ($mid !== $id) {
                throw new Exception("WordPress is broken. $mid != $id");
            }

            $meta = get_post_meta($query->post->ID, '_podiant_meta', true);

            return new Podiant_Episode(
                $podcast,
                array_merge(
                    array('id' => $id),
                    $meta
                )
            );
        }

        throw new Podiant_NotFound_Exception("Episode not found with ID '$id'.");
    }

    /**
     * Returns a single episode by WordPress post ID.
     *
     * @since 1.0
     * @param int $id The post ID.
     * @return Podiant_Episode
     */
    public static function get_from_wp($id) {
        $mid = get_post_meta($id, '_podiant_id', true);
        $meta = get_post_meta($id, '_podiant_meta', true);
        $terms = wp_get_post_terms($id, 'podcast');

        if ($mid && $meta && is_array($meta)) {
            foreach ($terms as $term) {
                $term_id = $term->term_id;
                $podcast = Podiant_Podcast::get_from_wp($term_id);
            }

            return new Podiant_Episode(
                $podcast,
                array_merge(
                    array('id' => $mid),
                    $meta
                )
            );
        }

        throw new Podiant_NotFound_Exception("Episode not found with WordPress post ID '$id'.");
    }

    /**
     * Instantiates a new Podiant_Episode object.
     *
     * @since 1.0
     * @param Podiant_Podcast $podcast The podcast the episode belongs to.;
     * @param array $fields The attributes used to create the podcast object.
     */
    public function __construct($podcast, $fields) {
        $this->podcast = $podcast;

        foreach ($fields as $key => $value) {
            switch ($key) {
                case 'id':
                    $this->id = $value;
                    break;

                case 'attributes':
                    $this->attributes = $value;
                    break;

                case 'links':
                    $this->links = $value;
                    break;

                case 'relationships':
                    $this->relationships = $value;
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

        if (!isset($this->attributes)) {
            throw new Exception('Missing required attribute attributes.');
        }

        if (!isset($this->links)) {
            throw new Exception('Missing required attribute links.');
        }

        if (!isset($this->relationships)) {
            throw new Exception('Missing required attribute relationships.');
        }
    }

    /**
     * Creates a new episode object from the attribute list.
     *
     * @since 1.0
     * @param Podiant_Podcast $podcast The podcast the episode belongs to.;
     * @param array $fields The attributes used to create the podcast object.
     * @return Podiant_Episode
     */
    public static function create($podcast, $fields) {
        $episode = new Podiant_Episode($podcast, $fields);
        $episode->save();

        return $episode;
    }

    /**
     * Updates an existing episode object using the attribute list.
     *
     * @since 1.0
     * @param array $fields The attributes used to update the podcast object.
     * @return boolean
     */
    public function update($fields) {
        foreach ($fields as $key => $value) {
            switch ($key) {
                case 'attributes':
                    $this->attributes = $value;
                    break;

                case 'links':
                    $this->links = $value;
                    break;

                case 'relationships':
                    $this->relationships = $value;
                    break;

                default:
                    throw new Exception(
                        "Unrecognised property: $key"
                    );
            }
        }

        if (!isset($this->id)) {
            throw new Exception('Cannot update an object that has no ID.');
        }

        if (!isset($this->attributes)) {
            throw new Exception('Missing required attribute attributes.');
        }

        if (!isset($this->links)) {
            throw new Exception('Missing required attribute links.');
        }

        if (!isset($this->relationships)) {
            throw new Exception('Missing required attribute relationships.');
        }

        return $this->save();
    }

    /**
     * Saves the podcast to the option array.
     *
     * @since 1.0
     * @return boolean
     */
    public function save() {
        if ($this->attributes['published']) {
            $date = strtotime($this->attributes['published']);
            $status = 'publish';
        } else {
            $date = mktime();
            $status = 'draft';
        }

        $parsedown = new Parsedown();
        $title = $this->attributes['title'];
        $content = $parsedown->text($this->attributes['description']);
        $excerpt = $this->attributes['summary'];
        $name = $this->attributes['number'];

        $attrs = array(
            'post_date' => date('Y-m-d H:i:s', $date),
            'post_title' => $title,
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_type' => 'episode',
            'post_status' => $status
        );

        $query = new WP_Query(
            array(
                'post_type' => 'episode',
                'meta_query' => array(
                    array(
                        'key' => '_podiant_id',
                        'value' => $this->id
                    )
                ),
                'post_status' => 'any'
            )
        );

        while ($query->have_posts()) {
            $query->the_post();
            $mid = get_post_meta($query->post->ID, '_podiant_id', true);

            if ($mid !== $this->id) {
                throw new Exception("WordPress is broken. $mid != $this->id");
            }

            $attrs['ID'] = $query->post->ID;
            break;
        }

        $id = wp_insert_post($attrs, true);
        if (is_wp_error($id)) {
            throw new Exception($id->get_error_message());
        }

        update_post_meta($id, '_podiant_id', $this->id);
        update_post_meta(
            $id,
            '_podiant_meta',
            array(
                'attributes' => $this->attributes,
                'links' => $this->links,
                'relationships' => $this->relationships
            )
        );

        wp_set_post_terms(
            $id,
            array($this->podcast->get_term_id()),
            'podcast'
        );

        $new_artwork = $this->attributes['artwork'];
        $old_artwork = get_post_meta($id, '_podiant_artwork', true);

        if ($new_artwork !== $old_artwork) {
            if (!$new_artwork) {
                delete_post_meta($id, '_podiant_artwork');
            } else {
                $plugin = podiant_plugin();

                try {
                    $file = $plugin->download($new_artwork);
                    $attachment_id = $file->attach(
                        $id,
                        __('Episode artwork', 'podiant'),
                        true
                    );
                } catch (Exception $ex) {
                    $plugin->log(
                        'Error downloading image',
                        PODIANT_LOG_WARNING,
                        array('exc' => $ex)
                    );

                    return;
                }

                update_post_meta($id, '_podiant_artwork', $new_artwork);
            }
        }
    }

    /**
     * Creates new episode posts or updates existing ones.
     *
     * @since 1.0
     * @param array $episode The episode data.
     * @return WP_Post
     */
    public static function create_or_update($podcast, $data) {
        $id = $data['id'];
        $attributes = $data['attributes'];
        $links = $data['links'];
        $relationships = $data['relationships'];

        try {
            $episode = Podiant_Episode::get($podcast, $id);
        } catch (Exception $ex) {
            if (is_a($ex, 'Podiant_NotFound_Exception')) {
                podiant_log(
                    "Creating WP post for episode $id",
                    PODIANT_LOG_DEBUG
                );

                Podiant_Episode::create(
                    $podcast,
                    array(
                        'id' => $id,
                        'attributes' => $attributes,
                        'links' => $links,
                        'relationships' => $relationships
                    )
                );

                return;
            } else {
                wp_die($ex);
            }
        }

        podiant_log(
            "Updating WP post for episode $id",
            PODIANT_LOG_DEBUG
        );

        $episode->update(
            array(
                'attributes' => $attributes,
                'links' => $links,
                'relationships' => $relationships
            )
        );
    }

    /**
     * Renders a player
     *
     * @since 1.0
     * @param string $style The style of player
     * @return string
     */
    public function player($context='single') {
        $url = $this->links['player'];
        $width = '100%';
        $height = '150px';
        $display = 'block';
        $plugin = podiant_plugin();
        $style = $plugin->option("player_style_{$context}");

        switch ($style) {
            case null:
            case 'default':
                $style = 'default';
                break;

            case 'mini':
                $url .= 'mini/';
                $height = '40px';
                break;

            case 'button':
                $width = '32px !important';
                $height = '32px !important';
                $display = 'inline';
                $url .= 'button/';
                break;

            default:
                throw new Exception("Unrecognised player style: '$style'.");
        }

        return (
            '<iframe src="' . $url . '" ' .
            'frameborder="0" ' .
            'class="podiant-player podiant-player--' . $style . '" ' .
            'style="width:' . $width . ';height:' . $height . ';display:' . $display . ';border-width:0;">' .
            '</iframe>'
        );
    }
}
