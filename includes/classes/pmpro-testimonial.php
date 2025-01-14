<?php
class PMPro_Testimonial {

	private $id;
	private $testimonial;
	private $name;
	private $rating = 0;
	private $job_title;
	private $company;
	private $url;
	private $email;
	private $categories;
	private $tags;
	private $elements = 'all';
	private $post_data;

	function __construct( $id ) {

		$this->id          = intval( $id );
		$this->post_data   = get_post( $this->id );
		$this->name        = $this->post_data->post_title;
		$this->testimonial = $this->post_data->post_content;

	}

	function get_id() {
		return $this->id;
	}

	function get_name() {
		return $this->name;
	}

	function get_email() {
		if ( empty( $this->email ) ) {
			$this->email = get_post_meta( $this->id, '_email', true );
		}
		return $this->email;
	}

	function get_testimonial() {
		return $this->testimonial;
	}

	function get_date() {
		return get_the_date( 'Y-m-d', $this->id );
	}

	function get_rating() {
		if ( empty( $this->rating ) ) {
			$this->rating = get_post_meta( $this->id, '_rating', true );
		}
		return $this->rating;
	}

	function get_stars() {
		// Get the rating if it's not set.
		if ( empty( $this->rating ) ) {
			$this->get_rating();
		}

		// Return empty string if no rating.
		if ( empty( $this->rating ) ) {
			return '';
		}

		// Build the stars.
		// translators: %d is the rating out of 5 (i.e. 4 out of 5).
		$stars = '<span class="screen-reader-text">' . sprintf( esc_html__( 'Rating: %d out of 5', 'pmpro-testimonials' ), esc_html( $this->rating ) ) . '</span>';
		for ( $i = 1; $i <= $this->rating; $i++ ) {
			$svg    = pmpro_testimonials_get_star( 'filled', 'var(--pmpro--testimonials--star)' );
			$svg    = apply_filters( 'pmpro_testimonials_star_svg', $svg );
			$stars .= $svg;
		}
		for ( $i = 1; $i <= ( 5 - $this->rating ); $i++ ) {
			$svg    = pmpro_testimonials_get_star( '', 'var(--pmpro--testimonials--star)' );
			$svg    = apply_filters( 'pmpro_testimonials_star_svg', $svg );
			$stars .= $svg;
		}
		return $stars;
	}

	function get_job_title() {
		if ( empty( $this->job_title ) ) {
			$this->job_title = get_post_meta( $this->id, '_job_title', true );
		}
		return $this->job_title;
	}

	function get_company( $link = false ) {
		if ( empty( $this->company ) ) {
			$this->company = get_post_meta( $this->id, '_company', true );
		}
		if ( $link && $this->company ) {
			$output = $this->get_url( true, true, $this->company );
		} else {
			$output = $this->company;
		}
		return $output;
	}

	function get_url_raw() {
		if ( empty( $this->url ) ) {
			$this->url = get_post_meta( $this->id, '_url', true );
		}
		return $this->url;
	}

	function get_url( $linked = false, $new_window = true, $label = '' ) {
		$this->get_url_raw();
		if ( $this->url && $linked ) {
			if ( $new_window ) {
				$target = '_blank';
			} else {
				$target = '_self';
			}
			if ( empty( $label ) ) {
				// Fallback to the URL itself as the link label.
				$label = $this->url;
			}
			return '<a href="' . $this->url . '" target="' . $target . '">' . $label . '</a>';
		}
		return $this->url;
	}

	function get_image( $size = 'thumbnail', $attr = array() ) {
		$alt = esc_attr( $this->get_name() );

		// Use the post's featured image if available.
		if ( has_post_thumbnail( $this->id ) ) {
			$image_id = get_post_thumbnail_id( $this->id );
			$attr['alt'] = $alt;
			return wp_get_attachment_image( $image_id, $size, false, $attr );
		}

		// Get default image options and attributes.
		$default_image_id = get_option( 'pmpro_testimonials_default_image', '' );
		$default_image_url = $default_image_id
			? wp_get_attachment_image_url( $default_image_id, $size )
			: PMPRO_TESTIMONIALS_URL . 'images/default-user.png';

		$style = ! empty( $attr['style'] ) ? esc_attr( $attr['style'] ) : '';

		// If the user has an email, use Gravatar.
		if ( $email = $this->get_email() ) {
			$gravatar_url = get_avatar_url( strtolower( trim( $email ) ), array( 'default' => $default_image_url ) );
			if ( $gravatar_url ) {
				return sprintf(
					'<img src="%s" alt="%s" style="%s" />',
					esc_url( $gravatar_url ),
					$alt,
					$style
				);
			}
		}

		// Fallback to default image.
		return sprintf(
			'<img src="%s" alt="%s" style="%s" />',
			esc_url( $default_image_url ),
			$alt,
			$style
		);
	}

	function get_categories( $separator = ', ' ) {
		if ( empty( $this->categories ) ) {
			$this->categories = wp_get_post_terms( $this->id, 'pmpro_testimonial_category', array( 'fields' => 'names' ) );
		}
		$return = '';
		if ( ! empty( $this->categories ) ) {
			$return = join( $separator, $this->categories );
		}
		return $return;
	}

	function get_tags( $separator = ', ' ) {
		if ( empty( $this->tags ) ) {
			$this->tags = wp_get_post_terms( $this->id, 'pmpro_testimonial_tag', array( 'fields' => 'names' ) );
		}
		$return = '';
		if ( ! empty( $this->tags ) ) {
			$return = join( $separator, $this->tags );
		}
		return $return;
	}

	function get_review_schema_name() {
		// translators: %1$s is the site name, %d is the rating, %3$s is the reviewer name.
		return sprintf(
			__( '%1$s Testimonial: %2$s out of 5 stars by %3$s', 'pmpro-testimonials' ),
			esc_html( bloginfo('name') ),
			esc_html( $this->get_rating() ),
			esc_html( $this->name )
		);
	}

	public function should_show( $element ) {
		if ( empty( $this->elements ) || $this->elements === 'all' ) {
			return true;
		}
		if ( is_array( $this->elements ) ) {
			return in_array( $element, $this->elements );
		}
		return false;
	}

}
