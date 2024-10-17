<?php
/**
 * Shortcode to output testimonials.
 *
 * @param array $atts Shortcode attributes for categories, tags, etc.
 */
function pmpro_testimonials_shortcode_display( $atts ) {
	// Set default values for categories, tags, etc.
	$atts = shortcode_atts(
		array(
			'testimonials' => '',  // ids of specific testimonials, comma-separated
			'categories'   => '',  // category slugs, comma-separated
			'tags'         => '',  // tag slugs, comma-separated
			'limit'        => '',  // max testimonials to show
			'order'        => '',  // ASC/DESC
			'orderby'      => '',  // 'title', 'name', 'rand', 'date'
		),
		$atts,
		'pmpro_testimonials_display'
	);

	$testimonials = new PMPro_Testimonial_Display( $atts );
	return $testimonials->display( false );

}
add_shortcode( 'pmpro_testimonials_display', 'pmpro_testimonials_shortcode_display' );
