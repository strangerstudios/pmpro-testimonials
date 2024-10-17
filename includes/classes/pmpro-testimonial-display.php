<?php
class PMPro_Testimonial_Display {

	private $testimonials = array();
	private $categories   = array();
	private $tags         = array();
	private $limit        = 3;
	private $order        = 'rand';
	private $orderby      = 'ASC';
	private $layout       = 'default';

	function __construct( $atts = array() ) {

		if ( ! empty( $atts['ids'] ) ) {
			if ( ! is_array( $atts['ids'] ) ) {
				$atts['ids'] = array_map( 'trim', explode( ',', $atts['ids'] ) );
			}
			$this->testimonials = $atts['ids'];
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

		if ( ! empty( $atts['limit'] ) ) {
			$this->limit = intval( $atts['limit'] );
		}

		if ( ! empty( $atts['order'] ) ) {
			// Only allow valid order options.
			if ( in_array( strtoupper( $atts['order'] ), array( 'ASC', 'DESC' ) ) ) {
				$this->order = strtoupper( $atts['order'] );
			}
		}

		if ( ! empty( $atts['orderby'] ) ) {
			// Only allow valid order options.
			if ( in_array( strtolower( $atts['orderby'] ), array( 'title', 'name', 'rand', 'date' ) ) ) {
				$this->orderby = $atts['orderby'];
			}
		}

		if ( ! empty( $atts['layout'] ) ) {
			$this->layout = $atts['layout'];
		}

	}

	public function display( $echo = true ) {

		// Query testimonials.
		$args = array(
			'post_type' => 'pmpro_testimonial',
			'tax_query' => array(),
		);

		if ( ! empty( $this->testimonials ) ) {
			// Get specific testimonials, don't need other options if present.
			$args['post__in'] = $this->testimonials;
		} else {
			if ( ! empty( $this->limit ) ) {
				$args['posts_per_page'] = $this->limit;
			}
			if ( ! empty( $this->categories ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'pmpro_testimonial_category',
					'field'    => 'slug',
					'terms'    => $this->categories,
				);
			}
			if ( ! empty( $this->tags ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'pmpro_testimonial_tag',
					'field'    => 'slug',
					'terms'    => $this->tags,
				);
			}
		}

		$args = apply_filters( 'pmpro_testimonials_query_args', $args );

		$html = '<div class="pmpro_testimonials pmpro_testimonials__' . esc_attr( $this->layout ) . '">';

		$query = new WP_Query( $args );

		while ( $query->have_posts() ) :
			$query->the_post();

			$testimonial = new PMPro_Testimonial( get_the_ID() );
			$html       .= '<div class="pmpro_testimonial">';
			$html       .= $this->get_layout( $testimonial );
			$html       .= '</div>';

		endwhile;
		wp_reset_postdata();

		$html .= '</div>';

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}

	}

	public function get_layout( $testimonial ) {

		ob_start();

		$layout_path = PMPRO_TESTIMONIALS_DIR . '/layouts/' . $this->layout . '.php';

		if ( file_exists( $layout_path ) ) {
			include $layout_path;
		}

		$output = ob_get_clean();
		return $output;

	}

}
