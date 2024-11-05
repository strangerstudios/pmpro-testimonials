<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_rating() ) {
	?>
<div <?php echo esc_attr( get_block_wrapper_attributes( array( 'class' => 'pmpro_starsx' ) ) ); ?> itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
	<?php echo wp_kses( $testimonial->get_stars( $attributes['starColor'] ), pmpro_testimonials_allowed_star_html() ); ?>
	<meta itemprop="ratingValue" content="<?php echo esc_attr( $testimonial->get_rating() ); ?>" />
	<meta itemprop="bestRating" content="5" />
	<meta itemprop="worstRating" content="1" />
</div>
<?php } ?>
