<?php
/**
 * Admin settings page for testimonials for Membership Add On.
 */

/**
 * Add a Testimonials page for settings under the Memberships menu.
 */
function pmpro_testimonials_settings_page() {
	// Testimonials settings page under Memberships menu.
	add_submenu_page( 'pmpro-dashboard', esc_html__( 'Paid Memberships Pro Testimonials - Settings', 'pmpro-testimonials' ), esc_html__( 'Testimonial Settings', 'pmpro-testimonials' ), 'manage_options', 'pmpro-testimonials-settings', 'pmpro_testimonials_settings' );
	add_submenu_page( 'edit.php?post_type=pmpro_testimonial', esc_html__( 'Paid Memberships Pro Testimonials - Settings', 'pmpro-testimonials' ), esc_html__( 'Settings', 'pmpro-testimonials' ), 'manage_options', 'pmpro-testimonials-settings', 'pmpro_testimonials_settings' );
}
add_action( 'admin_menu', 'pmpro_testimonials_settings_page' );

/**
 * Redirect admin.php?page=pmpro-testimonials-settings
 * to edit.php?post_type=pmpro_testimonial&page=pmpro-testimonials-settings
 */
function pmpro_testimonials_settings_redirect() {
	if ( empty( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'pmpro-testimonials-settings' === $_GET['page'] ) {
		wp_safe_redirect( admin_url( 'edit.php?post_type=pmpro_testimonial&page=pmpro-testimonials-settings' ) );
		exit;
	}
}
add_action( 'admin_init', 'pmpro_testimonials_settings_redirect' );

/**
 * Loads the settings page layout.
 */
function pmpro_testimonials_settings() {
	require_once PMPRO_TESTIMONIALS_DIR . '/includes/adminpages/settings.php';
}

/**
 * Register setting fields with Settings API.
 */
function pmpro_register_settings() {
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_confirmation_type' );
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_redirect_page' );
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_confirmation_message' );
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_schema_type' );
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_schema_description' );
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_star_color' );
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_default_image' );
}
add_action( 'admin_init', 'pmpro_register_settings' );

/**
 * Display a success message after saving settings.
 */
function pmpro_testimonials_settings_success_message() {
	// Check if we are on the Testimonials settings page and if settings were saved.
	if (
		isset( $_GET['page'] ) &&
		'pmpro-testimonials-settings' === $_GET['page'] &&
		isset( $_GET['settings-updated'] ) &&
		'true' === $_GET['settings-updated']
	) {
		echo '<div class="notice notice-success">';
		echo '<p>' . esc_html__( 'Settings saved successfully.', 'pmpro-testimonials' ) . '</p>';
		echo '</div>';
	}
}
add_action( 'admin_notices', 'pmpro_testimonials_settings_success_message' );
