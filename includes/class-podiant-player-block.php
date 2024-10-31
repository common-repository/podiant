<?php

/**
 * Gutenberg block for embedding players.
 *
 * @link https://podiant.co/
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Gutenberg block for embedding players.
 *
 * This class registers a Gutenberg block that can be used to embed an
 * episode of a Podiant podcast, or the entire podcast.
 *
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Player_Block {
    /**
     * Registers the Gutenberg block.
     *
     * @since 1.1
     */

    public function register() {
        wp_register_script(
            'podiant-player-block',
            plugins_url('blocks/player.js', dirname(__file__)),
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'),
            filemtime(
                dirname(dirname(__file__)) . '/blocks/player.js'
            )
        );

        register_block_type(
            'podiant/player',
            array(
                'editor_script' => 'podiant-player-block'
            )
        );
    }
}
