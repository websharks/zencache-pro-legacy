<?php
/*
Version: 150511
Text Domain: zencache
Plugin Name: ZenCache Pro
Network: true

Author: WebSharks, Inc.
Author URI: http://www.websharks-inc.com/

Plugin URI: http://zencache.com/
Description: ZenCache is an advanced WordPress caching plugin inspired by simplicity.
*/
if (!defined('WPINC')) {
    exit('Do NOT access this file directly: '.basename(__FILE__));
}
$GLOBALS['wp_php_rv'] = '5.3.2'; //php-required-version//
if (require(dirname(__FILE__).'/src/vendor/websharks/wp-php-rv/src/includes/check.php')) {
    require_once dirname(__FILE__).'/src/includes/plugin.php';
} else {
    wp_php_rv_notice('ZenCache Pro');
}
