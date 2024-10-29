<?php
function pmpro_testimonials_get_star( $status = '', $fill_color = 'none', $atts = array() ) {
	$classes = array( 'pmpro_star' );
	$fill    = '';
	if ( $status == 'filled' ) {
		$classes[] = 'filled';
		$style     = 'fill: ' . esc_attr( $fill_color ) . '; stroke: ' . esc_attr( $fill_color ) . ';';
	} else {
		$style = 'fill:none;stroke:#CCC;';
	}
	$attributes = '';
	if ( ! empty( $atts ) && is_array( $atts ) ) {
		foreach ( $atts as $key => $value ) {
			$attributes .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}
	$svg = '<svg style="' . $style . '" ' . $attributes . ' class="' . esc_attr( join( ' ', $classes ) ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
				<polygon points="12 17.27 18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27" />
			</svg>';
	return $svg;
}
