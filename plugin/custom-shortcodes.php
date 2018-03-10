<?php
/**
* Plugin Name:  Custom Shortcodes
* Description:  Create, test and deploy custom shortcode form WordPres control panel
* Version:      20180210
* Author:       Filippo Toso
* Author URI:   https://www.filippotoso.com/
* License:      MIT License
*/

if (!defined('ABSPATH')) {
	exit;
}

require_once(__DIR__ . '/includes/includes.php');

// Start the plugin
FTCustomShortcodes::start();

// Register the activation / deactivation hooks
register_activation_hook(__FILE__, [FTCustomShortcodes::instance(), 'activate']);
register_deactivation_hook(__FILE__, [FTCustomShortcodes::instance(), 'deactivate']);
