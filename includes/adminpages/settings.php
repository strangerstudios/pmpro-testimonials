<?php
	global $msg, $msgt;

	// Make sure PMPro is active.
	if ( ! defined( 'PMPRO_DIR' ) ) {
		echo "<p>" . esc_html__( 'Please activate Paid Memberships Pro to use the Testimonials Add On.', 'pmpro-testimonials' ) . "</p>";
		return;
	}
?>
<?php require_once PMPRO_DIR . '/adminpages/admin_header.php'; ?>

	<h1>
		<?php esc_html_e( 'Testimonials', 'pmpro-testimonials' ); ?>
	</h1>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'pmpro_testimonials_settings' );
		do_settings_sections( 'pmpro_testimonials_settings' );

		$confirmation_type     = get_option( 'pmpro_testimonials_confirmation_type', 'message' );
		$redirect_page         = get_option( 'pmpro_testimonials_redirect_page', 0 );
		$confirmation_message  = get_option( 'pmpro_testimonials_confirmation_message', '' );
		$schema_type           = get_option( 'pmpro_testimonials_schema_type', 'Service' );
		$schema_description    = get_option( 'pmpro_testimonials_schema_description', '' );
		$star_color            = empty( get_option( 'pmpro_testimonials_star_color', '#ffd700' ) ) ? '#ffd700' : get_option( 'pmpro_testimonials_star_color', '#ffd700' );
		$testimonial_image_id  = get_option( 'pmpro_testimonials_default_image', '' );
		$testimonial_image_url = $testimonial_image_id ? wp_get_attachment_url( $testimonial_image_id ) : PMPRO_TESTIMONIALS_URL . 'images/default-user.png';
		?>
		<div id="pmpro-testimonials-submission-settings" class="pmpro_section" data-visibility="shown" data-activated="true">
			<div class="pmpro_section_toggle">
				<button class="pmpro_section-toggle-button" type="button" aria-expanded="true">
					<span class="dashicons dashicons-arrow-up-alt2"></span>
					<?php esc_html_e( 'Submission Settings', 'pmpro-testimonials' ); ?>
				</button>
			</div> <!-- end pmpro_section_toggle -->
			<div class="pmpro_section_inside">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Type of Confirmation', 'pmpro-testimonials' ); ?></th>
						<td>
							<select name="pmpro_testimonials_confirmation_type" id="pmpro_confirmation_type">
								<option value="message" <?php selected( $confirmation_type, 'message' ); ?>>
									<?php esc_html_e( 'Message', 'pmpro-testimonials' ); ?>
								</option>
								<option value="redirect" <?php selected( $confirmation_type, 'redirect' ); ?>>
									<?php esc_html_e( 'Redirect', 'pmpro-testimonials' ); ?>
								</option>
							</select>
						</td>
					</tr>

					<!-- Redirect Page (shown only if redirect is selected) -->
					<tr valign="top" class="pmpro-redirect-page" style="display: <?php echo ( 'redirect' === $confirmation_type ) ? 'table-row' : 'none'; ?>;">
						<th scope="row"><?php esc_html_e( 'Select Redirect Page', 'pmpro-testimonials' ); ?></th>
						<td>
							<?php
							wp_dropdown_pages(
								array(
									'name'              => 'pmpro_testimonials_redirect_page',
									'selected'          => intval( $redirect_page ),
									'show_option_none'  => esc_html__( 'Select a Page', 'pmpro-testimonials' ),
									'option_none_value' => '0',
								)
							);
							?>
						</td>
					</tr>

					<!-- Confirmation Message (shown only if message is selected) -->
					<tr valign="top" class="pmpro-confirmation-message" style="display: <?php echo ( 'message' === $confirmation_type ) ? 'table-row' : 'none'; ?>;">
						<th scope="row"><?php esc_html_e( 'Confirmation Message', 'pmpro-testimonials' ); ?></th>
						<td>
							<?php
							wp_editor(
								$confirmation_message,
								'pmpro_testimonials_confirmation_message',
								array(
									'textarea_name' => 'pmpro_testimonials_confirmation_message',
									'textarea_rows'	=> 3,
								)
							);
							?>
							<p class="description"><?php esc_html_e( 'This message will be displayed to the member after they submit a testimonial. If you clear this field, a default system message will be displayed.', 'pmpro-testimonials' ); ?></p>
						</td>
					</tr>

				</table>
			</div> <!-- end pmpro_section_inside -->
		</div> <!-- end pmpro-testimonials-submission-settings -->

		<div id="pmpro-testimonials-submission-settings" class="pmpro_section" data-visibility="shown" data-activated="true">
			<div class="pmpro_section_toggle">
				<button class="pmpro_section-toggle-button" type="button" aria-expanded="true">
					<span class="dashicons dashicons-arrow-up-alt2"></span>
					<?php esc_html_e( 'Defaults', 'pmpro-testimonials' ); ?>
				</button>
			</div> <!-- end pmpro_section_toggle -->
			<div class="pmpro_section_inside">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Business Type', 'pmpro-testimonials' ); ?></th>
						<td>
							<select name="pmpro_testimonials_schema_type" id="pmpro_review_schema_type">
								<option value="Service" <?php selected( $schema_type, 'Service' ); ?>>
									<?php esc_html_e( 'Service', 'pmpro-testimonials' ); ?>
								</option>
								<option value="Organization" <?php selected( $schema_type, 'Organization' ); ?>>
									<?php esc_html_e( 'Organization', 'pmpro-testimonials' ); ?>
								</option>
								<option value="EducationalOccupationalProgram" <?php selected( $schema_type, 'EducationalOccupationalProgram' ); ?>>
									<?php esc_html_e( 'Educational/Occupational Program', 'pmpro-testimonials' ); ?>
								</option>
								<option value="MediaSubscription" <?php selected( $schema_type, 'MediaSubscription' ); ?>>
									<?php esc_html_e( 'Media Subscription', 'pmpro-testimonials' ); ?>
								</option>
							</select>
							<p class="description"><?php esc_html_e( 'Select the type of business or service that your testimonials are for.', 'pmpro-testimonials' ); ?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Short Description', 'pmpro-testimonials' ); ?></th>
						<td>
							<input type="text" name="pmpro_testimonials_schema_description" value="<?php echo esc_attr( $schema_description ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'A short description of the business or service that your testimonials are for. If you clear this field, a default system message will be displayed.', 'pmpro-testimonials' ); ?></p>
						</td>
					</tr>

					<!-- Color Picker -->
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Star Color', 'pmpro-testimonials' ); ?></th>
						<td>
							<input type="text" name="pmpro_testimonials_star_color" value="<?php echo esc_attr( $star_color ); ?>" class="wp-color-picker" />
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Default Testimonial Image', 'pmpro-testimonials' ); ?></th>
						<td>
							<div>
								<img id="pmpro_testimonial_image_preview" src="<?php echo esc_url( $testimonial_image_url ); ?>" style="max-width: 150px; height: auto;" />
							</div>
							<input type="hidden" id="pmpro_testimonials_default_image" name="pmpro_testimonials_default_image" value="<?php echo esc_attr( $testimonial_image_id ); ?>" />
							<button type="button" class="button" id="pmpro_testimonial_image_button"><?php esc_html_e( 'Select Image', 'pmpro-testimonials' ); ?></button>
							<button type="button" class="button" id="pmpro_testimonial_image_remove"><?php esc_html_e( 'Remove Image', 'pmpro-testimonials' ); ?></button>
							<p class="description"><?php esc_html_e( 'Note: The image must be hosted on a publicly accessible website. Images stored locally or in development environments may not load correctly.', 'pmpro-testimonials' ); ?></p>
						</td>
					</tr>

				</table>
			</div> <!-- end pmpro_section_inside -->
		</div> <!-- end pmpro-testimonials-submission-settings -->

		<div id="pmpro-testimonials-shortcode-examples" class="pmpro_section" data-visibility="hidden" data-activated="false">
			<div class="pmpro_section_toggle">
				<button class="pmpro_section-toggle-button" type="button" aria-expanded="false">
					<span class="dashicons dashicons-arrow-down-alt2"></span>
					<?php esc_html_e( 'Shortcode Examples', 'pmpro-testimonials' ); ?>
				</button>
			</div> <!-- end pmpro_section_toggle -->
			<div class="pmpro_section_inside" style="display: none;">
				<p>
					<?php esc_html_e( 'Use the following shortcode examples to display testimonials in your membership site.', 'pmpro-testimonials' ); ?>
					<a href="<?php echo esc_url( 'https://www.paidmembershipspro.com/add-ons/testimonials/?utm_source=plugin&utm_medium=pmpro-testimonials-settings&utm_campaign=pmpro-testimonials' ); ?>" target="_blank"><?php esc_html_e( 'Refer to the documentation for a complete list of shortcode attributes and more examples.', 'pmpro-testimonials' ); ?></a>
				</p>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Display Testimonials', 'pmpro-testimonials' ); ?></th>
						<td>
							<code>[pmpro_testimonials_display]</code>
							<p class="description"><?php esc_html_e( 'Displays a list of testimonials or a single testimonial. You can customize the output using shortcode attributes such as "testimonials" (IDs), "categories", "tags", "limit", and more.', 'pmpro-testimonials' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Testimonial Submission Form', 'pmpro-testimonials' ); ?></th>
						<td>
							<code>[pmpro_testimonials_form]</code>
							<p class="description"><?php esc_html_e( 'Displays a form for users to submit their testimonials. Customize fields and dropdowns using attributes such as "categories", "tags", and "required_fields".', 'pmpro-testimonials' ); ?></p>
						</td>
					</tr>
				</table>
			</div> <!-- end pmpro_section_inside -->
		</div> <!-- end pmpro-testimonials-shortcode-examples -->

		<?php wp_nonce_field( 'pmpro_testimonials_settings', 'pmpro_testimonials_settings' ); ?>
		<?php submit_button(); ?>
	</form>
	<hr />
	<p><a href="https://www.paidmembershipspro.com/add-ons/testimonials/?utm_source=plugin&utm_medium=pmpro-testimonials-admin&utm_campaign=add-ons" target="_blank"><?php esc_html_e( 'Documentation', 'pmpro-testimonials' ); ?></a> | <a href="https://www.paidmembershipspro.com/support/?utm_source=plugin&utm_medium=pmpro-testimonials-admin&utm_campaign=support" target="_blank"><?php esc_html_e( 'Support', 'pmpro-testimonials' ); ?></a></p>

	<script type="text/javascript">

		// Pass PHP constant into JavaScript
		var pmproTestimonialsURL = "<?php echo esc_url(PMPRO_TESTIMONIALS_URL); ?>";

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

			// Image selection
			let mediaUploader;
			$('#pmpro_testimonial_image_button').on('click', function(e) {
				e.preventDefault();
				if (mediaUploader) {
					mediaUploader.open();
					return;
				}
				mediaUploader = wp.media({
					title: '<?php esc_html_e( 'Choose Default Testimonial Image', 'pmpro-testimonials' ); ?>',
					button: { text: '<?php esc_html_e( 'Use this image', 'pmpro-testimonials' ); ?>' },
					multiple: false
				});

				mediaUploader.on('select', function() {
					const attachment = mediaUploader.state().get('selection').first().toJSON();
					$('#pmpro_testimonials_default_image').val(attachment.id);
					$('#pmpro_testimonial_image_preview').attr('src', attachment.url);
				});
				mediaUploader.open();
			});

			// Image removal
			$('#pmpro_testimonial_image_remove').on('click', function(e) {
				e.preventDefault();
				$('#pmpro_testimonials_default_image').val('');
				$('#pmpro_testimonial_image_preview').attr('src', pmproTestimonialsURL + 'images/default-user.png');
			});

		})(jQuery);

		jQuery(document).ready(function($) {
			$('.wp-color-picker').wpColorPicker();
		});

	</script>

<?php require_once PMPRO_DIR . '/adminpages/admin_footer.php'; ?>
