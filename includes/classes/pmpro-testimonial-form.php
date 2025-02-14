<?php
class PMPro_Testimonial_Form {

	private $required_fields   = array();
	private $categories        = array();
	private $tags              = array();
	private $category_dropdown = false;
	private $tag_dropdown      = false;

	private $errors = array();

	function __construct( $atts = array() ) {

		if ( ! empty( $atts['required_fields'] ) ) {
			if ( ! is_array( $atts['required_fields'] ) ) {
				$atts['required_fields'] = array_map( 'trim', explode( ',', $atts['required_fields'] ) );
			}
			$this->required_fields = $atts['required_fields'];
		}

		if ( ! empty( $atts['categories'] ) ) {
			if ( ! is_array( $atts['categories'] ) ) {
				$atts['categories'] = array_map( 'trim', explode( ',', $atts['categories'] ) );
			}
			$this->categories = $atts['categories'];
		}

		if ( ! empty( $atts['tags'] ) ) {
			if ( ! is_array( $atts['tags'] ) ) {
				$atts['tags'] = array_map( 'trim', explode( ',', $atts['tags'] ) );
			}
			$this->tags = $atts['tags'];
		}

		if ( ! empty( $atts['category_dropdown'] ) ) {
			$this->category_dropdown = $atts['category_dropdown'];
		}

		if ( ! empty( $atts['tag_dropdown'] ) ) {
			$this->tag_dropdown = $atts['tag_dropdown'];
		}

	}

	public function display( $echo = true ) {

		// If we are on the success message page, show it and bail.
		if ( isset( $_GET['testimonial_success'] ) ) {
			$message = get_option( 'pmpro_testimonials_confirmation_message' );

			// Set a default message if one is not in the settings for some reason.
			if ( empty( $message ) ) {
				$message = '<p>' . __( 'Thank you for sharing your testimonial. Our team will review your submission.', 'pmpro-testimonials' ) . '</p>';
			}

			// Wrap it in PMPro classes for styling.
			$message = '<div id="pmpro_testimonials_success" class="' . esc_attr( pmpro_get_element_class( 'pmpro_message pmpro_success' ) ) . '">' . $message . '</div>';

			// Show or return the message.
			if ( $echo ) {
				echo wp_kses_post( $message );
				return;
			} else {
				return $message;
			}
		}

		// Must be running PMPro to show the form.
		if ( ! class_exists( 'PMPro_Field' ) ) {
			$message = '<div id="pmpro_testimonials_error" class="' . esc_attr( pmpro_get_element_class( 'pmpro_message pmpro_error', 'pmpro_testimonials_error' ) ) . '">' . esc_html__( 'Please activate Paid Memberships Pro to use this form.', 'pmpro-testimonials' ) . '</div>';

			if ( $echo ) {
				echo wp_kses_post( $message );
				return;
			} else {
				return $message;
			}
		}

		// Process again to get errors.
		$this->process();

		// Start the form otherwise.
		ob_start();
		?>
		<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro' ) ); ?>">
			<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_section' ) ); ?>">
				<form method="post" action="" id="pmpro_form" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form' ) ); ?>">

					<?php if ( ! empty( $this->errors ) ) { ?>
						<div id="pmpro_message" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_message pmpro_error', 'pmpro_error' ) ); ?>" role="alert"><?php echo wp_kses_post( join( '<br>', $this->errors ) ); ?></div>
					<?php } ?>

					<fieldset id="pmpro_testimonials_submission" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_fieldset' ) ); ?>">
						<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card' ) ); ?>">
							<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card_content' ) ); ?>">
								<legend class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_legend' ) ); ?>">
									<h2 class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_heading pmpro_font-large' ) ); ?>"><?php esc_html_e( 'Submit Your Testimonial', 'pmpro-testimonials' ); ?></h2>
								</legend>
								<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_fields' ) ); ?>">
									<?php
									// Testimonial field
									$title_field = new PMPro_Field(
										'testimonial',
										'textarea',
										array(
											'label'        => __( 'Testimonial', 'pmpro-testimonials' ),
											'required'     => true,
											'showrequired' => 'label',
										)
									);
									$title_field->displayAtCheckout();

									// Rating field (Star Rating would still require custom JavaScript)
									echo '<div class="' . esc_attr( pmpro_get_element_class( 'pmpro_form_field pmpro_form_field-required' ) ) . '">';
									echo '<label class="' . esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ) . '" for="rating">' . esc_html__( 'Rating', 'pmpro-testimonials' ) . ' <span class="' . esc_attr( pmpro_get_element_class( 'pmpro_asterisk' ) ) . '"> <abbr title="' . esc_html__( 'Required Field', 'pmpro-testimonials' ) . '">*</abbr></span></label>';
									echo '<div class="' . esc_attr( pmpro_get_element_class( 'pmpro_star_rating' ) ) . '">';
									$rating_value = isset( $_POST['rating'] ) ? intval( $_POST['rating'] ) : 0;
									for ( $i = 1; $i <= 5; $i++ ) {
										// Build the selectors for the star.
										$classes = array( 'pmpro_star' );
										$classes[] = ( $i <= $rating_value ) ? 'filled' : ''; // Add 'filled' class if previously selected
										$class = implode( ' ', array_unique( $classes ) );
										echo '<svg data-value="' . esc_attr( $i ) . '" class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
											<polygon points="12 17.27 18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27" />
										</svg>';
									}
									echo '</div>';
									echo '<input type="hidden" name="rating" value="' . esc_attr( $rating_value ) . '">';
									echo '</div>';

									// Name field.
									$name_field = new PMPro_Field(
										'display_name',
										'text',
										array(
											'label'        => __( 'Name', 'pmpro-testimonials' ),
											'required'     => true,
											'showrequired' => 'label',
										)
									);
									$name_field->displayAtCheckout();

									// Job Title field.
									$job_title_field = new PMPro_Field(
										'job_title',
										'text',
										array(
											'label'        => __( 'Job Title', 'pmpro-testimonials' ),
											'required'     => in_array( 'job_title', $this->required_fields ),
											'showrequired' => 'label',
										)
									);
									$job_title_field->displayAtCheckout();

									// Company field.
									$company_field = new PMPro_Field(
										'company',
										'text',
										array(
											'label'        => __( 'Company', 'pmpro-testimonials' ),
											'required'     => in_array( 'company', $this->required_fields ),
											'showrequired' => 'label',
										)
									);
									$company_field->displayAtCheckout();

									// Email field.
									$email_field = new PMPro_Field(
										'user_email',
										'text',
										array(
											'label'        => __( 'Email', 'pmpro-testimonials' ),
											'required'     => in_array( 'email', $this->required_fields ),
											'showrequired' => 'label',
										)
									);
									$email_field->displayAtCheckout();

									// URL field.
									$url_field = new PMPro_Field(
										'url',
										'text',
										array(
											'label'        => __( 'URL', 'pmpro-testimonials' ),
											'required'     => in_array( 'url', $this->required_fields ),
											'showrequired' => 'label',
										)
									);
									$url_field->displayAtCheckout();

									// Category Dropdown (if enabled).
									if ( filter_var( $this->category_dropdown, FILTER_VALIDATE_BOOLEAN ) ) {
										$selected_category = isset( $_POST['testimonial_category'] ) ? intval( $_POST['testimonial_category'] ) : '';
										$category_field    = new PMPro_Field(
											'testimonial_category',
											'select',
											array(
												'label'   => __( 'Category', 'pmpro-testimonials' ),
												'options' => wp_list_pluck(
													get_terms(
														array(
															'taxonomy' => 'pmpro_testimonial_category',
															'hide_empty' => false,
														)
													),
													'name',
													'term_id'
												),
												'default' => $selected_category,
											)
										);
										$category_field->displayAtCheckout();
									}

									// Tag Dropdown (if enabled, allows multiple selection).
									if ( filter_var( $this->tag_dropdown, FILTER_VALIDATE_BOOLEAN ) ) {
										$selected_tags = isset( $_POST['testimonial_tags'] ) ? array_map( 'intval', $_POST['testimonial_tags'] ) : array();
										$tag_field     = new PMPro_Field(
											'testimonial_tags',
											'select2',
											array(
												'label'   => __( 'Tags', 'pmpro-testimonials' ),
												'options' => wp_list_pluck(
													get_terms(
														array(
															'taxonomy' => 'pmpro_testimonial_tag',
															'hide_empty' => false,
														)
													),
													'name',
													'term_id'
												),
												'default' => $selected_tags,
											)
										);
										$tag_field->displayAtCheckout();
									}
									?>
	
								</div>
							</div>
						</div>
					</fieldset>
	
					<div style="position: absolute; left: -99999px;"><input type="text" name="first_name" /></div>
	
					<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_submit' ) ); ?>">
						<?php wp_nonce_field( 'pmpro_testimonials_form', 'pmpro_testimonials_nonce' ); ?>
						<input type="submit" name="submit" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_btn pmpro_btn-submit', 'pmpro_btn-submit' ) ); ?>" value="<?php esc_attr_e( 'Submit', 'pmpro-testimonials' ); ?>" />
					</div>

					<?php if ( ! empty( $this->categories ) ) {
						echo '<input type="hidden" name="categories" value="' . esc_attr( join( ',', $this->categories ) ) . '" />';
					} ?>

					<?php if ( ! empty( $this->tags ) ) {
						echo '<input type="hidden" name="tags" value="' . esc_attr( join( ',', $this->tags ) ) . '" />';
					} ?>

				</form>
			</div>
		</div>
		<?php
		$html = ob_get_clean();

		if ( $echo ) {
			echo wp_kses_post( $html );
		} else {
			return $html;
		}

	}

	public function process() {

		if ( isset( $_POST['pmpro_testimonials_nonce'] ) && wp_verify_nonce( $_POST['pmpro_testimonials_nonce'], 'pmpro_testimonials_form' ) ) {

			// Honeypot check.
			if ( ! empty( $_POST['first_name'] ) ) {
				$this->errors[] = esc_html__( 'There was an error submitting this form. Please try again.', 'pmpro-testimonials' );
			}

			// Check if required fields are present.
			$error_fields = array();
			if ( empty( $_POST['testimonial'] ) ) {
				$error_fields[] = esc_html__( 'Testimonial', 'pmpro-testimonials' );
			}
			if ( empty( $_POST['rating'] ) ) {
				$error_fields[] = esc_html__( 'Rating', 'pmpro-testimonials' );
			}
			if ( in_array( 'name', $this->required_fields ) && empty( $_POST['display_name'] ) ) {
				$error_fields[] = esc_html__( 'Name', 'pmpro-testimonials' );
			}
			if ( in_array( 'email', $this->required_fields ) && empty( $_POST['email'] ) ) {
				$error_fields[] = esc_html__( 'Email', 'pmpro-testimonials' );
			}
			if ( in_array( 'job_title', $this->required_fields ) && empty( $_POST['job_title'] ) ) {
				$error_fields[] = esc_html__( 'Job Title', 'pmpro-testimonials' );
			}
			if ( in_array( 'company', $this->required_fields ) && empty( $_POST['company'] ) ) {
				$error_fields[] = esc_html__( 'Company', 'pmpro-testimonials' );
			}
			if ( in_array( 'url', $this->required_fields ) && empty( $_POST['url'] ) ) {
				$error_fields[] = esc_html__( 'Website URL', 'pmpro-testimonials' );
			}

			if ( ! empty( $error_fields ) ) {
				$this->errors[] = sprintf(
					/* translators: %s is a comma-separated list of required fields. */
					esc_html__( 'Please complete all required fields: %s.', 'pmpro-testimonials' ),
					esc_html( implode( ', ', $error_fields ) )
				);
			}

			// If there are errors, show them.
			if ( empty( $this->errors ) ) {

				// Sanitize form input.
				$testimonial = sanitize_textarea_field( $_POST['testimonial'] );
				$name        = sanitize_text_field( $_POST['display_name'] );
				$job_title   = sanitize_text_field( $_POST['job_title'] );
				$company     = sanitize_text_field( $_POST['company'] );
				$email       = sanitize_email( $_POST['user_email'] );
				$url         = esc_url_raw( $_POST['url'] );
				$rating      = intval( $_POST['rating'] );

				if ( ! empty( $_POST['categories'] ) ) { // Handle passed categories from shortcode.
					$categories = sanitize_text_field( $_POST['categories'] );
					$categories = array_map( 'trim', explode( ',', $categories ) );
				} elseif ( isset( $_POST['testimonial_category'] ) ) { // Handle selected category from dropdown.
					$categories = array( intval( $_POST['testimonial_category'] ) ); // Will only ever be one.
				}
				
				if ( ! empty( $_POST['tags'] ) ) { // Handle passed tags from shortcode.
					$tags = sanitize_text_field( $_POST['tags'] );
					$tags = array_map( 'trim', explode( ',', $tags ) );
				} elseif ( isset( $_POST['testimonial_tags'] ) && is_array( $_POST['testimonial_tags'] ) ) { // Handle selected category from dropdown.
					$tags = array_map( 'intval', $_POST['testimonial_tags'] ); // Can be multiple.
				}

				// Split the content by new lines into paragraphs.
				$paragraphs = explode( "\n", trim( $testimonial ) );

				// Convert each paragraph into a Gutenberg paragraph block.
				$gutenberg_content = '';
				foreach ( $paragraphs as $paragraph ) {
					if ( ! empty( trim( $paragraph ) ) ) {
						$gutenberg_content .= "<!-- wp:paragraph -->\n<p>" . esc_html( $paragraph ) . "</p>\n<!-- /wp:paragraph -->\n";
					}
				}

				$post_args = array(
					'post_title'   => $name,
					'post_content' => $gutenberg_content,
					'post_type'    => 'pmpro_testimonial',
					'post_status'  => 'pending', // Set status to 'pending'
					'meta_input'   => array(
						// '_name'      => $name,
						'_job_title' => $job_title,
						'_company'   => $company,
						'_email'     => $email,
						'_url'       => $url,
						'_rating'    => $rating,
					),
				);

				if ( is_user_logged_in() ) {
					$post_args['post_author'] = get_current_user_id();
				}

				// Create new testimonial post in 'pending' status.
				$post_id = wp_insert_post( $post_args );

				// Set categories and tags (if provided via shortcode arguments).
				if ( ! empty( $categories ) ) {
					wp_set_object_terms( $post_id, $categories, 'pmpro_testimonial_category' );
				}
				if ( ! empty( $tags ) ) {
					wp_set_object_terms( $post_id, $tags, 'pmpro_testimonial_tag' );
				}

				// Confirmation message or redirect.
				$confirmation_type = get_option( 'pmpro_testimonials_confirmation_type' );
				if ( $confirmation_type === 'redirect' ) {
					$redirect_page_id = get_option( 'pmpro_testimonials_redirect_page' );
					$redirect_url              = get_permalink( $redirect_page_id );
				} else {
					$redirect_url = add_query_arg( 'testimonial_success', 1, get_permalink() );
				}

				do_action( 'pmpro_testimonial_success' );

				wp_safe_redirect( $redirect_url );
				exit;
			}
		}

	}

}
