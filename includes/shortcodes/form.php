<?php
/**
 * Shortcode to output a form for submitting testimonials.
 *
 * @param array $atts Shortcode attributes for categories, tags, required fields, and dropdown display.
 */
function pmpro_testimonials_shortcode_form( $atts ) {
	// Set default values for categories, tags, required fields, and dropdown display.
	$atts = shortcode_atts(
		array(
			'categories'        => '',  // category slugs, comma-separated
			'tags'              => '',  // tag slugs, comma-separated
			'required_fields'   => '',  // fields marked as required, comma-separated
			'category_dropdown' => false, // show dropdown for category selection
			'tag_dropdown'      => false, // show dropdown for tag selection
		),
		$atts,
		'pmpro_testimonials_form'
	);
	$form = new PMPro_Testimonial_Form( $atts );
	return $form->display( false );

}
add_shortcode( 'pmpro_testimonials_form', 'pmpro_testimonials_shortcode_form' );
