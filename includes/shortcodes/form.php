<?php
/**
 * Shortcode to output a form for submitting testimonials.
 * NOTE: The display method will process the form.
 * 
 * @param array $atts Shortcode attributes for categories, tags, required fields, and dropdown display.
 */
function pmpro_testimonials_shortcode_form( $atts ) {
	// Return early if PMPro is not active.
	if ( ! defined( 'PMPRO_VERSION' ) ) {
		return;
	}

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

	// Let's save the attributes in the cache for if we process the form, for the required_fields
	if ( ! empty( $atts['required_fields'] ) ) {
		set_transient( 'pmpro_testimonials_form_required_fields_' . get_the_ID(), $atts, 10 * MINUTE_IN_SECONDS );
	}

	$form = new PMPro_Testimonial_Form( $atts );
	return $form->display( false );

}
add_shortcode( 'pmpro_testimonials_form', 'pmpro_testimonials_shortcode_form' );
