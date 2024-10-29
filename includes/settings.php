<?php
/**
 * Admin settings page for testimonials for Membership Add On.
 */

/**
 * Add a Testimonials page for settings under the Memberships menu.
 */
function pmpro_testimonials_settings_page() {
	// Course settings page under Memberships menu.
	add_submenu_page( 'pmpro-dashboard', esc_html__( 'Paid Memberships Pro Testimonials - Settings', 'pmpro-testimonials' ), esc_html__( 'Testimonial Settings', 'pmpro-testimonials' ), 'manage_options', 'pmpro-testimonials-settings', 'pmpro_testimonials_settings' );
	add_submenu_page( 'edit.php?post_type=pmpro_testimonial', esc_html__( 'Paid Memberships Pro Testimonials - Settings', 'pmpro-testimonials' ), esc_html__( 'Settings', 'pmpro-testimonials' ), 'manage_options', 'pmpro-testimonials-settings', 'pmpro_testimonials_settings' );
}
add_action( 'admin_menu', 'pmpro_testimonials_settings_page' );

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
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_star_color' );
	register_setting( 'pmpro_testimonials_settings', 'pmpro_testimonials_default_image' );
}

add_action( 'admin_init', 'pmpro_register_settings' );

function pmpro_testimonials_settings_save() {
	// Check permissions.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Check if form is being submitted.
	if ( ! isset( $_POST['pmpro_testimonials_settings'] ) || ! wp_verify_nonce( $_POST['pmpro_testimonials_settings'], 'pmpro_testimonials_settings' ) ) {
		return;
	}

	// Save module specific settings.
	do_action( 'pmpro_testimonials_settings_save' );

}
// add_action( 'admin_init', 'pmpro_testimonials_settings_save' );

function pmpro_testimonials_save_notice() {
	if ( isset( $_REQUEST['pmpro_testimonials_save_settings'] ) ) {
		echo sprintf( "<div class='updated'><p>%s</p></div>", esc_html__( 'Settings saved successfully.', 'pmpro-testimonials' ) );
	}
}
// add_action( 'admin_notices', 'pmpro_testimonials_save_notice', 10 );
