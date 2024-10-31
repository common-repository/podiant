<?php

/**
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/lib
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Include wp-background-processing library
require_once dirname(__FILE__) . '/wp-background-processing/classes/wp-async-request.php';
require_once dirname(__FILE__) . '/wp-background-processing/classes/wp-background-process.php';

// Include Parsedown for parsing Markdown
require_once dirname(__FILE__) . '/parsedown/parsedown.php';
