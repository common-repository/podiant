<?php

/**
 * Define the internationalisation functionality.
 *
 * Loads and defines the internationalisation files for this plugin
 * so that it is ready for translation.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Define the internationalisation functionality.
 *
 * Loads and defines the internationalisation files for this plugin
 * so that it is ready for translation.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_i18n {
    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'podiant',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
