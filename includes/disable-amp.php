<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Checks if a post has AMP disabled based on its categories.
 *
 * @param int|WP_Post $post Post ID or post object.
 * @return bool True if AMP should be disabled for this post, false otherwise.
 */
function xwp_amp_deprecator_is_disabled( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return false;
	}

	$selected_categories = get_option( 'xwp_amp_deprecator', [] );
	if ( empty( $selected_categories ) ) {
		return false; // No categories selected for disabling AMP.
	}

	$post_categories = wp_get_post_categories( $post->ID, array( 'fields' => 'ids' ) );
	if ( empty( $post_categories ) ) {
		return false; // No categories.
	}

	foreach ( $post_categories as $category ) {
		if ( in_array( (int) $category, $selected_categories, true ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Display AMP status indicator on admin post edit screen.
 * Only displays in non-production environments.
 */
function xwp_amp_deprecator_admin_amp_status_notice() {
	// Don't show the AMP Status notice in production environments.
	if ( 'production' === wp_get_environment_type() ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'post' !== $screen->base ) {
		return;
	}

	global $post;
	if ( ! $post ) {
		return;
	}

	$has_amp_disabled = xwp_amp_deprecator_is_disabled( $post );

	if ( $has_amp_disabled ) {
		echo '<div class="notice notice-warning is-dismissible">';
		echo '<p><strong>AMP Status:</strong> Disabled.</p>';

		$selected_categories = get_option( 'xwp_amp_deprecator', [] );
		$post_categories     = wp_get_post_categories( $post->ID, array( 'fields' => 'all' ) );
		$matching_cats       = [];

		foreach ( $post_categories as $category ) {
			if ( in_array( (int) $category->term_id, $selected_categories, true ) ) {
				$matching_cats[] = $category->name;
			}
		}

		if ( ! empty( $matching_cats ) ) {
			echo '<p>AMP is disabled because this post belongs to the following disabled categories: <strong>' .
				esc_html( implode( ', ', $matching_cats ) ) . '</strong></p>';
		}

		echo '</div>';
	}
}
add_action( 'admin_notices', 'xwp_amp_deprecator_admin_amp_status_notice' );

/**
 * Remove amphtml link for posts in categories with disabled AMP.
 *
 * @return void
 */
function xwp_amp_deprecator_remove_amphtml_link_for_excluded_category(): void {
	// Only run for singular posts
	if ( ! is_singular() ) {
		return;
	}

	global $post;
	if ( ! $post ) {
		return;
	}
	$has_amp_disabled = xwp_amp_deprecator_is_disabled( $post );

	if ( $has_amp_disabled ) {
		// Remove the amphtml link
		remove_action( 'wp_head', 'amp_add_amphtml_link' );
	}
}
add_action( 'wp_head', 'xwp_amp_deprecator_remove_amphtml_link_for_excluded_category', 1 );

/**
 * Check if the current request is an AMP endpoint.
 *
 * @return bool Whether the current request is an AMP endpoint.
 */
function xwp_amp_deprecator_is_amp_endpoint(): bool {
	$request_uri = ( isset( $_SERVER['REQUEST_URI'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false; // Input var ok.
	$is_amp      = strpos( trailingslashit( strtok( $request_uri, '?' ) ), '/amp/' );

	if ( false !== $is_amp ) {
		return true;
	} elseif ( did_action( 'wp' ) && function_exists( 'is_amp_endpoint' ) && defined( 'AMP_QUERY_VAR' ) ) {
		return is_amp_endpoint();
	}
	return false;
}

/**
 * Redirect AMP pages to non-AMP versions for posts in disabled categories.
 *
 * @return void
 */
function xwp_amp_deprecator_redirect_amp_for_disabled_categories() {
	// Only run on AMP endpoints.
	if ( ! xwp_amp_deprecator_is_amp_endpoint() ) {
		return;
	}

	global $post;
	if ( ! $post ) {
		return;
	}
	$has_amp_disabled = xwp_amp_deprecator_is_disabled( $post );

	if ( $has_amp_disabled ) {
		// Get the non-AMP URL and redirect
		$canonical_url = get_permalink( $post->ID );
		wp_redirect( $canonical_url, 301 );
		exit;
	}
}

add_action( 'wp', 'xwp_amp_deprecator_redirect_amp_for_disabled_categories' );
