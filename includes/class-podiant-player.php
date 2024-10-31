<?php

/**
 * Represents an embedded Podiant player.
 *
 * @link https://podiant.co/
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Represents an embedded Podiant player.
 *
 * This class builds an <iframe> tag for a given episode or podcast,
 * specified by subdomain and optional slug.
 *
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Player {
    /**
     * Subdomain of the podcast.
     *
     * @since 1.1
     * @access public
     * @var string $subdomain Subdomain of the podcast.
     */
    public $loader;

    /**
     * Slug identifying the episode.
     *
     * @since 1.1
     * @access public
     * @var string $slug Slug identifying the episode.
     */
    public $slug;

    /**
     * Initialises the player
     * @since 1.1
     * @param string $subdomain Subdomain of the podcast
     * @param string $slug Optional slug identifying the episode
     * @return Podiant_Player
     */
    public function __construct($subdomain, $slug='') {
        $this->subdomain = preg_replace("/[^A-Za-z0-9 ]/", '', $subdomain);

        if ($slug) {
            $this->slug = preg_replace("/[^A-Za-z0-9 ]/", '', $slug);
        }
    }

    /**
     * Renders the player as an HTML string.
     *
     * @since 1.1
     */

    public function render($style=null) {
        if ($this->slug) {
            $url = "https://player.podiant.co/{$this->subdomain}/{$this->slug}/";

            if (!$style) {
                $style = podiant_option('player_style_single');
            }

            $class = '';
            $width = '100%';
            $height = 150;

            switch ($style) {
                case 'mini':
                    if ($this->slug === 'latest') {
                        throw new Exception("The $style player style can only be used with an explicit episode reference (not 'latest').");
                    }

                    $url .= 'mini/';
                    $height = 40;
                    break;

                case 'button':
                    if ($this->slug === 'latest') {
                        throw new Exception("The $style player style can only be used with an explicit episode reference (not 'latest').");
                    }

                    $width = 32;
                    $height = 32;
                    $class = ' style="display: inline;"';
                    $url .= 'button/';
                    break;

                case 'default':
                    $class = ' class="wp-embedded-content"';
                    break;

                default:
                    throw new Exception("Unrecognised player style: '$style'.");
            }

            return (
                '<iframe' . $class . ' ' .
                'sandbox="allow-scripts" ' .
                'src="' . esc_attr($url) . '" ' .
                'width="' . $width . '" ' .
                'height="' . $height . '" ' .
                'frameborder="0">' .
                '</iframe>'
            );
        }

        $url = "https://player.podiant.co/{$this->subdomain}/embed.js";
        return '<script src="' . esc_attr($url) . '"></script>';
    }
}
