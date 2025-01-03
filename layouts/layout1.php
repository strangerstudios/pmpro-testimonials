<?php if ( $display->should_show( 'stars' ) ) : ?>
	<div class="pmpro_testimonial__rating_stars">Stars: <?php echo wp_kses_post( $testimonial->get_stars() ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'rating' ) ) : ?>
	<div class="pmpro_testimonial__rating">Rating: <?php echo esc_html( $testimonial->get_rating() ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'testimonial' ) ) : ?>
	<div class="pmpro_testimonial__testimonial">Testimonial: <?php echo wp_kses_post( $testimonial->get_testimonial() ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'name' ) ) : ?>
	<div class="pmpro_testimonial__name">Name: <?php echo esc_html( $testimonial->get_name() ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'job_title' ) ) : ?>
	<div class="pmpro_testimonial__job_title">Job Title: <?php echo esc_html( $testimonial->get_job_title() ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'company' ) ) : ?>
	<div class="pmpro_testimonial__company">Company: <?php echo esc_html( $testimonial->get_company() ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'url' ) ) : ?>
	<div class="pmpro_testimonial__url">URL: <?php echo esc_url( $testimonial->get_url() ); ?> / <?php echo $testimonial->get_url( true, true, 'Visit website' ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'categories' ) ) : ?>
	<div class="pmpro_testimonial__categories">Cats: <?php echo esc_html( $testimonial->get_categories() ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'tags' ) ) : ?>
	<div class="pmpro_testimonial__tags">Tags: <?php echo esc_html( $testimonial->get_tags() ); ?></div>
<?php endif; ?>
<?php if ( $display->should_show( 'image' ) ) : ?>
	<div class="pmpro_testimonial__image">Image: <?php echo wp_kses_post( $testimonial->get_image() ); ?></div>
<?php endif; ?>
