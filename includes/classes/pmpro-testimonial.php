<?php
class PMPro_Testimonial {

	private $id;
	private $title;
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
		$this->title       = $this->post_data->post_title;
		$this->testimonial = $this->post_data->post_content;

	}

	function get_id() {
		return $this->id;
	}

	function get_name() {
		return $this->title;
	}

	function get_testimonial() {
		return $this->testimonial;
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
		$stars = '<span class="screen-reader-text">' . sprintf( __( 'Rating: %d out of 5', 'pmpro-testimonials' ), esc_html( $this->rating ) ) . '</span>';
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

	function get_company() {
		if ( empty( $this->company ) ) {
			$this->company = get_post_meta( $this->id, '_company', true );
		}
		return $this->company;
	}

	function get_url_raw() {
		if ( empty( $this->url ) ) {
			$this->url = get_post_meta( $this->id, '_url', true );
		}
		return $this->url;
	}

	function get_url( $linked = false, $new_window = true, $label = '' ) {
		if ( empty( $this->url ) ) {
			$this->url = get_post_meta( $this->id, '_url', true );
		}
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

	function get_image() {
		// Get the featured image, then the gravatar from the email, then the default image.
		$image = get_the_post_thumbnail( $this->id, array( 96, 96 ) );
		if ( empty( $image ) ) {
			$image = get_avatar( $this->email, 96 );
		}
		if ( empty( $image ) ) {
			$image = '<img src="' . PMPRO_TESTIMONIALS_URL . 'images/default-avatar.png" alt />';
		}
		return $image;
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
