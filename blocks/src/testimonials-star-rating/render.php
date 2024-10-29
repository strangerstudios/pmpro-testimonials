<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_rating() ) {
	?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'pmpro_starsx' ) ); ?>>
	<?php echo $testimonial->get_stars( $attributes['starColor'] ); ?>
</div>
<?php } ?>
