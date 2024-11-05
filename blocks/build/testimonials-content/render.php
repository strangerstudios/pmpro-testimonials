<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
?>
<div <?php echo esc_attr( get_block_wrapper_attributes() ); ?> itemprop="reviewBody">
	<?php echo wp_kses_post( $testimonial->get_testimonial() ); ?>
</div>
