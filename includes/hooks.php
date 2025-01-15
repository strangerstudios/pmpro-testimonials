<?php
// Add review schema to site <head>.
function pmpro_testimonial_wp_head() {
    global $post;

	// Check if the post content contains the specific shortcodes
	if ( $post && ( has_shortcode( $post->post_content, 'pmpro_testimonials_display' ) || has_shortcode( $post->post_content, 'pmpro_testimonials_display_custom' ) ) ) {
		// Get site title and URL
		$site_title = get_bloginfo('name');
		$site_url = get_bloginfo('url');
		$schema_type = get_option( 'pmpro_testimonials_schema_type', 'Service' );
		$schema_name = sprintf(
			/* translators: %s: Site title */
			__( '%s Membership', 'pmpro-testimonials' ),
			get_bloginfo( 'name' )
		);
		$schema_description = get_option( 'pmpro_testimonials_schema_description' );
		if ( empty( $schema_description ) ) {
			$schema_description = __( 'Access exclusive content and benefits with our memberships.', 'pmpro-testimonials' );
		}

		// Output the structured data
		echo '<script type="application/ld+json">
{
	"@context": "https://schema.org/",
	"@type": "' . esc_html( $schema_type ) . '",
	"name": "' . esc_html( $schema_name ) . '",
	"description": "' . esc_html( $schema_description ) . '",
	"provider": {
		"@type": "Organization",
		"name": "' . esc_html( $site_title ) . '",
		"url": "' . esc_url( $site_url ) . '"
	}
}
</script>';
	}
}
add_action( 'wp_head', 'pmpro_testimonial_wp_head' );

// Used for the featured image block.
function pmpro_testimonial_featured_image_fallback( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

	// Only modify for 'pmpro_testimonial' post type.
	if ( empty( $post_thumbnail_id ) && get_post_type( $post_id ) === 'pmpro_testimonial' ) {
		$current_testimonial = new PMPro_Testimonial( $post_id );
		$html = $current_testimonial->get_image( $size, $attr );
	}

	return $html;
}
add_filter( 'post_thumbnail_html', 'pmpro_testimonial_featured_image_fallback', 10, 5 );

// Adds the necessary Testimonial schema to the li within the query loop block.
function pmpro_testimonials_custom_query_loop_schema_attrs( $block_content, $block ) {
	// Check if this is the Query Loop block and if it has your specific variation
	if ( $block['blockName'] === 'core/query' && isset( $block['attrs']['query']['postType'] ) && $block['attrs']['query']['postType'] === 'pmpro_testimonial' ) {
		// Modify the output to add attributes to <li> elements
		$block_content = preg_replace_callback(
			'/<li(.*?)>/',
			function ( $matches ) {
				// Add custom attributes here
				return '<li' . $matches[1] . ' itemscope itemtype="http://schema.org/Review">';
			},
			$block_content
		);
	}
	return $block_content;
}
add_filter( 'render_block', 'pmpro_testimonials_custom_query_loop_schema_attrs', 10, 2 );

// Process the form submission.
function pmpro_testimonials_process_form_submission() {
	if ( ! empty( $_POST['pmpro_testimonials_nonce'] ) ) {
		$form = new PMPro_Testimonial_Form();
		$form->process();
	}
}
add_action( 'wp', 'pmpro_testimonials_process_form_submission' );
