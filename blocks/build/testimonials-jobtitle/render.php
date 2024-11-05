<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_job_title() ) {
	?>
<div <?php echo esc_attr( get_block_wrapper_attributes() ); ?>>
	<?php echo esc_html( $testimonial->get_job_title() ); ?>
</div>
<?php } ?>
