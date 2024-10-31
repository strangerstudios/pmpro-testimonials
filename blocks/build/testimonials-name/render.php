<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_name() ) { ?>
<div <?php echo get_block_wrapper_attributes(); ?> itemprop="author" itemscope itemtype="http://schema.org/Person">
	<span itemprop="name"><?php echo $testimonial->get_name( $attributes['linkEnabled'] ); ?></span>
</div>
<?php } ?>
