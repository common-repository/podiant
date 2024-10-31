<?php

/**
 * The file that defines the core plugin class.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

require_once dirname(__FILE__) . '/class-podiant-validation-exception.php';

/**
 * The core plugin class.
 *
 * This is used to define internationalisation, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant {
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since 1.0
     * @access protected
     * @var Podiant_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since 1.0
     * @access protected
     * @var string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since 1.0
     * @access protected
     * @var string $version The current version of the plugin.
     */
    protected $version;

    /**
     * The background process queue for syncing podcast episodes.
     *
     * @since 1.0
     * @access protected
     * @var Podiant_Action_Queue $sync_queue The background process queue for syncing podcast episodes..
     */
    protected $sync_queue;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0
     */
    public function __construct() {
        if (defined('PODIANT_VERSION')) {
            $this->version = PODIANT_VERSION;
        } else {
            $this->version = '1.0';
        }

        $this->plugin_name = 'podiant';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_cron_hooks();

        if (class_exists('WP_CLI')) {
            $this->define_cli_commands();
        }

        $this->setup_queue();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Podiant_Loader. Orchestrates the hooks of the plugin.
     * - Podiant_i18n. Defines internationalisation functionality.
     * - Podiant_Admin. Defines all hooks for the admin area.
     * - Podiant_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since 1.0
     * @access private
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-loader.php';

        /**
         * The class responsible for defining internationalisation functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-i18n.php';

        /**
         * The class responsible for defining WordPress admin notices.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-notice.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-podiant-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-podiant-public.php';

        /**
         * The class responsible for defining scheduled actions that occur.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'cron/class-podiant-cron.php';

        if (class_exists('WP_CLI')) {
            /**
             * The class responsible for defining CLI commands.
             */
            require_once plugin_dir_path(dirname(__FILE__)) . 'cli/class-podiant-cli.php';
        }

        /**
         * The class representing podcasts.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-podcast.php';

        /**
         * The class representing episodes.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-episode.php';

        /**
         * The class responsible for handling Podiant API requests.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-api.php';

        /**
         * The class responsible for downloading remote resources.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-download.php';

        $this->loader = new Podiant_Loader();

        /**
         * The class responsible for handling the oEmbed provider.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-oembed-provider.php';

        /**
         * The class responsible for producing HTML code for episode and podcast players.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-player.php';

        /**
         * The class responsible for handling the player shortcode.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-player-shortcode.php';

        /**
         * The class responsible for handling the player block.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-player-block.php';

        /**
         * The classes responsible for handling the player widgets.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-player-widget.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-podiant-latest-episode-player-widget.php';
    }

    /**
     * Define the locale for this plugin for internationalisation.
     *
     * Uses the Podiant_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since 1.0
     * @access private
     */
    private function set_locale() {
        $plugin_i18n = new Podiant_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since 1.0
     * @access private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Podiant_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'menu');
        $this->loader->add_action('admin_notices', $plugin_admin, 'notices');
        $this->loader->add_action('admin_bar_menu', $plugin_admin, 'bar_menu', 100);
        $this->loader->add_action('restrict_manage_posts', $plugin_admin, 'add_episode_filter_options');
        $this->loader->add_action('podiant_sync_podcast', $plugin_admin, 'sync_podcast');
        $this->loader->add_action('podiant_flush_rewrite_rules', $plugin_admin, 'flush_rewrite_rules');

        $this->loader->add_filter('parse_query', $plugin_admin, 'filter_episode_query');
        $this->loader->add_filter('validate_podiant_option_sync_enabled', $plugin_admin, 'validate_sync_enabled');
        $this->loader->add_filter('validate_podiant_option_player_style_list', $plugin_admin, 'validate_player_style_list');
        $this->loader->add_filter('validate_podiant_option_player_style_single', $plugin_admin, 'validate_player_style_single');
        $this->loader->add_filter('validate_podiant_option_player_position', $plugin_admin, 'validate_player_position');
        $this->loader->add_filter('validate_podiant_option_episodes_prefix', $plugin_admin, 'validate_episodes_prefix');
        $this->loader->add_filter('validate_podiant_option_podcasts_prefix', $plugin_admin, 'validate_podcasts_prefix');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since 1.0
     * @access private
     */
    private function define_public_hooks() {
        $plugin_public = new Podiant_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'register_post_types');
        $this->loader->add_action('init', $plugin_public, 'register_taxonomy');
        $this->loader->add_action('init', $plugin_public, 'sync_episodes');
        $this->loader->add_action('init', $plugin_public, 'disable_sync');
        $this->loader->add_action('init', $plugin_public, 'enable_sync');
        $this->loader->add_filter('the_content', $plugin_public, 'filter_episode_content');
        $this->loader->add_filter('pre_get_posts', $plugin_public, 'filter_main_query');

        $oembed = new Podiant_OEmbed_Provider();
        $this->loader->add_action('init', $oembed, 'register');

        $shortcode = new Podiant_Player_Shortcode();
        add_shortcode('podiant:player', array($shortcode, 'handle'));

        $block = new Podiant_Player_Block();
        $this->loader->add_action('init', $block, 'register');

        $this->loader->add_widget('Podiant_Player_Widget');
        $this->loader->add_widget('Podiant_Latest_Episode_Player_Widget');
    }

    /**
     * Register all of the hooks related to the scheduling functionality
     * of the plugin.
     *
     * @since 1.0
     * @access private
     */
    private function define_cron_hooks() {
        $plugin_cron = new Podiant_Cron($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('podiant_cron_hook', $plugin_cron, 'run');
        if (!wp_next_scheduled('podiant_cron_hook')) {
            wp_schedule_event(time(), 'hourly', 'podiant_cron_hook');
        }
    }

    /**
     * Register all of the commands available from this plugin.
     *
     * @since 1.0
     * @access private
     */
    private function define_cli_commands() {
        $plugin_cli = new Podiant_CLI($this->get_plugin_name(), $this->get_version());

        WP_CLI::add_command('podiant sync', array($plugin_cli, 'sync_podcasts'));
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalisation functionality.
     *
     * @since 1.0
     * @return string The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since 1.0
     * @return Podiant_Loader Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since 1.0
     * @return string The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Instantiates a Podiant_Action_Queue object for queueing sync jobs..
     *
     * @since 1.0
     */
    protected function setup_queue() {
        $this->sync_queue = new Podiant_Action_Queue();
    }

    /**
     * Returns the episode sync queue object.
     *
     * @since 1.0
     * @return Podiant_Action_Queue
     */
    public function get_sync_queue() {
        return $this->sync_queue;
    }

    /**
     * Validates plugin options set via the settings form.
     *
     * @since 1.0
     * @param string $name The name of the option to validate.
     * @param string $value The value to validate.
     * @return mixed
     */
    public function validate_option($name, $value) {
        if (!$value && !is_bool($value)) {
            throw new Podiant_Validation_Exception('This value is required.');
        }

        return apply_filters("validate_podiant_option_{$name}", $value);
    }

    /**
     * Gets or sets plugin options.
     *
     * @since 1.0
     * @param string $name The name of the option to change.
     * @param mixed $value The value to set the option to.
     * @return mixed
     */
    public function option($name, $value=null, $validate=true) {
        $option = PODIANT_OPTION_NAMESPACE . $name;

        if ($value !== null) {
            if ($validate) {
                $value = $this->validate_option($name, $value);
            }

            update_option(
                $option,
                json_encode(
                    array(
                        'type' => gettype($value),
                        'value' => $value,
                        'version' => PODIANT_VERSION
                    )
                )
            );

            return $value;
        }

        $constant = 'PODIANT_' . strtoupper($name);
        $return = get_option($option, null);

        if ($return === null) {
            return constant($constant);
        }

        $return = json_decode($return, true);
        // $return['name'] = $option;
        // print('<pre>' . print_r($return, true) . '</pre>');
        return $return['value'];
    }

    /**
     * Returns a `Podiant_Download` object representing the request to download
     * a given resource.
     * @since 1.0
     * @param string $url The URL of the resource to download
     * @return Podiant_Download
     */
    public function download($url) {
        return new Podiant_Download($url);
    }

    /**
     * Logs a message to the appropriate place. Returns TRUE if the message
     * was delivered to the stream.
     * @since 1.0
     * @param mixed $message The message to log
     * @param string $level The level of message (debug, info, warn/warning, error)
     * @param array $args Optional keyword arguments
     * @return bool
     */
    public function log($message, $level=PODIANT_LOG_INFO, $args=array()) {
        $stream = PODIANT_LOG_STREAM;

        switch ($level) {
            case 'debug':
                $source_level = 1;
                break;
            case 'info':
                $source_level = 2;
                break;
            case 'warn':
            case 'warning':
                $source_level = 3;
                break;
            case 'error':
                $source_level = 4;
                $stream = PODIANT_ERROR_STREAM;
                break;
            default:
                throw new Exception("Unrecognised message level: '{level}'.");
        }

        $levels = array(
            'debug' => 1,
            'info' => 2,
            'warn' => 3,
            'warning' => 3,
            'error' => 4
        );

        $extra = array();
        $exc = null;

        foreach ($args as $key => $value) {
            switch ($key) {
                case 'extra':
                    $extra = is_array($value) ? $value : array();
                    break;

                case 'exc':
                    if (is_a($value, 'Exception')) {
                        $exc = $value;
                    } else {
                        throw new Exception('exc argument must be an Exception.');
                    }

                    break;

                default:
                    throw new Exception("Unrecognised argument: '{$key}'.");
            }
        }

        $target_level = $levels[PODIANT_LOG_LEVEL];
        if ($source_level >= $target_level) {
            if (class_exists('WP_CLI')) {
                switch ($source_level) {
                    case 1:
                        WP_CLI::debug($message);
                        break;

                    case 2:
                        WP_CLI::log($message);
                        break;

                    case 3:
                        WP_CLI::warning($message);
                        break;

                    case 4:
                        WP_CLI::error($message);
                        break;
                }

                if ($exc) {
                    foreach ($lines as $line) {
                        WP_CLI::error("    $line");
                    }
                }
            } else {
                error_log('PODIANT [' . date('j/M/Y:H:i:s O') . "] $message\n", 3, $stream);

                if ($exc) {
                    $lines = explode("\r", $exc->getTraceAsString());

                    foreach ($lines as $line) {
                        error_log("    $line", 3, $stream);
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Shows a WordPress admin notice.
     * @since 1.0
     * @param array Arguments to pass to the notice class constructor.
     * @return bool
     */
    public function notice($args) {
        $kind = 'info';
        $title = null;
        $body = null;
        $cta = null;
        $suppress = null;

        foreach ($args as $key => $value) {
            switch ($key) {
                case 'kind':
                    $kind = $value;
                    break;

                case 'title':
                    $title = $value;
                    break;

                case 'body':
                    $body = $value;
                    break;

                case 'cta':
                    $cta = $value;
                    break;

                case 'suppress':
                    $suppress = $value;
                    break;

                default:
                    throw new Exception("Unrecognised argument: '{$key}'.");
            }
        }

        $notice = new Podiant_Notice($title, $body, $cta, $suppress);
        return $notice->show($kind);
    }
}
