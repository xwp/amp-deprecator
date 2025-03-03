<?php
/**
 * Plugin Name: Disable AMP by Category
 * Plugin URI: https://github.com/xwp/amp-deprecator
 * Description: Allows users to choose which categories to disable AMP on.
 * Version: 1.0
 * Author: XWP
 * Author URI: https://xwp.co
 * License: GPLv2+
 * Text Domain: amp-deprecator
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Initialize the plugin.
 */
function xwp_amp_deprecator_init() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/disable-amp.php';
}
add_action( 'plugins_loaded', 'xwp_amp_deprecator_init' );
