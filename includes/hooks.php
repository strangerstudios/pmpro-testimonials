<?php
function pmpro_testimonial_featured_image_fallback( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	// Only modify for 'pmpro_testimonial' post type.
	if ( get_post_type( $post_id ) === 'pmpro_testimonial' ) {
		// Check if a featured image exists.
		if ( empty( $post_thumbnail_id ) ) {
			// Get the _email meta value
			$email = get_post_meta( $post_id, '_email', true );

			$style = ( ! empty( $attr['style'] ) ) ? $attr['style'] : '';
			$alt   = get_the_title( $post_id );

			// Generate Gravatar URL if email is available
			if ( $email ) {
				$gravatar_url = get_avatar_url( strtolower( trim( $email ) ) );
				if ( $gravatar_url ) {
					// Gravatar exists, use it
					return '<img src="' . esc_url( $gravatar_url ) . '" alt="' . esc_attr( $alt ) . '" style="' . esc_attr( $style ) . '" />';
				}
			}

			// If no Gravatar, look for settings fallback image.
			$default_image_id = get_option( 'pmpro_testimonials_default_image', '' );
			if ( $default_image_id ) {
				return wp_get_attachment_image( $default_image_id, $size, false, $attr );
			}

			// Our internal fallback image.
			$fallback_image_url = PMPRO_TESTIMONIALS_URL . 'images/default-user.png';
			return '<img src="' . esc_url( $fallback_image_url ) . '" alt="' . esc_attr( $alt ) . '" style="' . esc_attr( $style ) . '" />';
		}
	}

	// Return the original HTML if featured image exists or is not pmpro_testimonial post type.
	return $html;
}
add_filter( 'post_thumbnail_html', 'pmpro_testimonial_featured_image_fallback', 10, 5 );
