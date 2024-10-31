<?php
class PMPro_Testimonial_Display {

	private $testimonials = array();
	private $categories   = array();
	private $tags         = array();
	private $limit        = 3;
	private $order        = 'rand';
	private $orderby      = 'ASC';
	private $layout       = 'default';
	private $style        = '';
	private $elements     = 'all';
	private $columns      = 1;

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

		if ( ! empty( $atts['style'] ) ) {
			$this->style = $atts['style'];
		}

		if ( ! empty( $atts['elements'] ) ) {
			if ( ! is_array( $atts['elements'] ) ) {
				$atts['elements'] = array_map( 'trim', explode( ',', $atts['elements'] ) );
			}
			$this->elements = $atts['elements'];
		}

		if ( ! empty( $atts['columns'] ) ) {
			$this->columns = $atts['columns'];
		}

	}

	public function get_testimonials() {

				// Query testimonials.
		$args = array(
			'post_type' => 'pmpro_testimonial',
			'tax_query' => array(),
			'order'     => $order,
			'orderby'   => $orderby,
		);

		if ( ! empty( $this->testimonials ) ) {
			// Get specific testimonials, don't need other options if present.
			$args['post__in'] = $this->testimonials;
		} else {
			if ( ! empty( $this->limit ) ) {
				$args['posts_per_page'] = $this->limit;
			}
			if ( ! empty( $this->categories ) ) {
				// If we have integers, look by term_id. Otherwise by slug.
				if ( is_integer( $this->categories[0] ) ) {
					$field = 'term_id';
				} else {
					$field = 'slug';
				}
				$args['tax_query'][] = array(
					'taxonomy' => 'pmpro_testimonial_category',
					'field'    => $field,
					'terms'    => $this->categories,
				);
			}
			if ( ! empty( $this->tags ) ) {
				// If we have integers, look by term_id. Otherwise by slug.
				if ( is_integer( $this->tags[0] ) ) {
					$field = 'term_id';
				} else {
					$field = 'slug';
				}
				$args['tax_query'][] = array(
					'taxonomy' => 'pmpro_testimonial_tag',
					'field'    => 'slug',
					'terms'    => $this->tags,
				);
			}
		}

		$args         = apply_filters( 'pmpro_testimonials_query_args', $args );
		$query        = new WP_Query( $args );
		$testimonials = array();
		while ( $query->have_posts() ) :
			$query->the_post();
			$testimonials[] = new PMPro_Testimonial( get_the_ID() );
		endwhile;
		wp_reset_postdata();
		return $testimonials;

	}

	public function display( $echo = true ) {

		$testimonials = $this->get_testimonials();

		if ( empty( $testimonials ) ) {
			return '';
		}

		$html = '<div class="pmpro pmpro_testimonials pmpro_testimonials__' . esc_attr( $this->layout ) . '" style="--pmpro-testimonial-columns: ' . esc_attr( $this->columns ) . '"><div class="pmpro_section">';

		foreach ( $testimonials as $testimonial ) {

			$html .= '<div class="pmpro_testimonial">';
			$html .= $this->get_layout( $testimonial );
			$html .= '</div>';

		}

		$html .= '</div></div>';

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
			$display = $this;
			include $layout_path;
		}

		$output = ob_get_clean();
		return $output;

	}

	public function should_show( $element ) {

		if ( empty( $this->elements ) ) {
			return true;
		} elseif ( is_array( $this->elements ) ) {
			if ( in_array( 'all', $this->elements ) ) {
				return true;
			} else {
				return in_array( $element, $this->elements );
			}
		}

		return false;

	}

}
