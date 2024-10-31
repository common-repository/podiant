<?php

/**
 * oEmbed provider for Podiant content.
 *
 * @link https://podiant.co/
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * oEmbed provider for Podiant content.
 *
 * This class registers an oEmbed provider that can quickly turn
 * known Podiant episode URLs into HTML, via Podiant's oEmbed API.
 *
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_OEmbed_Provider {
    /**
     * Registers the oEmbed provider.
     *
     * @since 1.1
     */

    public function register() {
        wp_oembed_add_provider(
            'https://*.podiant.co/e/*',
            'https://oembed.podiant.co/provider/'
        );
    }
}
