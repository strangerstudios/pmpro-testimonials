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
		global $current_user;

		// If we are on the success message page, show it and bail.
		if ( isset( $_GET['testimonial_success'] ) ) {
			$message = get_option( 'pmpro_testimonials_confirmation_message' );

			// Set a default message if one is not in the settings for some reason.
			if ( empty( $message ) ) {
				$message = '<p>' . __( 'Thank you for sharing your testimonial. Our team will review your submission.', 'pmpro-testimonials' ) . '</p>';
			}

			// Wrap it in PMPro classes for styling.
			$message = '<div id="pmpro_testimonials_success" class="' . esc_attr( pmpro_get_element_class( 'pmpro_message pmpro_success', 'pmpro_testimonials_success' ) ) . '">' . $message . '</div>';

			// Show or return the message.
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

					<fieldset id="pmpro_testimonials_submission" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_fieldset', 'pmpro_testimonials_submission' ) ); ?>">
						<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card' ) ); ?>">
							<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card_content' ) ); ?>">
								<legend class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_legend' ) ); ?>">
									<h2 class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_heading pmpro_font-large' ) ); ?>"><?php esc_html_e( 'Submit Your Testimonial', 'pmpro-testimonials' ); ?></h2>
								</legend>
								<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_fields' ) ); ?>">
									
									<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_field pmpro_form_field-required pmpro_form_field-textarea pmpro_form_field-testimonial', 'pmpro_form_field-testimonial' ) ); ?>">
										<label for="testimonial" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>">
											<?php esc_html_e( 'Testimonial', 'pmpro-testimonials' );?>
											<span class="<?php esc_attr_e( pmpro_get_element_class( 'pmpro_asterisk' ) ); ?>"> <abbr title="<?php esc_html_e( 'Required Field', 'pmpro-testimonials' ); ?>">*</abbr></span>
										</label>
										<textarea id="testimonial" name="testimonial" rows="5" cols="80" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-textarea', 'testimonial' ) ); ?>"><?php echo ( ( ! empty( $_POST['testimonial'] ) ) ? esc_textarea( wp_unslash( $_POST['testimonial'] ) ) : '' );?></textarea>
									</div>

									<?php
									// Rating field (Star Rating would still require custom JavaScript)
									echo '<div class="' . esc_attr( pmpro_get_element_class( 'pmpro_form_field pmpro_form_field-required pmpro_form_field-rating', 'pmpro_form_field-rating' ) ) . '">';
									echo '<label class="' . esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ) . '" for="rating">' . esc_html__( 'Rating', 'pmpro-testimonials' ) . ' <span class="' . esc_attr( pmpro_get_element_class( 'pmpro_asterisk' ) ) . '"> <abbr title="' . esc_html__( 'Required Field', 'pmpro-testimonials' ) . '">*</abbr></span></label>';
									echo '<div class="' . esc_attr( pmpro_get_element_class( 'pmpro_star_rating' ) ) . '" role="radiogroup" aria-labelledby="rating">';
									$rating_value = isset( $_POST['rating'] ) ? intval( $_POST['rating'] ) : 0;
									for ( $i = 1; $i <= 5; $i++ ) {
										// Build the selectors for the star.
										$classes = array( 'pmpro_star' );
										if ( $i <= $rating_value ) {
											$classes[] = 'filled';
										} 
										if ( $i === $rating_value ) {
											$checked = 'true';
										} else {
											$checked = 'false';
										}
										$class = join( ' ', $classes );
										$label = sprintf( esc_html__( '%s Star Rating', 'pmpro-testimonials' ), $i );
										echo '<svg role="radio" aria-checked="' . esc_attr( $checked ) . '" aria-label="' . esc_attr( $label ) . '" data-value="' . esc_attr( $i ) . '" class="' . esc_attr( pmpro_get_element_class( $class, 'pmpro_star' ) ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
											<polygon points="12 17.27 18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27" />
										</svg>';
									}
									echo '</div>';
									echo '<input id="rating" type="hidden" name="rating" value="' . esc_attr( $rating_value ) . '">';
									echo '</div>';
									?>

									<?php
									$value = '';
									if ( ! empty( $_POST['display_name'] ) ) {
										$value = wp_unslash( $_POST['display_name'] );
									} elseif ( is_user_logged_in() ) {
										$value = $current_user->display_name;
									}
									?>
									<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_field pmpro_form_field-required pmpro_form_field-text', 'display_name' ) ); ?>">
										<label for="display_name" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>">
											<?php esc_html_e( 'Name', 'pmpro-testimonials' );?>
											<span class="<?php esc_attr_e( pmpro_get_element_class( 'pmpro_asterisk' ) ); ?>"> <abbr title="<?php esc_html_e( 'Required Field', 'pmpro-testimonials' ); ?>">*</abbr></span>
										</label>
										<input id="display_name" type="text" name="display_name" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-text', 'display_name' ) ); ?>" value="<?php echo esc_attr( $value ); ?>" />
									</div>

									<?php
									$value = '';
									if ( ! empty( $_POST['job_title'] ) ) {
										$value = wp_unslash( $_POST['job_title'] );
									}
									$classes = array( 'pmpro_form_field', 'pmpro_form_field-text' );
									$required = false;
									if ( in_array( 'job_title', $this->required_fields ) ) {
										$required = true;
										$classes[] = 'pmpro_form_field-required';
									}
									?>
									<div class="<?php echo esc_attr( pmpro_get_element_class( join( ' ', $classes ), 'job_title' ) ); ?>">
										<label for="job_title" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>">
											<?php esc_html_e( 'Job Title', 'pmpro-testimonials' );?>
											<?php if ( $required ) { ?><span class="<?php esc_attr_e( pmpro_get_element_class( 'pmpro_asterisk' ) ); ?>"> <abbr title="<?php esc_html_e( 'Required Field', 'pmpro-testimonials' ); ?>">*</abbr></span><?php } ?>
										</label>
										<input id="job_title" type="text" name="job_title" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-text', 'job_title' ) ); ?>" value="<?php echo esc_attr( $value ); ?>" />
									</div>

									<?php
									$value = '';
									if ( ! empty( $_POST['company'] ) ) {
										$value = wp_unslash( $_POST['company'] );
									}
									$classes = array( 'pmpro_form_field', 'pmpro_form_field-text', 'pmpro_form_field-company' );
									$required = false;
									if ( in_array( 'company', $this->required_fields ) ) {
										$required = true;
										$classes[] = 'pmpro_form_field-required';
									}
									?>
									<div class="<?php echo esc_attr( pmpro_get_element_class( join( ' ', $classes ), 'pmpro_form_field-company' ) ); ?>">
										<label for="company" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>">
											<?php esc_html_e( 'Company', 'pmpro-testimonials' );?>
											<?php if ( $required ) { ?><span class="<?php esc_attr_e( pmpro_get_element_class( 'pmpro_asterisk' ) ); ?>"> <abbr title="<?php esc_html_e( 'Required Field', 'pmpro-testimonials' ); ?>">*</abbr></span><?php } ?>
										</label>
										<input id="company" type="text" name="company" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-text', 'company' ) ); ?>" value="<?php echo esc_attr( $value ); ?>" />
									</div>

									<?php
									$value = '';
									if ( ! empty( $_POST['user_email'] ) ) {
										$value = wp_unslash( $_POST['user_email'] );
									} elseif ( is_user_logged_in() ) {
										$value = $current_user->user_email;
									}
									$classes = array( 'pmpro_form_field', 'pmpro_form_field-email' );
									$required = false;
									if ( in_array( 'email', $this->required_fields ) ) {
										$required = true;
										$classes[] = 'pmpro_form_field-required';
									}
									$pmpro_email_field_type = apply_filters( 'pmpro_email_field_type', true );
									?>
									<div class="<?php echo esc_attr( pmpro_get_element_class( join( ' ', $classes ), 'pmpro_form_field-email' ) ); ?>">
										<label for="user_email" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label', 'user_email' ) ); ?>">
											<?php esc_html_e( 'Email', 'pmpro-testimonials' );?>
											<?php if ( $required ) { ?><span class="<?php esc_attr_e( pmpro_get_element_class( 'pmpro_asterisk' ) ); ?>"> <abbr title="<?php esc_html_e( 'Required Field', 'pmpro-testimonials' ); ?>">*</abbr></span><?php } ?>
										</label>
										<input id="user_email" type="<?php echo ( $pmpro_email_field_type ? 'email' : 'text' ); ?>" name="user_email" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-email', 'user_email' ) ); ?>" value="<?php echo esc_attr( $value ); ?>" />
									</div>

									<?php
									$value = '';
									if ( ! empty( $_POST['url'] ) ) {
										$value = wp_unslash( $_POST['url'] );
									}
									$classes = array( 'pmpro_form_field', 'pmpro_form_field-text', 'pmpro_form_field-url' );
									$required = false;
									if ( in_array( 'url', $this->required_fields ) ) {
										$required = true;
										$classes[] = 'pmpro_form_field-required';
									}
									?>
									<div class="<?php echo esc_attr( pmpro_get_element_class( join( ' ', $classes ), 'pmpro_form_field-url' ) ); ?>">
										<label for="url" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>">
											<?php esc_html_e( 'URL', 'pmpro-testimonials' );?>
											<?php if ( $required ) { ?><span class="<?php esc_attr_e( pmpro_get_element_class( 'pmpro_asterisk' ) ); ?>"> <abbr title="<?php esc_html_e( 'Required Field', 'pmpro-testimonials' ); ?>">*</abbr></span><?php } ?>
										</label>
										<input id="url" type="url" name="url" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-text', 'url' ) ); ?>" value="<?php echo esc_attr( $value ); ?>" />
									</div>

									<?php

									// Category Dropdown (if enabled).
									if ( filter_var( $this->category_dropdown, FILTER_VALIDATE_BOOLEAN ) ) {
										$categories = get_terms(
											array(
												'taxonomy' => 'pmpro_testimonial_category',
												'hide_empty' => false,
											)
										);
										if ( $categories ) {
											$selected_category = isset( $_POST['testimonial_category'] ) ? intval( $_POST['testimonial_category'] ) : '';
											$classes = array( 'pmpro_form_field', 'pmpro_form_field-select', 'pmpro_form_field-testimonial_category' );
											$required = false;
											if ( in_array( 'testimonial_category', $this->required_fields ) ) {
												$required = true;
												$classes[] = 'pmpro_form_field-required';
											}
											?>
											<div class="<?php echo esc_attr( pmpro_get_element_class( join( ' ', $classes ), 'pmpro_form_field-testimonial_category' ) ); ?>">
												<label for="testimonial_category" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>">
													<?php esc_html_e( 'Category', 'pmpro-testimonials' );?>
													<?php if ( $required ) { ?><span class="<?php esc_attr_e( pmpro_get_element_class( 'pmpro_asterisk' ) ); ?>"> <abbr title="<?php esc_html_e( 'Required Field', 'pmpro-testimonials' ); ?>">*</abbr></span><?php } ?>
												</label>
												<select id="testimonial_category" name="testimonial_category" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-select', 'testimonial_category' ) ); ?>">
													<?php foreach ( $categories as $category ) { ?>
														<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $selected_category, $category->term_id ); ?>><?php echo esc_html( $category->name ); ?></option>
													<?php } ?>
												</select>
											</div>
											<?php
										}
									}

									// Tag Dropdown (if enabled, allows multiple selection).
									if ( filter_var( $this->tag_dropdown, FILTER_VALIDATE_BOOLEAN ) ) {
										$tags = get_terms(
											array(
												'taxonomy' => 'pmpro_testimonial_tag',
												'hide_empty' => false,
											)
										);
										if ( $tags ) {
											$selected_tags = isset( $_POST['testimonial_tags'] ) ? (array) $_POST['testimonial_tags'] : array();
											$classes = array( 'pmpro_form_field', 'pmpro_form_field-select', 'pmpro_form_field-testimonial_tags' );
											$required = false;
											if ( in_array( 'tags', $this->required_fields ) ) {
												$required = true;
												$classes[] = 'pmpro_form_field-required';
											}
											?>
											<div class="<?php echo esc_attr( pmpro_get_element_class( join( ' ', $classes ), 'pmpro_form_field-testimonial_tags' ) ); ?>">
												<label for="url" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_label' ) ); ?>">
													<?php esc_html_e( 'Tags', 'pmpro-testimonials' );?>
													<?php if ( $required ) { ?><span class="<?php esc_attr_e( pmpro_get_element_class( 'pmpro_asterisk' ) ); ?>"> <abbr title="<?php esc_html_e( 'Required Field', 'pmpro-testimonials' ); ?>">*</abbr></span><?php } ?>
												</label>
												<select id="testimonial_tags" name="testimonial_tags[]" multiple="multiple" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_input pmpro_form_input-select', 'testimonial_tags' ) ); ?>" style="width: 100%;">
													<?php foreach ( $tags as $tag ) { ?>
														<option value="<?php echo esc_attr( $tag->term_id ); ?>" <?php selected( in_array( $tag->term_id, $selected_tags ), true ); ?>><?php echo esc_html( $tag->name ); ?></option>
													<?php } ?>
												</select>
												<script>jQuery(document).ready(function($){ $( '#testimonial_tags' ).select2({ theme: "classic", width: "resolve" }); });</script>
											</div>
											<?php
										}
									}
									?>
	
								</div>
							</div>
						</div>
					</fieldset>
	
					<div style="position: absolute; left: -99999px;"><input type="text" name="first_name" /></div>
	
					<div id="pmpro_form_submit-testimonials" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form_submit', 'pmpro_form_submit-testimonials' ) ); ?>">
						<?php wp_nonce_field( 'pmpro_testimonials_form', 'pmpro_testimonials_nonce' ); ?>
						<input type="submit" id="pmpro_btn-submit-testimonials" name="submit" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_btn pmpro_btn-submit', 'pmpro_btn-submit-testimonials' ) ); ?>" value="<?php esc_attr_e( 'Submit', 'pmpro-testimonials' ); ?>" />
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
				$url         = sanitize_url( $_POST['url'] );
				$rating      = intval( $_POST['rating'] );

				$categories = array();
				if ( ! empty( $_POST['categories'] ) ) {
					$categories_string = sanitize_text_field( $_POST['categories'] );
					$categories = array_map( 'trim', explode( ',', $categories_string ) );
				} 
				if ( isset( $_POST['testimonial_category'] ) ) {
					$categories[] = intval( $_POST['testimonial_category'] );
				}
				
				$tags = array();
				if ( ! empty( $_POST['tags'] ) ) {
					$tags_string = sanitize_text_field( $_POST['tags'] );
					$tags = array_map( 'trim', explode( ',', $tags_string ) );
				}
				if ( ! empty( $_POST['testimonial_tags'] ) && is_array( $_POST['testimonial_tags'] ) ) {
					$tags = array_merge( $tags, array_map( 'intval', $_POST['testimonial_tags'] ) );
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
