<?php require_once PMPRO_DIR . '/adminpages/admin_header.php'; ?>

	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Testimonials', 'pmpro-testimonials' ); ?>
	</h1>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'pmpro_testimonials_settings' );
		do_settings_sections( 'pmpro_testimonials_settings' );

		$confirmation_type    = get_option( 'pmpro_testimonials_confirmation_type', 'message' );
		$redirect_page        = get_option( 'pmpro_testimonials_redirect_page', 0 );
		$confirmation_message = get_option( 'pmpro_testimonials_confirmation_message', '' );
		$star_color           = get_option( 'pmpro_testimonials_star_color', '' );
		?>
		<h3><?php esc_html_e( 'Submission Settings', 'pmpro-testimonials' ); ?></h3>
		<table class="form-table">	
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Type of Confirmation', 'pmpro' ); ?></th>
				<td>
					<select name="pmpro_testimonials_confirmation_type" id="pmpro_confirmation_type">
						<option value="message" <?php selected( $confirmation_type, 'message' ); ?>>
							<?php esc_html_e( 'Message', 'pmpro' ); ?>
						</option>
						<option value="redirect" <?php selected( $confirmation_type, 'redirect' ); ?>>
							<?php esc_html_e( 'Redirect', 'pmpro' ); ?>
						</option>
					</select>
				</td>
			</tr>

			<!-- Redirect Page (shown only if redirect is selected) -->
			<tr valign="top" class="pmpro-redirect-page" style="display: <?php echo ( 'redirect' === $confirmation_type ) ? 'table-row' : 'none'; ?>;">
				<th scope="row"><?php esc_html_e( 'Select Redirect Page', 'pmpro' ); ?></th>
				<td>
					<?php
					wp_dropdown_pages(
						array(
							'name'              => 'pmpro_testimonials_redirect_page',
							'selected'          => $redirect_page,
							'show_option_none'  => __( 'Select a Page', 'pmpro' ),
							'option_none_value' => '0',
						)
					);
					?>
				</td>
			</tr>

			<!-- Confirmation Message (shown only if message is selected) -->
			<tr valign="top" class="pmpro-confirmation-message" style="display: <?php echo ( 'message' === $confirmation_type ) ? 'table-row' : 'none'; ?>;">
				<th scope="row"><?php esc_html_e( 'Confirmation Message', 'pmpro' ); ?></th>
				<td>
					<?php
					wp_editor(
						$confirmation_message,
						'pmpro_testimonials_confirmation_message',
						array(
							'textarea_name' => 'pmpro_testimonials_confirmation_message',
						)
					);
					?>
				</td>
			</tr>

			<!-- Color Picker -->
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Star Color', 'pmpro-testimonials' ); ?></th>
				<td>
					<input type="text" name="pmpro_testimonials_star_color" value="<?php echo esc_attr( $star_color ); ?>" class="wp-color-picker" />
				</td>
			</tr>

		</table>
		<?php wp_nonce_field( 'pmpro_testimonials_settings', 'pmpro_testimonials_settings' ); ?>
		<?php submit_button(); ?>
	</form>
	<hr />
	<p><a href="https://www.paidmembershipspro.com/add-ons/pmpro-testimonials/?utm_source=plugin&utm_medium=pmpro-testimonials-admin&utm_campaign=add-ons" target="_blank"><?php esc_html_e( 'Documentation', 'pmpro_courses' ); ?></a> | <a href="https://www.paidmembershipspro.com/support/?utm_source=plugin&utm_medium=pmpro-testimonials-admin&utm_campaign=support" target="_blank"><?php esc_html_e( 'Support', 'pmpro-testimonials' ); ?></a></p>

	<script type="text/javascript">
		(function($) {
			$( '#pmpro_confirmation_type' ).change(function() {
				var confirmationType = $(this).val();
				if ( confirmationType === 'redirect' ) {
					$('.pmpro-redirect-page').show();
					$('.pmpro-confirmation-message').hide();
				} else if (confirmationType === 'message') {
					$('.pmpro-redirect-page').hide();
					$('.pmpro-confirmation-message').show();
				}
			}).trigger('change');
		})(jQuery);
		jQuery(document).ready(function($) {
			$('.wp-color-picker').wpColorPicker();
		});
	</script>

<?php require_once PMPRO_DIR . '/adminpages/admin_footer.php'; ?>