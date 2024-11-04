<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_company() ) {
	?>
<div <?php echo get_block_wrapper_attributes(); ?> itemprop="affiliation" itemscope itemtype="http://schema.org/Organization">
	<span itemprop="name"><?php echo $testimonial->get_company( $attributes['linkEnabled'] ); ?></span>
</div>
<?php } ?>
