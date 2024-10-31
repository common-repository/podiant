<?php

/**
 * The scheduling functionality of the plugin.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/cron
 */

/**
 * The scheduling functionality of the plugin.
 *
 * Defines the plugin name, version, and a function to execute on $
 * schedule.
 *
 * @package Podiant
 * @subpackage Podiant/cron
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Cron {
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
     * Execute cron events
     *
     * @since 1.0
     */
    public function run() {
        $plugin = podiant_plugin();
        $queue = $plugin->get_sync_queue();
        $podcasts = Podiant_Podcast::list();

        foreach($podcasts as $podcast) {
            $podcast->sync(false); // Add the sync job to the queue
        }

        $plugin->log('Dispatching sync queue', PODIANT_LOG_DEBUG);
        $queue->dispatch();
    }
}
