<?php

/**
 * Syncs episodes from the Podiant API with the WordPress database.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Syncs episodes from the Podiant API with the WordPress database.
 *
 * This class defines a background task queue that will read the Podiant
 * API and check for new or updated episodes of each of the users'
 * podcasts.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Action_Queue extends WP_Background_Process {
    /**
     * The name of the queue.
     *
     * @since 1.0
     * @access protected
     * @var string $action The name of the queue.
     */
    protected $action = 'wp_actions';

    /**
     * Runs the sync task.
     *
     * @since 1.0
     */
    protected function task($item) {
        $action = $item['action'];
        $args = array($action);

        if (isset($item['args'])) {
            foreach ($item['args'] as $arg) {
                $args[] = $arg;
            }
        }

        $j = json_encode($args);
        $j = substr($j, 1, strlen($j) - 1);
        podiant_log('do_action(' . $j . ')', PODIANT_LOG_DEBUG);
        call_user_func_array('do_action', $args);

        return false;
    }

    protected function complete() {
        return parent::complete();
    }
}
