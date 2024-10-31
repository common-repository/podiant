<?php

/**
 * Shortcode for embedding players.
 *
 * @link https://podiant.co/
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Shortcode for embedding players.
 *
 * This class registers a shortcode that can be used to embed an
 * episode of a Podiant podcast, or the entire podcast.
 *
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Player_Shortcode {
    /**
     * Interprets the shortcode input and produces relevant output.
     *
     * @since 1.1
     */

    public function handle($args) {
        if (isset($args['subdomain'])) {
            $subdomain = $args['subdomain'];
        } else {
            $subdomain = $args[0];
        }

        if (isset($args['slug'])) {
            $slug = $args['slug'];
        } elseif (count($args) > 1) {
            $slug = $args[1];
        } else {
            $slug = '';
        }

        if (isset($args['style'])) {
            $style = $args['style'];
        } elseif (count($args) > 2) {
            $style = $args[2];
        } else {
            $style = null;
        }

        $player = new Podiant_Player(
            wp_strip_all_tags($subdomain),
            wp_strip_all_tags($slug)
        );

        if (!$style) {
            $style = podiant_option('player_style_single');
        }

        if ($style === 'button') {
            $style = 'mini';
        }

        try {
            return $player->render(
                wp_strip_all_tags($style)
            );
        } catch (Exception $err) {
            $div = '<div style="border: 1px solid #900; border-radius: 4px; background: #fff; padding: 15px; color: #900; font-family: monospace;">';
            $div .= '<strong>Error embedding player from shortcode:</strong><br>';
            $div .= esc_html($err->getMessage());
            $div .= '</div>';

            return $div;
        }
    }
}
