<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
?>
<div <?php echo esc_attr( get_block_wrapper_attributes() ); ?>>
	<?php echo esc_html( $testimonial->get_image() ); ?>
</div>
