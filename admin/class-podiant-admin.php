<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/admin
 */

require_once dirname(__FILE__) . '/../includes/class-podiant-validation-exception.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Podiant
 * @subpackage Podiant/admin
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Admin {
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
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        require_once dirname(__FILE__) . '/class-podiant-settings-controller.php';

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/podiant-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/podiant-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Adds submenu items to the podcast menu.
     *
     * @since 1.0
     */
    public function menu() {
        /**
        * Adds a Settings item to the Podcast menu
        */
        $parent_slug = 'edit.php?post_type=episode';
        $page_title = _x('Settings', 'settings meta title', 'podiant');
        $menu_title = _x('Settings', 'settings menu title', 'podiant');
        $capability = 'manage_options';
        $menu_slug = 'settings';
        $controller = new Podiant_Settings_Controller();
        $position = 10 /* Bottom of the menu */;

        add_submenu_page(
            $parent_slug,
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            array($controller, 'dispatch'),
            $position
        );
    }

    /**
     * Adds admin notices.
     *
     * @since 1.0
     */
    public function notices() {
        global $pagenow;

        if (!podiant_option('sync_enabled')) {
            return;
        }

        if ($pagenow !== 'edit.php') {
            if (!isset($_GET['page']) || $_GET['page'] !== 'settings') {
                $podcasts = Podiant_Podcast::count();
                if (!$podcasts) {
                    if (current_user_can('manage_options')) {
                        podiant_notice(
                            array(
                                'kind' => 'error',
                                'title' => _x(
                                    'Podiant can automatically sync episodes of your podcast with WordPress.',
                                    'sync nag title',
                                    'podiant'
                                ),
                                'body' => _x(
                                    "If you choose this option, podcast episodes will " .
                                    "appear in the Podiant menu within the WordPress " .
                                    "dashboard, and you'll be able to add a link to " .
                                    "your episode list from the Appearance > Menus section.\n\n" .
                                    "Alternatively, you can disable automatic syncing " .
                                    "and instead use widgets and blocks to embed " .
                                    "players for episodes or an entire podcast.",
                                    'sync nag body',
                                    'podiant'
                                ),
                                'cta' => array(
                                    'title' => _x('Setup automatic syncing', 'sync setup button', 'podiant'),
                                    'url' => 'edit.php?post_type=episode&page=settings&tab=podcasts'
                                ),
                                'suppress' => array(
                                    'title' => _x('Don\'t sync automatically', 'sync disable button', 'podiant'),
                                    'url' => wp_nonce_url(admin_url('?process=disable_sync'), 'process')
                                )
                            )
                        );
                    }
                }
            }
        }

        if (isset($_GET['process']) && isset($_GET['_wpnonce'])) {
            if (wp_verify_nonce($_GET['_wpnonce'], 'process')) {
                switch ($_GET['process']) {
                    case 'sync_episodes':
                        podiant_notice(
                            array(
                                'kind' => 'success',
                                'body' => _x(
                                    'Episodes should start to appear in your WordPress dashboard shortly.',
                                    'sync queued body',
                                    'podiant'
                                )
                            )
                        );

                        break;

                    case 'disable_sync':
                        podiant_notice(
                            array(
                                'kind' => 'success',
                                'body' => _x(
                                    'Podcast episoeds will not be automatically synced.',
                                    'sync disabled notice',
                                    'podiant'
                                )
                            )
                        );

                        break;
                }
            }
        }
    }

    /**
     * Adds items to the admin bar.
     *
     * @since 1.0
     */
    public function bar_menu($wp_admin_bar) {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (!podiant_option('sync_enabled')) {
            return;
        }

        $wp_admin_bar->add_menu(
            array(
                'id' => 'podiant',
                'title' => __('Podcast', 'podiant'),
                'href' => 'javascript:;'
            )
        );

        $wp_admin_bar->add_menu(
            array(
                'parent' => 'podiant',
                'id' => 'podiant-sync',
                'title' => __('Sync Episodes', 'podiant'),
                'href' => wp_nonce_url(admin_url('?process=sync_episodes'), 'process')
            )
        );
    }

    /**
     * Adds filter options to episodes in the admin.
     *
     * @since 1.0
     */
    public function add_episode_filter_options() {
        global $typenow;

        if ($typenow !== 'episode') {
            return;
        }

        $selected = isset($_GET['podcast']) ? sanitize_text_field($_GET['podcast']) : '';
        wp_dropdown_categories(
            array(
                'show_option_all' => __('All Podcasts', 'podiant'),
                'taxonomy' => 'podcast',
                'name' => 'podcast',
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => false,
                'hide_empty' => true
            )
        );
    }

    /**
     * Filters the episode query by the supplied podcast taxonomy term.
     *
     * @since 1.0
     * @param WP_Query $query The WordPress query to filter.
     */
     public function filter_episode_query($query) {
        global $pagenow;

        if ($pagenow !== 'edit.php') {
            return $query;
        }

        $q = $query->query_vars;
        if (!isset($q['post_type']) || $q['post_type'] !== 'episode') {
            return $query;
        }

        if (!isset($q['podcast'])) {
            return $query;
        }

        if (is_numeric($q['podcast']) && $q['podcast']) {
            $term = get_term_by('id', $q['podcast'], 'podcast');
            $query->query_vars['podcast'] = $term->slug;
        }

        return $query;
    }

    /**
     * Iterates through the list of episodes for a podcast and syncs each
     * with a WordPress post.
     *
     * @since 1.0
     * @param string $podcast_id The ID of the podcast to sync.
     */
    function sync_podcast($podcast_id) {
        $podcast = Podiant_Podcast::get($podcast_id);
        $podcast->fetch_episodes(
            array($podcast, 'create_or_update_post')
        );

        flush_rewrite_rules();
    }

    /**
     * Flushes WordPress' rewrite rules.
     *
     * @since 1.0
     */
    function flush_rewrite_rules() {
        flush_rewrite_rules();
        podiant_log('Flushed rewrite rules', PODIANT_LOG_DEBUG);
    }

    /**
     * Validates the `sync_enabled` option.
     *
     * @since 1.0
     * @param boolean $value value to validate.
     * @return boolean
     */
    function validate_sync_enabled($value) {
        switch ($value) {
            case true:
                return true;

            case false:
                return false;
        }

        throw new Podiant_Validation_Exception('Invalid option.');
    }

    /**
     * Validates the `player_style_list` option.
     *
     * @since 1.0
     * @param string $value value to validate.
     * @return string
     */
    function validate_player_style_list($value) {
        switch ($value) {
            case 'default':
            case 'mini':
            case 'button':
                return $value;
        }

        throw new Podiant_Validation_Exception('Invalid option.');
    }

    /**
     * Validates the `player_style_single` option.
     *
     * @since 1.0
     * @param string $value value to validate.
     * @return string
     */
     function validate_player_style_single($value) {
        switch ($value) {
            case 'default':
            case 'mini':
            case 'button':
                return $value;
        }

        throw new Podiant_Validation_Exception('Invalid option.');
    }

    /**
     * Validates the `player_position` option.
     *
     * @since 1.0
     * @param string $value value to validate.
     * @return string
     */
     function validate_player_position($value) {
        switch ($value) {
            case 'none':
            case 'top':
            case 'bottom':
                return $value;
        }

        throw new Podiant_Validation_Exception('Invalid option.');
    }

    /**
     * Validates the `episodes_prefix` option.
     *
     * @since 1.0
     * @param string $value The value to validate.
     * @return string
     */
    function validate_episodes_prefix($value) {
        if (preg_match('/^[\w-]+$/', $value) === 0) {
            throw new Podiant_Validation_Exception('Only lowercase letters, numbers and hyphens are permitted.');
        }

        $value = strtolower($value);
        return $value;
    }

    /**
     * Validates the `podcasts_prefix` option.
     *
     * @since 1.0
     * @param string $value The value to validate.
     * @return string
     */
    function validate_podcasts_prefix($value) {
        if (preg_match('/^[\w-]+$/', $value) === 0) {
            throw new Podiant_Validation_Exception('Only lowercase letters, numbers and hyphens are permitted.');
        }

        $value = strtolower($value);
        return $value;
    }
}
