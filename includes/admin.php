<?php
/**
 * Runs only when the plugin is activated.
 *
 * @since 0.1.0
 */
function pmpro_testimonials_activation() {
	// If this plugin is being acitvated, show a notice.
	if ( current_filter() === 'activate_' . PMPRO_TESTIMONIALS_BASENAME ) {
		set_transient( 'pmpro-testimonials-admin-notice', true, 5 );
		$confirmation_message = get_option( 'pmpro_testimonials_confirmation_message' );
		if ( empty( $confirmation_message ) ) {
			update_option( 'pmpro_testimonials_confirmation_message', __( 'Thank you for sharing your testimonial. Our team will review your submission.', 'pmpro-testimonials' ) );
		}
		$star_color = get_option( 'pmpro_testimonials_star_color' );
		if ( empty( $star_color ) ) {
			update_option( 'pmpro_testimonials_star_color', '#ffd700' );
		}
	}
}
register_activation_hook( PMPRO_TESTIMONIALS_BASENAME, 'pmpro_testimonials_activation' );

/**
 * Admin Notice on Activation.
 *
 * @since 0.1.0
 */
function pmpro_testimonials_admin_notice() {
	// Check transient, if available display notice.
	if ( get_transient( 'pmpro-testimonials-admin-notice' ) ) { ?>
		<div class="updated notice is-dismissible">
			<p>
			<?php
				esc_html_e( 'Thank you for activating.', 'pmpro-testimonials' );
				echo ' <a href="' . esc_url( get_admin_url( null, 'post-new.php?post_type=pmpro_testimonial' ) ) . '">';
				esc_html_e( 'Click here to add your first testimonial.', 'pmpro-testimonials' );
				echo '</a>';
			?>
			</p>
		</div>
		<?php
		// Delete transient, only display this notice once.
		delete_transient( 'pmpro-testimonials-admin-notice' );
	}
}
add_action( 'admin_notices', 'pmpro_testimonials_admin_notice' );

/**
 * Function to add links to the plugin action links
 *
 * @param array $links Array of links to be shown in plugin action links.
 */
function pmpro_testimonials_plugin_action_links( $links ) {
	if ( current_user_can( 'manage_options' ) ) {
		$new_links = array(
			'<a href="' . esc_url( get_admin_url( null, 'edit.php?post_type=pmpro_testimonial&page=pmpro-testimonials-settings' ) ) . '">' . esc_html__( 'Settings', 'pmpro-testimonials' ) . '</a>',
		);

		$links = array_merge( $new_links, $links );
	}
	return $links;
}
add_filter( 'plugin_action_links_' . PMPRO_TESTIMONIALS_BASENAME, 'pmpro_testimonials_plugin_action_links' );

/**
 * Function to add links to the plugin row meta
 *
 * @param array  $links Array of links to be shown in plugin meta.
 * @param string $file Filename of the plugin meta is being shown for.
 */
function pmpro_testimonials_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'pmpro-testimonials.php' ) !== false ) {
		$new_links = array(
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/add-ons/testimonials/' ) . '" title="' . esc_attr__( 'View Documentation', 'pmpro-testimonials' ) . '">' . esc_html__( 'Docs', 'pmpro-testimonials' ) . '</a>',
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/support/' ) . '" title="' . esc_attr__( 'Visit Customer Support Forum', 'pmpro-testimonials' ) . '">' . esc_html__( 'Support', 'pmpro-testimonials' ) . '</a>',
		);
		$links     = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'pmpro_testimonials_plugin_row_meta', 10, 2 );
