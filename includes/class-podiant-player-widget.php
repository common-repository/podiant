<?php

/**
 * Widget for embedding a player with a list of episodes.
 *
 * @link https://podiant.co/
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Widget for embedding a player with a list of episodes.
 *
 * This class registers a widget that can be used to embed an
 * entire podcast.
 *
 * @since 1.1
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Player_Widget extends WP_Widget {
    /**
     * Widget title.
     *
     * @since 1.1
     * @access protected
     * @var string $title Widget title
     */
    protected $title = 'Podcast Player';

    /**
     * Widget description.
     *
     * @since 1.1
     * @access protected
     * @var string $description Widget description
     */
    protected $description = 'Embed a player showing all episodes of a podcast.';

    /**
     * Constructs the widget object.
     *
     * @since 1.1
     * @access public
     */
    function __construct() {
        parent::__construct(
            get_class($this),
            __($this->title, 'podiant'),
            array(
                'description' => __($this->description, 'podiant')
            )
        );
    }

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
            $player = new Podiant_Player($subdomain);
            echo $player->render();
            echo $args['after_widget'];
        }
    }

    /**
     * Renders the widget settings form.
     *
     * @since 1.1
     * @access public
     * @var array $instance The widget settings
     */
    public function form($instance) {
        if ($subdomain = isset($instance['subdomain']) ? $instance['subdomain'] : '') {
            $url = "https://{$subdomain}.podiant.co/";
        } else {
            $url = '';
        } ?>
        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Podcast Website URL:', 'player widget url label', 'podiant'); ?></label>
            <input id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="url" value="<?php echo esc_attr($url); ?>" placeholder="https://platform.podiant.co/" class="widefat">
        </p>
    <?php }

    /**
     * Updates the widget settings.
     *
     * @since 1.1
     * @access public
     * @var array $new_instance The new widget settings
     * @var array $instance The old widget settings
     */
    public function update($new_instance, $old_instance) {
        if ($url = (isset($new_instance['url']) ? $new_instance['url'] : '')) {
            if (preg_match('/^(?:https?\:\/\/)?(?:www\.)?([a-z0-9]+)\.podiant\.co/i', $url, $matches)) {
                return array(
                    'subdomain' => $matches[1]
                );
            }
        }

        return array();
    }
}
