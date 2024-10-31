<?php

/**
 * Functions for WP-CLI.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/cron
 */

/**
 * Functions for WP-CLI.
 *
 * Defines functions that can be run from the WordPress command-line interface
 * (WP_CLI).
 *
 * @package Podiant
 * @subpackage Podiant/cron
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_CLI {
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
     * Sync Podiant podcast episode changes with WordPress.
     *
     * @since 1.0
     */
    public function sync_podcasts() {
        $podcasts = Podiant_Podcast::list();
        podiant_log('Manually syncing podcasts', PODIANT_LOG_INFO);

        foreach($podcasts as $podcast) {
            $podcast->fetch_episodes(
                array($podcast, 'create_or_update_post')
            );
        }

        flush_rewrite_rules();
        WP_CLI::success('Finished syncing');
    }
}
