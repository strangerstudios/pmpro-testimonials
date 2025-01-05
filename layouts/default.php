
<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card' ) ); ?>" itemscope itemtype="http://schema.org/Review">
	<meta itemprop="datePublished" content="<?php echo esc_attr( $testimonial->get_date() ); ?>" />
	<meta itemprop="name" content="<?php echo esc_attr( $testimonial->get_review_schema_name() ); ?>" />
	<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card_content' ) ); ?>">
		<?php if ( $display->should_show( 'stars' ) ) :
			$stars = $testimonial->get_stars();
			if ( ! empty( $stars ) ) : ?>
				<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_testimonial__rating_stars' ) ); ?>" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
					<?php echo wp_kses_post( $testimonial->get_stars() ); ?>
					<meta itemprop="ratingValue" content="<?php echo esc_attr( $testimonial->get_rating() ); ?>" />
					<meta itemprop="bestRating" content="5" />
					<meta itemprop="worstRating" content="1" />
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $display->should_show( 'testimonial' ) ) : ?>
			<div itemprop="reviewBody"><?php echo wp_kses_post( $testimonial->get_testimonial() ); ?></div>
		<?php endif; ?>

		<?php
			/**
			 * Build the testimonial cite.
			 */
			$cite = array();

			if ( $display->should_show( 'image' ) ) :
				$cite['image'] = $testimonial->get_image();
			endif;

			if ( $display->should_show( 'name' ) ) :
				$cite['name'] = $testimonial->get_name();
			endif;

			if ( $display->should_show( 'job_title' ) ) :
				$cite['job_title'] = $testimonial->get_job_title();
			endif;

			if ( $display->should_show( 'company' ) ) :
				$cite['company'] = $testimonial->get_company();
			endif;

			if ( $display->should_show( 'url' ) ) :
				$cite['url'] = $testimonial->get_url_raw();
			endif;

			$cite_html = '<cite>';
			$cite_html = empty( $cite['url'] ) ? $cite_html : $cite_html . '<a href="' . $cite['url'] . '" target="_blank" rel="noopener noreferrer">';
			$cite_html = empty( $cite['name'] ) ? $cite_html : $cite_html . '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__name" ) ) . '" itemprop="name">' . $cite['name'] . '</span>';
			$cite_html = empty( $cite['job_title'] ) ? $cite_html : $cite_html . ', ' . '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__job_title" ) ) . '" itemprop="jobTitle">' . $cite['job_title'] . '</span>';
			$cite_html = empty( $cite['company'] ) ? $cite_html : $cite_html . ', ' . '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__company" ) ) . '" itemprop="affiliation" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">' . $cite['company'] . '</span></span>';
			$cite_html = empty( $cite['url'] ) ? $cite_html : $cite_html . '</a>';
			$cite_html = $cite_html . '</cite>';
			$cite_html = empty( $cite['image'] ) ? $cite_html : $cite_html . '<span class="' . esc_attr( pmpro_get_element_class( "pmpro_testimonial__image" ) ) . '">' . $cite['image'] . '</span>';

			/**
			 * Filter the testimonial cite.
			 *
			 * @since 1.0
			 *
			 * @param array $cite The testimonial cite.
			 * @param PMPro_Testimonial $testimonial The testimonial object.
			 */
			$cite_html = apply_filters( 'pmpro_testimonials_cite', '<footer class="' . pmpro_get_element_class( 'pmpro_testimonial__author' ) . '" itemprop="author" itemscope itemtype="http://schema.org/Person">' . $cite_html . '</footer>', $testimonial );

			// Allowed HTML tags.
			$allowed_html = array(
				'a' => array (
					'class' => array(),
					'href' => array(),
					'id' => array(),
					'rel' => array(),
					'target' => array(),
					'title' => array(),
				),
				'cite' => array(
					'class' => array(),
					'id' => array(),
				),
				'footer' => array(
					'class' => array(),
					'id' => array(),
					'itemprop' => array(),
					'itemscope' => array(),
					'itemtype' => array(),
				),
				'img' => array(
					'alt' => array(),
					'class' => array(),
					'decoding' => array(),
					'height' => array(),
					'src' => array(),
					'style' => array(),
					'width' => array(),
				),
				'span' => array(
					'class' => array(),
					'id' => array(),
					'itemprop' => array(),
					'itemscope' => array(),
					'itemtype' => array(),
				),
			);

			if ( ! empty( $cite_html ) ) :
				echo wp_kses( $cite_html, $allowed_html );
			endif;
		?>

	</div> <!-- .pmpro_card_content -->
</div> <!-- .pmpro_card -->
