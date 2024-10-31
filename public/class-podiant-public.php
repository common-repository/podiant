<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package Podiant
 * @subpackage Podiant/public
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Public {
    /**
     * The ID of this plugin.
     *
     * @since 1.0
     * @access private
     * @var string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since 1.0
     * @access private
     * @var string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.0
     */
    public function enqueue_styles() {
        /**
         * An instance of this class should be passed to the run() function
         * defined in Podiant_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Podiant_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/podiant-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.0
     */
    public function enqueue_scripts() {
        /**
         * An instance of this class should be passed to the run() function
         * defined in Podiant_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Podiant_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/podiant-public.js', array('jquery'), $this->version, false);
    }

    /**
     * Register post types required for the plugin
     *
     * @since 1.0
     */
    public function register_post_types() {
        /**
         * Registers the Episode custom post type
         */

        $post_type = 'episode';
        $name = 'Episodes';
        $singular_name = 'Episode';
        $labels = array(
            'name' => _x($name, 'post type general name', 'podiant'),
            'singular_name' => _x($singular_name, 'post type singular name', 'podiant'),
            'menu_name' => 'Podiant',
            'name_admin_bar' => _x($singular_name, 'add new on admin bar', 'podiant'),
            'add_new' => _x('Add New', $post_type, 'podiant'),
            'add_new_item' => __("Add New $singular_name", 'podiant'),
            'new_item' => __("New $singular_name", 'podiant'),
            'edit_item' => __("Edit $singular_name", 'podiant'),
            'view_item' => __("View $singular_name", 'podiant'),
            'all_items' => __("All $name", 'podiant'),
            'search_items' => __("Search $name", 'podiant'),
            'parent_item_colon' => __("Parent $name:", 'podiant'),
            'not_found' => __('No ' . strtolower($name) . ' found.', 'podiant'),
            'not_found_in_trash' => __('No ' . strtolower($name) . ' found in Trash.', 'podiant')
        );

        $menu_icon = plugin_dir_url(dirname(__FILE__)) . 'admin/img/icon.png';

        register_post_type(
            $post_type,
            array(
                'labels' => $labels,
                'description' => __('Podcast episodes', 'podiant'),
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array(
                    'slug' => podiant_option('episodes_prefix'),
                    'with_front' => false
                ),
                'capability_type' => 'post',
                'capabilities' => array(
                    'create_posts' => 'do_not_allow'
                ),
                'map_meta_cap' => true,
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => 10 /* After the Media menu item */,
                'menu_icon' => $menu_icon,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt',
                    'comments'
                )
            )
        );
    }

    /**
     * Register taxonomies required for the plugin
     *
     * @since 1.0
     */
    public function register_taxonomy() {
        $name = 'Podcasts';
        $singular_name = 'Podcast';
        $labels = array(
            'name' => _x($name, 'taxonomy general name', 'podiant'),
            'singular_name' => _x($singular_name, 'taxonomy singular name', 'podiant'),
            'search_items' => __("Search $name", 'podiant'),
            'popular_items' => __("Popular $name", 'podiant'),
            'all_items' => __("All $name", 'podiant'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __("Edit $singular_name", 'podiant'),
            'update_item' => __("Update $singular_name", 'podiant'),
            'add_new_item' => __("Add New $singular_name", 'podiant'),
            'new_item_name' => __("New $singular_name Name", 'podiant'),
            'separate_items_with_commas' => __('Separate ' . strtolower($name) . ' with commas', 'podiant'),
            'add_or_remove_items' => __('Add or remove ' . strtolower($name), 'podiant'),
            'choose_from_most_used' => __('Choose from the most used ' . strtolower($name), 'podiant'),
            'not_found' => __('No ' . strtolower($name) . ' found.', 'podiant'),
            'menu_name' => __($name, 'podiant'),
        );

        register_taxonomy(
            'podcast',
            'episode',
            array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => false,
                'rewrite' => array(
                    'slug' => podiant_option('podcasts_prefix'),
                    'with_front' => false
                )
            )
        );
    }

    /**
     * Runs the sync_episodes queue.
     *
     * @since 1.0
     */
    public function sync_episodes() {
        if (!isset($_GET['process']) || !isset($_GET['_wpnonce'])) {
            return;
        }

        if (!wp_verify_nonce($_GET['_wpnonce'], 'process')) {
            return;
        }

        if ($_GET['process'] === 'sync_episodes') {
            $plugin = podiant_plugin();
            $plugin->log('Manually syncing all podcast episodes', PODIANT_LOG_DEBUG);
            $queue = $plugin->get_sync_queue();
            $podcasts = Podiant_Podcast::list();

            foreach($podcasts as $podcast) {
                $podcast->sync(false);
            }

            $plugin->log('Dispatching sync queue', PODIANT_LOG_DEBUG);
            $queue->dispatch();
        }
    }

    /**
     * Sets the sync_enabled option to false.
     *
     * @since 1.0
     */
    public function disable_sync() {
        if (!isset($_GET['process']) || !isset($_GET['_wpnonce'])) {
            return;
        }

        if (!wp_verify_nonce($_GET['_wpnonce'], 'process')) {
            return;
        }

        if ($_GET['process'] === 'disable_sync') {
            $plugin = podiant_plugin();
            $plugin->log('Disabling automatic sync', PODIANT_LOG_DEBUG);
            $plugin->option('sync_enabled', false);
        }
    }

    /**
     * Sets the sync_enabled option to true.
     *
     * @since 1.0
     */
    public function enable_sync() {
        if (!isset($_GET['process']) || !isset($_GET['_wpnonce'])) {
            return;
        }

        if (!wp_verify_nonce($_GET['_wpnonce'], 'process')) {
            return;
        }

        if ($_GET['process'] === 'enable_sync') {
            $plugin = podiant_plugin();
            $plugin->log('Enabling automatic sync', PODIANT_LOG_DEBUG);
            $plugin->option('sync_enabled', true);
        }
    }

    /**
     * Adds the player to the main body of episodes' content.
     *
     * @since 1.0
     */
    public function filter_episode_content($content) {
        $id = get_the_ID();

        if (get_post_type($id) === 'episode') {
            $plugin = podiant_plugin();
            $episode = Podiant_Episode::get_from_wp($id);
            $context = is_single() ? 'single' : 'list';
            $player = $episode->player($context);

            switch ($plugin->option('player_position')) {
                case 'top':
                    $content = $player . $content;
                    break;

                case 'bottom':
                    $content .= $player;
                    break;
            }
        }

        return $content;
    }

    /**
     * Alters the main query, adding the 'episode' custom post type if
     * applicable.
     *
     * @since 1.0
     */
    public function filter_main_query($query) {
        $plugin = podiant_plugin();

        if (is_home() && $query->is_main_query()) {
            if ($plugin->option('main_query')) {
                $post_types = $query->get('post_type');
                if (is_array($post_types)) {
                    $post_types[] = 'episode';
                } else if (is_string($post_types)) {
                    if ($post_types) {
                        $post_types = array($post_types, 'episode');
                    } else {
                        $post_types = array('post', 'episode');
                    }
                }

                $query->set('post_type', $post_types);
            }
        }

        return $query;
    }
}
