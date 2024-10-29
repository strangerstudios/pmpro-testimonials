<?php
// Shortcode for displaying testimonials
function pmpro_testimonials_shortcode_display( $atts, $content = '' ) {
    // Set default values for categories, tags, etc.
    $atts = shortcode_atts(
        array(
            'testimonials' => '',  // ids of specific testimonials, comma-separated
            'style'       => '',  // design style to use
            'categories'   => '',  // category slugs, comma-separated
            'tags'         => '',  // tag slugs, comma-separated
            'limit'        => '',  // max testimonials to show
            'order'        => '',  // ASC/DESC
            'orderby'      => '',  // 'title', 'name', 'rand', 'date'
            'layout'      => '',  // Layout to use
			'elements'    => 'all',  // Elements to show, comma-separated
        ),
        $atts,
        'pmpro_testimonials_display'
    );

    $testimonials = new PMPro_Testimonial_Display( $atts );
	return $testimonials->display( false );
}
add_shortcode( 'pmpro_testimonials_display', 'pmpro_testimonials_shortcode_display' );

// Shortcode for displaying testimonials with sub shortcodes inside for custom layouts.
function pmpro_testimonials_display_custom( $atts, $content = '' ) {
    // Set default values for categories, tags, etc.
    $atts = shortcode_atts(
        array(
            'testimonials' => '',  // ids of specific testimonials, comma-separated
            'style'       => '',  // design style to use
            'categories'   => '',  // category slugs, comma-separated
            'tags'         => '',  // tag slugs, comma-separated
            'limit'        => '',  // max testimonials to show
            'order'        => '',  // ASC/DESC
            'orderby'      => '',  // 'title', 'name', 'rand', 'date'
            'layout'      => 'default',  // Layout to use
			'elements'    => 'all',  // Elements to show, comma-separated
        ),
        $atts,
        'pmpro_testimonials_display_custom'
    );

    $html = '';
    $display = new PMPro_Testimonial_Display( $atts );
    $testimonials = $display->get_testimonials();
    if ( ! empty( $testimonials ) ) {
        $html = '<div class="pmpro_testimonials">';
        foreach ( $testimonials as $testimonial ) {
            $html .= '<div class="pmpro_testimonial">';
            // Set the current testimonial in a global variable for sub-shortcodes to access
            $GLOBALS['current_pmpro_testimonial'] = $testimonial;
            $html .= do_shortcode( $content );
            unset( $GLOBALS['current_pmpro_testimonial'] );
            $html .= '</div>';
        }
        $html .= '</div>';
    }

    return $html;
}
add_shortcode( 'pmpro_testimonials_display_custom', 'pmpro_testimonials_display_custom' );

// Individual element shortcodes
function pmpro_testimonial_title_shortcode( $atts ) {
    $testimonial = $GLOBALS['current_pmpro_testimonial'];
    return '<span class="pmpro_testimonial__title">' . esc_html( $testimonial->get_title() ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_title', 'pmpro_testimonial_title_shortcode' );

function pmpro_testimonial_rating_shortcode( $atts ) {
    $testimonial = $GLOBALS['current_pmpro_testimonial'];
    return '<span class="pmpro_testimonial__rating">' . esc_html( $testimonial->get_rating() ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_rating', 'pmpro_testimonial_rating_shortcode' );

function pmpro_testimonial_star_rating_shortcode( $atts ) {
    $testimonial = $GLOBALS['current_pmpro_testimonial'];
    return '<span class="pmpro_testimonial__star_rating">' . $testimonial->get_stars() . '</span>';
}
add_shortcode( 'pmpro_testimonial_star_rating', 'pmpro_testimonial_star_rating_shortcode' );

function pmpro_testimonial_content_shortcode( $atts ) {
    $testimonial = $GLOBALS['current_pmpro_testimonial'];
    return '<div class="pmpro_testimonial__testimonial">' . $testimonial->get_testimonial() . '</div>';
}
add_shortcode( 'pmpro_testimonial_content', 'pmpro_testimonial_content_shortcode' );

function pmpro_testimonial_name_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'link' => 'false' ), $atts, 'pmpro_testimonial_name' );
    $testimonial = $GLOBALS['current_pmpro_testimonial'];
    $name = $testimonial->get_name();
    
    if ( $atts['link'] === 'true' ) {
        return $testimonial->get_url( true, true, $name );
    }
    return '<span class="pmpro_testimonial__name">' . esc_html( $name ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_name', 'pmpro_testimonial_name_shortcode' );

function pmpro_testimonial_job_title_shortcode( $atts ) {
    $testimonial = $GLOBALS['current_pmpro_testimonial'];
    return '<span class="pmpro_testimonial__job_title">' . esc_html( $testimonial->get_job_title() ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_job_title', 'pmpro_testimonial_job_title_shortcode' );

function pmpro_testimonial_company_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'link' => 'false' ), $atts, 'pmpro_testimonial_company' );
    $testimonial = $GLOBALS['current_pmpro_testimonial'];
    $company = $testimonial->get_company();
    
    if ( $atts['link'] === 'true' ) {
        return $testimonial->get_url( true, true, $company );
    }
    return '<span class="pmpro_testimonial__company">' . esc_html( $company ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_company', 'pmpro_testimonial_company_shortcode' );

function pmpro_testimonial_url_shortcode( $atts ) {
    $testimonial = $GLOBALS['current_pmpro_testimonial'];
    return '<span class="pmpro_testimonial__url">' . esc_url( $testimonial->get_url() ) . '</span>';
}
add_shortcode( 'pmpro_testimonial_url', 'pmpro_testimonial_url_shortcode' );
