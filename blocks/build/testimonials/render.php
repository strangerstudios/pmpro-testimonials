<?php
$display      = new PMPro_Testimonial_Display( $attributes );
$testimonials = $display->get_testimonials();
// _log( $content, 'CONTENT' );
// _log( $block, 'BLOCK' );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php foreach ( $testimonials as $testimonial ) { ?>
		<div class="pmpro_testimonial">
			??
		</div>
	<?php } ?>
</div>
