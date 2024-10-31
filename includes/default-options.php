<?php

/**
 * The file that defines plugin option defaults.
 *
 * This file declares a collection of constants which are used to set
 * default values for varoius settings within the plugin, such as player
 * style and position.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

/* System */
if (!defined('PODIANT_LOG_LEVEL')) {
    define('PODIANT_LOG_LEVEL', PODIANT_LOG_DEBUG);
}

if (!defined('PODIANT_LOG_STREAM')) {
    define('PODIANT_LOG_STREAM', 'php://stdout');
}

if (!defined('PODIANT_ERROR_STREAM')) {
    define('PODIANT_ERROR_STREAM', 'php://stderr');
}

/* Sync options */

if (!defined('PODIANT_SYNC_ENABLED')) {
    define('PODIANT_SYNC_ENABLED', true);
}

/* Permalink options */

if (!defined('PODIANT_EPISODES_PREFIX')) {
    define('PODIANT_EPISODES_PREFIX', 'episodes');
}

if (!defined('PODIANT_PODCASTS_PREFIX')) {
    define('PODIANT_PODCASTS_PREFIX', 'podcasts');
}

/* Player options */

if (!defined('PODIANT_PLAYER_STYLE_LIST')) {
    define('PODIANT_PLAYER_STYLE_LIST', 'mini');
}

if (!defined('PODIANT_PLAYER_STYLE_SINGLE')) {
    define('PODIANT_PLAYER_STYLE_SINGLE', 'default');
}

if (!defined('PODIANT_PLAYER_POSITION')) {
    define('PODIANT_PLAYER_POSITION', 'top');
}

if (!defined('PODIANT_MAIN_QUERY')) {
    define('PODIANT_MAIN_QUERY', false);
}
