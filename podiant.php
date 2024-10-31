<?php

/**
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 *
 * @wordpress-plugin
 * Plugin Name: Podiant
 * Plugin URI: https://podiant.co/wp/
 * Description: Sync your Podiant podcasts with your WordPress website
 * Version: 1.1
 * Author: Mark Steadman
 * Author URI: https://podiant.co/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: podiant
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Plugin constants.
 */
define('PODIANT_VERSION', '1.0');
define('PODIANT_PULL_KEYS_OPTION_NAME', 'podiant_pull_keys');
define('PODIANT_OPTION_NAMESPACE', 'podiant_');
define('PODIANT_LOG_INFO', 'info');
define('PODIANT_LOG_DEBUG', 'debug');
define('PODIANT_LOG_WARNING', 'warn');
define('PODIANT_LOG_ERROR', 'error');

/**
 * Include option defaults
*/
require plugin_dir_path(__FILE__) . 'includes/default-options.php';

/**
 * Include third-party libraries
*/
require plugin_dir_path(__FILE__) . 'lib/autoload.php';

/**
 * The code that runs during plugin activation.
 */
function activate_podiant() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-podiant-activator.php';
    Podiant_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_podiant() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-podiant-deactivator.php';
    Podiant_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_podiant');
register_deactivation_hook(__FILE__, 'deactivate_podiant');

/**
 * The core plugin class that is used to define internationalisation,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-podiant.php';

/**
 * Begins execution of the plugin.
 * @since 1.0
 */
function run_podiant() {
    $plugin = new Podiant();
    $GLOBALS['wp_podiant_plugin'] = $plugin;

    $plugin->run();
}

/**
 * Returns the Podiant plugin object
 * @since 1.0
 * @return Podiant_Plugin
 */
function podiant_plugin() {
    return $GLOBALS['wp_podiant_plugin'];
}

/**
 * Shortcuts to the PodiantPlugin's log function
 * @since 1.0
 * @param mixed $message The message to log
 * @param string $level The level of message (debug, info, warn/warning, error)
 * @param array $args Optional keyword arguments
 * @return bool
 */
function podiant_log($message, $level=PODIANT_LOG_INFO, $args=array()) {
    $plugin = podiant_plugin();
    return $plugin->log($message, $level, $args);
}


/**
 * Shortcuts to the PodiantPlugin's option getter (this shortcut does not
 * allow for setting of options, as that just feels safer).
 * @since 1.0
 * @param string $name The name of the option to retrieve.
 * @return bool
 */
function podiant_option($name) {
    $plugin = podiant_plugin();
    return $plugin->option($name);
}

/**
 * Shortcut to the PodiantPlugin's notice method..
 * @since 1.0
 * @param array $args Arguments to pass to the notice class constructor.
 * @return bool
 */
function podiant_notice($args=array()) {
    $plugin = podiant_plugin();
    return $plugin->notice($args);
}

function podiant_do_action_async($action, $args=array(), $dispatch=true) {
    $plugin = podiant_plugin();
    $queue = $plugin->get_sync_queue();

    $queue->push_to_queue(
        array(
            'action' => "podiant_$action",
            'args' => $args
        )
    );

    $queue->save();
    if ($dispatch) {
        $queue->dispatch();
    }
}

run_podiant();
