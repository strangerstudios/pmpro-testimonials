<?php
// Shortcode for displaying testimonials
function pmpro_testimonials_shortcode_display( $atts, $content = '' ) {
	// Set default values for categories, tags, etc.
	$atts = shortcode_atts(
		array(
			'testimonials' => '',  // ids of specific testimonials, comma-separated
			'testimonial' => '',  // id of a specific testimonial
			'style'	   => '',  // design style to use
			'categories'   => '',  // category slugs, comma-separated
			'tags'		 => '',  // tag slugs, comma-separated
			'limit'		=> '',  // max testimonials to show
			'order'		=> '',  // ASC/DESC
			'orderby'	  => '',  // 'title', 'name', 'rand', 'date'
			'layout'	  => '',  // Layout to use
			'elements'	=> 'all',  // Elements to show, comma-separated
		),
		$atts,
		'pmpro_testimonials_display'
	);

	$testimonials = new PMPro_Testimonial_Display( $atts );
	return $testimonials->display( false );
}
add_shortcode( 'pmpro_testimonials_display', 'pmpro_testimonials_shortcode_display' );

// Shortcode for displaying testimonials with sub shortcodes inside for custom layouts.
function pmpro_testimonials_display_custom( $atts, $content = '' ) {
	// Set default values for categories, tags, etc.
	$atts = shortcode_atts(
		array(
			'testimonials' => '',  // ids of specific testimonials, comma-separated
			'style'	   => '',  // design style to use
			'categories'   => '',  // category slugs, comma-separated
			'tags'		 => '',  // tag slugs, comma-separated
			'limit'		=> '',  // max testimonials to show
			'order'		=> '',  // ASC/DESC
			'orderby'	  => '',  // 'title', 'name', 'rand', 'date'
			'layout'	  => 'default',  // Layout to use
			'elements'	=> 'all',  // Elements to show, comma-separated
		),
		$atts,
		'pmpro_testimonials_display_custom'
	);

	$html = '';
	$display = new PMPro_Testimonial_Display( $atts );
	$testimonials = $display->get_testimonials();
	if ( ! empty( $testimonials ) ) {
		$html = '<div class="' . esc_attr( pmpro_get_element_class( 'pmpro pmpro_testimonials', 'pmpro_testimonials' ) ) . '">';
		foreach ( $testimonials as $testimonial ) {
			$html .= '<div id="' . esc_attr( 'pmpro_testimonial-' . $testimonial->get_id() ) . '" class="' . esc_attr( pmpro_get_element_class( 'pmpro_testimonial', 'pmpro_testimonial-' . $testimonial->get_id() ) ) . '">';
			// Set the current testimonial in a global variable for sub-shortcodes to access
			$GLOBALS['current_pmpro_testimonial'] = $testimonial;
			$html .= do_shortcode( $content );
			unset( $GLOBALS['current_pmpro_testimonial'] );
			$html .= '</div>';
		}
		$html .= '</div>';
	}

	return $html;
}
add_shortcode( 'pmpro_testimonials_display_custom', 'pmpro_testimonials_display_custom' );

// Individual element shortcodes
function pmpro_testimonial_title_shortcode( $atts ) {
	$testimonial = $GLOBALS['current_pmpro_testimonial'];
	return '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__title" ) ) . '">' . esc_html( $testimonial->get_name() ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_title', 'pmpro_testimonial_title_shortcode' );

function pmpro_testimonial_rating_shortcode( $atts ) {
	$testimonial = $GLOBALS['current_pmpro_testimonial'];
	$html        = '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__rating" ) ) . '" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
	$html       .= esc_html( $testimonial->get_rating() );
	$html       .= '<meta itemprop="ratingValue" content="' . esc_attr( $testimonial->get_rating() ) . '" />';
	$html       .= '<meta itemprop="bestRating" content="5" />';
	$html       .= '<meta itemprop="worstRating" content="1" />';
	$html       .= '</span>';
	return $html;
}
add_shortcode( 'pmpro_testimonial_rating', 'pmpro_testimonial_rating_shortcode' );

function pmpro_testimonial_star_rating_shortcode( $atts ) {
	$testimonial = $GLOBALS['current_pmpro_testimonial'];
	$html        = '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__star_rating" ) ) . '" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
	$html       .= esc_html( $testimonial->get_stars() );
	$html       .= '<meta itemprop="ratingValue" content="' . esc_attr( $testimonial->get_rating() ) . '" />';
	$html       .= '<meta itemprop="bestRating" content="5" />';
	$html       .= '<meta itemprop="worstRating" content="1" />';
	$html       .= '</span>';
	return $html;
}
add_shortcode( 'pmpro_testimonial_star_rating', 'pmpro_testimonial_star_rating_shortcode' );

function pmpro_testimonial_content_shortcode( $atts ) {
	$testimonial = $GLOBALS['current_pmpro_testimonial'];
	return '<div class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__testimonial" ) ) . '" itemprop="reviewBody">' . $testimonial->get_testimonial() . '</div>';
}
add_shortcode( 'pmpro_testimonial_content', 'pmpro_testimonial_content_shortcode' );

function pmpro_testimonial_name_shortcode( $atts ) {
	$testimonial = $GLOBALS['current_pmpro_testimonial'];
	return '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__name" ) ) . '" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">' . esc_html( $name ) . '</span></span>';
}
add_shortcode( 'pmpro_testimonial_name', 'pmpro_testimonial_name_shortcode' );

function pmpro_testimonial_job_title_shortcode( $atts ) {
	$testimonial = $GLOBALS['current_pmpro_testimonial'];
	return '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__job_title" ) ) . '" itemprop="jobTitle">' . esc_html( $testimonial->get_job_title() ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_job_title', 'pmpro_testimonial_job_title_shortcode' );

function pmpro_testimonial_company_shortcode( $atts ) {
	$testimonial = $GLOBALS['current_pmpro_testimonial'];
	return '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__company" ) ) . '" itemprop="affiliation" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">' . esc_html( $testimonial->get_company() ) . '</span></span>';
}
add_shortcode( 'pmpro_testimonial_company', 'pmpro_testimonial_company_shortcode' );

function pmpro_testimonial_url_shortcode( $atts ) {
	$testimonial = $GLOBALS['current_pmpro_testimonial'];
	return '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__url" ) ) . '">' . esc_url( $testimonial->get_url() ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_url', 'pmpro_testimonial_url_shortcode' );
