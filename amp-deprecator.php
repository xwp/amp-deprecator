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
 * Requires Plugins: amp
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Checks if a required plugin is active.
 *
 * @param string $plugin Plugin folder/name.php.
 * @return bool
 */
function xwp_amp_deprecator_is_plugin_active( $plugin ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	return is_plugin_active( $plugin );
}

/**
 * Activation hook to check for required plugins.
 */
function xwp_amp_deprecator_activate() {
	$required_plugin = 'amp/amp.php';

	if ( ! xwp_amp_deprecator_is_plugin_active( $required_plugin ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );

		wp_die(
			sprintf(
				wp_kses(
					/* translators: 1: Plugin Name, 2: Required Plugin Name */
					__( 'Sorry, but <strong>%1$s</strong> requires <strong>%2$s</strong> to be installed and active.', 'amp-deprecator' ),
					[ 'strong' => [] ]
				),
				'<strong>' . esc_html( __( 'Disable AMP by Category', 'amp-deprecator' ) ) . '</strong>',
				'<strong>' . esc_html( __( 'AMP', 'amp-deprecator' ) ) . '</strong>'
			),
			esc_html( __( 'Plugin Dependency Check', 'amp-deprecator' ) ),
			[ 'back_link' => true ]
		);
	}
}
register_activation_hook( __FILE__, 'xwp_amp_deprecator_activate' );

/**
 * Initialize the plugin.
 */
function xwp_amp_deprecator_init() {
	// Ensure the required plugin is active
	$required_plugin = 'amp/amp.php';
	if ( ! xwp_amp_deprecator_is_plugin_active( $required_plugin ) ) {
		return;
	}

	require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/disable-amp.php';
}
add_action( 'plugins_loaded', 'xwp_amp_deprecator_init' );
