<?php

/**
 * Fired during plugin deactivation.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Deactivator {
    /**
     * Flushes the permalink cache.
     *
     * @since 1.0
     */

    public static function deactivate() {
        flush_rewrite_rules();

        $timestamp = wp_next_scheduled('podiant_cron_hook');
        wp_unschedule_event($timestamp, 'podiant_cron_hook');
    }
}
