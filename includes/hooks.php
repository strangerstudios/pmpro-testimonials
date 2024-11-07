<?php
// Used for the featured image block
function pmpro_testimonial_featured_image_fallback( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

	// Only modify for 'pmpro_testimonial' post type.
	if ( empty( $post_thumbnail_id ) && get_post_type( $post_id ) === 'pmpro_testimonial' ) {
		$testimonial = new PMPro_Testimonial( $post_id );
		$html = $testimonial->get_image( $size, $attr );
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
