<?php
/**
 * Register Custom Post Type for Courses
 * Hooks into init.
 */
function pmpro_testimonials_cpt() {
	$labels = array(
		'name'                  => esc_html_x( 'Testimonials', 'Post Type General Name', 'pmpro-testimonials' ),
		'singular_name'         => esc_html_x( 'Testimonial', 'Post Type Singular Name', 'pmpro-testimonials' ),
		'menu_name'             => esc_html__( 'Testimonials', 'pmpro-testimonials' ),
		'name_admin_bar'        => esc_html__( 'Testimonial', 'pmpro-testimonials' ),
		'archives'              => esc_html__( 'Testimonial Archives', 'pmpro-testimonials' ),
		'attributes'            => esc_html__( 'Testimonial Attributes', 'pmpro-testimonials' ),
		'all_items'             => esc_html__( 'All Testimonials', 'pmpro-testimonials' ),
		'add_new_item'          => esc_html__( 'Add New Testimonial', 'pmpro-testimonials' ),
		'add_new'               => esc_html__( 'Add New Testimonial', 'pmpro-testimonials' ),
		'new_item'              => esc_html__( 'New Testimonial', 'pmpro-testimonials' ),
		'edit_item'             => esc_html__( 'Edit Testimonial', 'pmpro-testimonials' ),
		'update_item'           => esc_html__( 'Update Testimonial', 'pmpro-testimonials' ),
		'view_item'             => esc_html__( 'View Testimonial', 'pmpro-testimonials' ),
		'view_items'            => esc_html__( 'View Testimonials', 'pmpro-testimonials' ),
		'search_items'          => esc_html__( 'Search Testimonials', 'pmpro-testimonials' ),
		'not_found'             => esc_html__( 'Testimonial not found', 'pmpro-testimonials' ),
		'not_found_in_trash'    => esc_html__( 'Testimonial not found in Trash', 'pmpro-testimonials' ),
		'featured_image'        => esc_html__( 'Featured Image', 'pmpro-testimonials' ),
		'set_featured_image'    => esc_html__( 'Set testimonial featured image', 'pmpro-testimonials' ),
		'remove_featured_image' => esc_html__( 'Remove featured image', 'pmpro-testimonials' ),
		'use_featured_image'    => esc_html__( 'Use as testimonial featured image', 'pmpro-testimonials' ),
		'insert_into_item'      => esc_html__( 'Insert into testimonial', 'pmpro-testimonials' ),
		'uploaded_to_this_item' => esc_html__( 'Uploaded to this testimonial', 'pmpro-testimonials' ),
		'items_list'            => esc_html__( 'PMPro Testimonials list', 'pmpro-testimonials' ),
		'items_list_navigation' => esc_html__( 'Testimonials list navigation', 'pmpro-testimonials' ),
		'filter_items_list'     => esc_html__( 'Filter Testimonials list', 'pmpro-testimonials' ),
	);
	$args   = array(
		'label'               => esc_html__( 'Testimonial', 'pmpro-testimonials' ),
		'description'         => esc_html__( 'Testimonials from members.', 'pmpro-testimonials' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'        => true,
		'public'              => false,
		'menu_icon'           => 'dashicons-format-quote',
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 10,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
		'show_in_rest'        => true,
	);
	register_post_type( 'pmpro_testimonial', $args );

	// Register Category Taxonomy.
	register_taxonomy(
		'pmpro_testimonial_category',
		'pmpro_testimonial',
		array(
			'label'              => esc_html__( 'Categories', 'pmpro-testimonials' ),
			'hierarchical'       => true,
			'show_in_rest'       => true,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
		)
	);
	register_taxonomy(
		'pmpro_testimonial_tag',
		'pmpro_testimonial',
		array(
			'label'              => esc_html__( 'Tags', 'pmpro-testimonials' ),
			'hierarchical'       => false,
			'show_in_rest'       => true,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
		)
	);
}
add_action( 'init', 'pmpro_testimonials_cpt', 30 );

/**
 * Define the metaboxes.
 */
function pmpro_testimonials_define_meta_boxes() {
	add_meta_box( 'pmpro_testimonials', esc_html__( 'Testimonial Information', 'pmpro-testimonials' ), 'pmpro_testimonials_meta_box', 'pmpro_testimonial', 'normal', 'high' );
}
add_action( 'admin_menu', 'pmpro_testimonials_define_meta_boxes', 20 );

/**
 * Callback for testimonials meta box
 */
function pmpro_testimonials_meta_box() {
	global $post;

	// boot out people without permissions
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false;
	}

	// Retrieve saved metadata
	$name      = get_post_meta( $post->ID, '_name', true );
	$job_title = get_post_meta( $post->ID, '_job_title', true );
	$company   = get_post_meta( $post->ID, '_company', true );
	$email     = get_post_meta( $post->ID, '_email', true );
	$url       = get_post_meta( $post->ID, '_url', true );
	$rating    = get_post_meta( $post->ID, '_rating', true );
	?>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="pmpro_testimonials_job_title"><?php esc_html_e( 'Job Title', 'pmpro-testimonials' ); ?></label>
				</th>
				<td>
					<input id="pmpro_testimonials_job_title" name="job_title" type="text" value="<?php echo esc_attr( $job_title ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pmpro_testimonials_company"><?php esc_html_e( 'Company', 'pmpro-testimonials' ); ?></label>
				</th>
				<td>
					<input id="pmpro_testimonials_company" name="company" type="text" value="<?php echo esc_attr( $company ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pmpro_testimonials_email"><?php esc_html_e( 'Email', 'pmpro-testimonials' ); ?></label>
				</th>
				<td>
					<input id="pmpro_testimonials_email" name="email" type="email" value="<?php echo esc_attr( $email ); ?>" />
					<br /><span class="description"><?php esc_html_e( "Used to optionally display the member's avatar via Gravatar (not shown publicly).", 'pmpro-testimonials' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pmpro_testimonials_url"><?php esc_html_e( 'URL', 'pmpro-testimonials' ); ?></label>
				</th>
				<td>
					<input id="pmpro_testimonials_url" name="url" type="url" value="<?php echo esc_url( $url ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pmpro_testimonials_rating"><?php esc_html_e( 'Rating', 'pmpro-testimonials' ); ?></label>
				</th>
				<td>
					<div id="pmpro_testimonials_rating" class="pmpro_star_rating" data-rating="<?php echo esc_attr( $rating ); ?>">
						<input type="hidden" name="rating" value="<?php echo esc_attr( $rating ); ?>" />
						<svg class="pmpro_star" data-value="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<polygon points="12 17.27 18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27" />
						</svg>
						<svg class="pmpro_star" data-value="2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<polygon points="12 17.27 18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27" />
						</svg>
						<svg class="pmpro_star" data-value="3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<polygon points="12 17.27 18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27" />
						</svg>
						<svg class="pmpro_star" data-value="4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<polygon points="12 17.27 18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27" />
						</svg>
						<svg class="pmpro_star" data-value="5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<polygon points="12 17.27 18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27" />
						</svg>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<?php wp_nonce_field( 'pmpro_testimonials_save_meta', 'pmpro_testimonials_nonce' ); ?>

	<?php
}

/**
 * Save meta data for testimonials from the edit testimonial page.
 */
function pmpro_testimonials_save_meta( $post_id, $post ) {
	// Only testimonials.
	if ( 'pmpro_testimonial' !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Verify the nonce before proceeding.
	if ( ! isset( $_POST['pmpro_testimonials_nonce'] ) || ! wp_verify_nonce( $_POST['pmpro_testimonials_nonce'], 'pmpro_testimonials_save_meta' ) ) {
		return;
	}

	// Save Job Title
	if ( isset( $_POST['job_title'] ) ) {
		update_post_meta( $post_id, '_job_title', sanitize_text_field( $_POST['job_title'] ) );
	}

	// Save Company
	if ( isset( $_POST['company'] ) ) {
		update_post_meta( $post_id, '_company', sanitize_text_field( $_POST['company'] ) );
	}

	// Save Email
	if ( isset( $_POST['email'] ) ) {
		update_post_meta( $post_id, '_email', sanitize_email( $_POST['email'] ) );
	}

	// Save URL
	if ( isset( $_POST['url'] ) ) {
		update_post_meta( $post_id, '_url', esc_url_raw( $_POST['url'] ) );
	}

	// Save Rating
	if ( isset( $_POST['rating'] ) ) {
		update_post_meta( $post_id, '_rating', intval( $_POST['rating'] ) );
	}

}
add_action( 'save_post', 'pmpro_testimonials_save_meta', 10, 2 );

/**
 * First testimonial screen to help get started.
 */
function pmpro_testimonials_testimonial_check() {
	global $pagenow;

	if ( empty( $_GET['s'] ) && $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'pmpro_testimonial' ) {
		$query = new WP_Query(
			array(
				'post_type'   => 'pmpro_testimonial',
				'post_status' => array( 'any', 'trash' ),
			)
		);
		if ( $query->found_posts == 0 ) {
			echo '<style>.wrap { display: none; }</style>';
			add_thickbox();
			add_action( 'admin_notices', 'pmpro_testimonials_get_started' );
		}
	}
}
add_action( 'wp', 'pmpro_testimonials_testimonial_check' );

function pmpro_testimonials_get_started() {
	?>
	<div class="pmpro_admin">
		<div class="pmpro-new-install">
			<h2><?php esc_html_e( 'Manage Testimonials', 'pmpro-testimonials' ); ?></h2>
			<h4><?php esc_html_e( 'Capture testimonials from your PMPro members for the entire site or for specific membership levels.', 'pmpro-testimonials' ); ?></h4>
			<a href="<?php echo esc_url_raw( admin_url( 'post-new.php?post_type=pmpro_testimonial' ) ); ?>" class="button-primary"><?php esc_html_e( 'Create a Testimonial', 'pmpro-testimonials' ); ?></a>
			<a href="https://www.paidmembershipspro.com/add-ons/testimonials/?utm_source=plugin&utm_medium=pmpro-testimonials-admin&utm_campaign=add-ons" class="button" target="_blank"><?php esc_html_e( 'Documentation: Testimonials Add On', 'pmpro-testimonials' ); ?></a>
		</div> <!-- end pmpro-new-install -->
	</div> <!-- end pmpro_admin -->
	<?php
}

/**
 * Unique placeholder for empty testimonial title.
 */
function pmpro_testimonials_enter_title_here( $title, $post ) {
	if ( 'pmpro_testimonial' === $post->post_type ) {
		return __( 'Enter name', 'pmpro-testimonials' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'pmpro_testimonials_enter_title_here', 10, 2 );

/**
 * Only allow paragraph blocks in the editor.
 */
function pmpro_testimonial_allowed_block_types( $allowed_blocks, $block_editor_context ) {
	// Check if we are editing the pmpro_testimonial post type
	if ( isset( $block_editor_context->post ) && $block_editor_context->post->post_type === 'pmpro_testimonial' ) {
		// Return only the core/paragraph block type
		return array(
			'core/paragraph',
		);
	}

	// Otherwise, do not modify the allowed blocks
	return $allowed_blocks;
}
add_filter( 'allowed_block_types_all', 'pmpro_testimonial_allowed_block_types', 10, 2 );

/**
 * Show number of pending testimonials in menu.
 */
function pmpro_testimonial_pending_count_bubble() {
	// Get the number of pending testimonials
	$count         = wp_count_posts( 'pmpro_testimonial' );
	$pending_count = $count->pending;

	// If there are pending testimonials, display the count.
	if ( $pending_count > 0 ) {
		// Find the existing menu item and append the count bubble.
		global $menu;
		foreach ( $menu as $key => $menu_item ) {
			if ( 'edit.php?post_type=pmpro_testimonial' === $menu_item[2] ) {
				$menu[ $key ][0] .= sprintf( ' <span class="awaiting-mod pending-count"><span class="pending-count-number">%d</span></span>', esc_html( $pending_count ) );
				break;
			}
		}
	}
}
add_action( 'admin_menu', 'pmpro_testimonial_pending_count_bubble' );
