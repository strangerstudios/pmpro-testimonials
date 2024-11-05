<?php
$testimonial = new PMPro_Testimonial( get_the_ID() );
if ( $testimonial->get_company() ) {
	?>
<div <?php echo esc_attr( get_block_wrapper_attributes() ); ?> itemprop="affiliation" itemscope itemtype="http://schema.org/Organization">
	<span itemprop="name"><?php echo wp_kses_post( $testimonial->get_company( $attributes['linkEnabled'] ) ); ?></span>
</div>
<?php } ?>
