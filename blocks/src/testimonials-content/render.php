<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
?>
<div <?php echo get_block_wrapper_attributes(); ?> itemprop="reviewBody">
	<?php echo $testimonial->get_testimonial(); ?>
</div>
