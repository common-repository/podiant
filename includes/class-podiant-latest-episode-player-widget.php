<?php

/**
 * Widget for embedding a player with a podcast's latest episode.
 *
 * @link https://podiant.co/
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Widget for embedding a player with a podcast's latest episode.
 *
 * This class registers a widget that can be used to embed the
 * latest episode of a Podiant podcast.
 *
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Latest_Episode_Player_Widget extends Podiant_Player_Widget {
    /**
     * Widget title.
     *
     * @since 1.1
     * @access protected
     * @var string $title Widget title
     */
    protected $title = 'Latest Episode Player';

    /**
     * Widget description.
     *
     * @since 1.1
     * @access protected
     * @var string $description Widget description
     */
    protected $description = 'Embed the latest episode of a podcast.';

    /**
     * Renders the widget frontend.
     *
     * @since 1.1
     * @access public
     * @var array $args Array of widget presentation arguments
     * @var array $instance The widget settings
     */
    public function widget($args, $instance) {
        if ($subdomain = isset($instance['subdomain']) ? $instance['subdomain'] : '') {
            echo $args['before_widget'];
            $player = new Podiant_Player($subdomain, 'latest');
            echo $player->render();
            echo $args['after_widget'];
        }
    }
}
