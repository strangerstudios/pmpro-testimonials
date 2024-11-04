
<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card_content' ) ); ?>">

	<blockquote>

		<?php if ( $display->should_show( 'stars' ) ) :
			$stars = $testimonial->get_stars();
			if ( ! empty( $stars ) ) : ?>
				<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_testimonial__rating_stars' ) ); ?>"><?php echo $testimonial->get_stars(); ?></div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $display->should_show( 'testimonial' ) ) : ?>
			<?php echo $testimonial->get_testimonial(); ?>
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

			$cite_html = '<span class="' . pmpro_get_element_class( 'pmpro_testimonial__author' ) . '">';
			$cite_html = empty( $cite['url'] ) ? $cite_html : $cite_html . '<a href="' . $cite['url'] . '" target="_blank" rel="noopener noreferrer">';
			$cite_html = empty( $cite['name'] ) ? $cite_html : $cite_html . $cite['name'];
			$cite_html = empty( $cite['job_title'] ) ? $cite_html : $cite_html . ', ' . $cite['job_title'];
			$cite_html = empty( $cite['company'] ) ? $cite_html : $cite_html . ', '
			. $cite['company'];
			$cite_html = empty( $cite['url'] ) ? $cite_html : $cite_html . '</a>';
			$cite_html = $cite_html . '</span>';
			$cite_html = empty( $cite['image'] ) ? $cite_html : $cite_html . $cite['image'];

			/**
			 * Filter the testimonial cite.
			 *
			 * @since 1.0
			 *
			 * @param array $cite The testimonial cite.
			 * @param PMPro_Testimonial $testimonial The testimonial object.
			 */
			$cite_html = apply_filters( 'pmpro_testimonials_cite', '<cite>' . $cite_html . '</cite>', $testimonial );

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
				),
			);

			if ( ! empty( $cite_html ) ) :
				echo wp_kses( $cite_html, $allowed_html );
			endif;
		?>

</blockquote>

</div> <!-- .pmpro_card_content -->
