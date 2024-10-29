<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_company() ) {
	?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php
	if ( $attributes['linkEnabled'] && $testimonial->get_url() ) {
		echo '<a href="' . $testimonial->get_url() . '">' . $testimonial->get_company() . '</a>';
	} else {
		echo $testimonial->get_company();
	}
	?>
</div>
<?php } ?>
