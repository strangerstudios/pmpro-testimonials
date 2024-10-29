<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_job_title() ) {
	?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo $testimonial->get_job_title(); ?>
</div>
<?php } ?>
