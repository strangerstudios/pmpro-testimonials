<?php
function pmpro_testimonials_block_init() {
	register_block_type( PMPRO_TESTIMONIALS_DIR . '/blocks/build/testimonials-content' );
	register_block_type( PMPRO_TESTIMONIALS_DIR . '/blocks/build/testimonials-name' );
	register_block_type( PMPRO_TESTIMONIALS_DIR . '/blocks/build/testimonials-jobtitle' );
	register_block_type( PMPRO_TESTIMONIALS_DIR . '/blocks/build/testimonials-company' );
	register_block_type( PMPRO_TESTIMONIALS_DIR . '/blocks/build/testimonials-star-rating' );
	// register_block_type( PMPRO_TESTIMONIALS_DIR . '/blocks/build/testimonials-image' );
}
add_action( 'init', 'pmpro_testimonials_block_init' );

function register_pmpro_testimonial_query_variation() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	wp_enqueue_script(
		'pmpro-testimonials-query-variation',
		PMPRO_TESTIMONIALS_URL . '/blocks/src/testimonials-query/index.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		PMPRO_TESTIMONIALS_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'register_pmpro_testimonial_query_variation' );

function pmpro_testimonials_custom_block_category( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'pmpro-testimonials',
				'title' => __( 'PMPro Testimonials', 'pmpro-testimonials' ),
				'icon'  => 'testimonial',
			),
		)
	);
}
add_filter( 'block_categories_all', 'pmpro_testimonials_custom_block_category', 10, 2 );

function pmpro_testimonials_register_metadata() {
	register_post_meta(
		'pmpro_testimonial',
		'_job_title',
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		)
	);
	register_post_meta(
		'pmpro_testimonial',
		'_company',
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		)
	);
	register_post_meta(
		'pmpro_testimonial',
		'_email',
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		)
	);
	register_post_meta(
		'pmpro_testimonial',
		'_url',
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		)
	);
	register_post_meta(
		'pmpro_testimonial',
		'_rating',
		array(
			'type'          => 'number',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'pmpro_testimonials_register_metadata' );

function pmpro_testimonials_enqueue_block_editor_assets() {
	// Get the default color from the options table
	$default_star_color = get_option( 'pmpro_testimonials_star_color', '#FFD700' ); // Fallback to gold if not set

	// Enqueue the script for testimonials-rating block.
	wp_enqueue_script(
		'pmpro-testimonials-star-rating-editor-script',
		PMPRO_TESTIMONIALS_URL . '/blocks/build/testimonials-star-rating/index.js',
		array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-components' ),
		PMPRO_TESTIMONIALS_VERSION,
		true
	);

	// Pass defaults to the editor.
	wp_localize_script(
		'pmpro-testimonials-star-rating-editor-script',
		'pmproTestimonialsSettings',
		array(
			'defaultStarColor' => $default_star_color,
			'defaultUserImage' => PMPRO_TESTIMONIALS_URL . 'images/default-user.png',
		)
	);

}
add_action( 'enqueue_block_editor_assets', 'pmpro_testimonials_enqueue_block_editor_assets' );
