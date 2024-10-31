<?php

/**
 * Fired during plugin activation.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Activator {
    /**
     * Flushes the permalink cache.
     *
     * @since 1.0
     */
    public static function activate() {
        flush_rewrite_rules();
    }
}
