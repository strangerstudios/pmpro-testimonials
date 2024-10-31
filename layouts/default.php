<article class="pmpro_card" itemscope itemtype="http://schema.org/Review">
	<div class="pmpro_card_content pmpro_testimonial__main">
		<?php if ( $display->should_show( 'stars' ) ) : ?>
			<div class="pmpro_testimonial__rating_stars" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
				<?php echo $testimonial->get_stars(); ?>
				<meta itemprop="ratingValue" content="<?php echo esc_attr( $testimonial->get_rating() ); ?>" />
				<meta itemprop="bestRating" content="5" />
				<meta itemprop="worstRating" content="1" />
			</div>
		<?php endif; ?>
		<?php if ( $display->should_show( 'testimonial' ) ) : ?>
			<blockquote class="pmpro_testimonial__testimonial" itemprop="reviewBody"><?php echo $testimonial->get_testimonial(); ?></blockquote>
		<?php endif; ?>
	</div>
	<footer class="pmpro_card_actions pmpro_testimonial__meta" itemprop="author" itemscope itemtype="http://schema.org/Person">
		<?php if ( $display->should_show( 'image' ) ) : ?>
			<div class="pmpro_testimonial__image"><?php echo $testimonial->get_image(); ?></div>
		<?php endif; ?>
		<?php if ( $display->should_show( 'name' ) ) : ?>
			<div class="pmpro_testimonial__name" itemprop="name"><?php echo $testimonial->get_name(); ?></div>
		<?php endif; ?>
		<?php if ( $display->should_show( 'job_title' ) ) : ?>
			<div class="pmpro_testimonial__job_title" itemprop="jobTitle"><?php echo $testimonial->get_job_title(); ?></div>
		<?php endif; ?>
		<?php if ( $display->should_show( 'company' ) ) : ?>
			<div class="pmpro_testimonial__company" itemprop="affiliation" itemscope itemtype="http://schema.org/Organization">
				<span itemprop="name">
					<?php echo $testimonial->get_company( true ); ?>
				</span>
			</div>
		<?php endif; ?>
	</footer>
</article>
