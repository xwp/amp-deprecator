<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Initialize the settings page.
 *
 * @return void
 */
function xwp_amp_deprecator_settings_page(): void {
	add_options_page(
		__( 'Disable AMP by Category', 'amp-deprecator' ),
		__( 'Disable AMP by Category', 'amp-deprecator' ),
		'manage_options',
		'amp-deprecator',
		'xwp_amp_deprecator_settings_page_content'
	);
}
add_action( 'admin_menu', 'xwp_amp_deprecator_settings_page' );

/**
 * Output the settings page content.
 *
 * @return void
 */
function xwp_amp_deprecator_settings_page_content(): void {
	// For admins only.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'amp-deprecator' ) );
	}

	if ( isset( $_POST['submit'] ) ) {
		// Verify nonce
		check_admin_referer( 'xwp_amp_deprecator_nonce' );
		$selected_categories = isset( $_POST['categories'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['categories'] ) ) : [];

		// Convert sanitized values to integers
		$selected_categories = array_map( 'intval', $selected_categories );

		update_option( 'xwp_amp_deprecator', $selected_categories );

		echo '<div class="updated"><p>' . esc_html__( 'Settings saved.', 'amp-deprecator' ) . '</p></div>';
	}

	// Get saved categories with default empty array
	$selected_categories = get_option( 'xwp_amp_deprecator', [] );
	$categories          = get_categories();

	// Output the settings form
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Disable AMP by Category', 'amp-deprecator' ); ?></h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'xwp_amp_deprecator_nonce' ); ?>
			<table class="widefat striped">
				<?php foreach ( $categories as $category ) : ?>
					<tr>
						<td>
							<label>
								<input
									type="checkbox"
									name="categories[]"
									value="<?php echo esc_attr( $category->term_id ); ?>"
									<?php checked( in_array( (int) $category->term_id, $selected_categories, true ) ); ?>
								>
								<span><?php echo esc_html( $category->name ); ?></span>
							</label>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<p>
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr__( 'Save Changes', 'amp-deprecator' ); ?>">
			</p>
		</form>
	</div>
	<?php
}
