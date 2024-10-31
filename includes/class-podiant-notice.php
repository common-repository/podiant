<?php

/**
 * WordPress notice wrapper class
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * WordPress notice wrapper class.
 *
 * This class defines the properties and adds a method for showing a WordPress admin notice.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Notice {
    /**
     * The title of the notice.
     *
     * @since 1.0
     * @access public
     * @var string $title The title of the notice.
     */
    public $title;

    /**
     * The body of the notice.
     *
     * @since 1.0
     * @access public
     * @var string $body The body of the notice.
     */
    public $body;

    /**
     * Call-to-action options.
     *
     * @since 1.0
     * @access public
     * @var array $cta Call-to-action options.
     */
    public $cta;

    /**
     * Suppression options.
     *
     * @since 1.0
     * @access public
     * @var array $suppress Suppression options.
     */
    public $suppress;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0
     * @param string $title The title of the notice.
     * @param string $body The body of the notice.
     * @param string $cta Call-to-action options.
     * @param string $suppress Suppression options.
     */
    public function __construct($title, $body, $cta=null, $suppress=null) {
        $this->title = $title;
        $this->body = $body;
        $this->cta = $cta;
        $this->suppress = $suppress;
    }

    /**
     * Displays the notice.
     *
     * @since 1.0
     */

    public function show($kind='info') {
        if ($this->title) {
            $title = $this->title;
        }

        if ($this->body) {
            $body = $this->body;
        }

        if ($this->cta) {
            $cta = $this->cta;
        }

        if ($this->suppress) {
            $suppress = $this->suppress;
        }

        include realpath(dirname(__FILE__) . '/../') . '/admin/views/notice.php';
    }
}
