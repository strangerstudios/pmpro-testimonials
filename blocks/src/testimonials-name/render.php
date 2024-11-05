<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_name() ) { ?>
<div <?php echo esc_attr( get_block_wrapper_attributes() ); ?> itemprop="author" itemscope itemtype="http://schema.org/Person">
	<span itemprop="name"><?php echo wp_kses_post( $testimonial->get_name( $attributes['linkEnabled'] ) ); ?></span>
</div>
<?php } ?>
