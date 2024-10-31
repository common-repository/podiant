<?php

/**
 * Register all actions, filters, and widgets for the plugin.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * Register all actions, filters, and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters and to
 * register the widgets.
 *
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Loader {
    /**
     * The array of actions registered with WordPress.
     *
     * @since 1.0
     * @access protected
     * @var array $actions The actions registered with WordPress to fire when the plugin loads.
     */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @since 1.0
     * @access protected
     * @var array $filters The filters registered with WordPress to fire when the plugin loads.
     */
    protected $filters;

    /**
     * The array of widgets registered with WordPress.
     *
     * @since 1.1
     * @access protected
     * @var array $widgets The widgets to be registered with WordPress once the plugin loads.
     */
    protected $widgets;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     * @since 1.0
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
        $this->widgets = array();
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @since 1.0
     * @param string $hook The name of the WordPress action that is being registered.
     * @param object $component A reference to the instance of the object on which the action is defined.
     * @param string $callback The name of the function definition on the $component.
     * @param int $priority Optional. The priority at which the function should be fired. Default is 10.
     * @param int $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_action($hook, $component, $callback, $priority=10, $accepted_args=1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @since 1.0
     * @param string $hook The name of the WordPress filter that is being registered.
     * @param object $component A reference to the instance of the object on which the filter is defined.
     * @param string $callback The name of the function definition on the $component.
     * @param int $priority Optional. The priority at which the function should be fired. Default is 10.
     * @param int $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1
     */
    public function add_filter($hook, $component, $callback, $priority=10, $accepted_args=1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     *
     * @since 1.0
     * @access private
     * @param array $hooks The collection of hooks that is being registered (that is, actions or filters).
     * @param string $hook The name of the WordPress filter that is being registered.
     * @param object $component A reference to the instance of the object on which the filter is defined.
     * @param string $callback The name of the function definition on the $component.
     * @param int $priority The priority at which the function should be fired.
     * @param int $accepted_args The number of arguments that should be passed to the $callback.
     * @return array The collection of actions and filters registered with WordPress.
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = array(
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    /**
     * Add a new widget to the collection to be registered with WordPress.
     *
     * @since 1.1
     * @param string $class The name of the widget class that is being registered.
     */
    public function add_widget($class) {
        $this->widgets[] = $class;
    }

    /**
     * Register the filters and actions with WordPress.
     *
     * @since 1.0
     */
    public function run() {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        add_action('widgets_init', array($this, 'register_widgets'));
    }

    /**
     * Register the widgets with WordPress.
     *
     * @since 1.1
     */
    public function register_widgets() {
        foreach ($this->widgets as $widget) {
            register_widget($widget);
        }
    }
}
