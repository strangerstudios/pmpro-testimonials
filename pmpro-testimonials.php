<?php
/**
 * Plugin Name: Paid Memberships Pro - Testimonials
 * Plugin URI: https://www.paidmembershipspro.com/add-ons/testimonials/
 * Description: Collect, approve, and display member testimonials with review schema on the frontend of your membership site.
 * Version: 0.1
 * Author: Paid Memberships Pro
 * Author URI: https://www.paidmembershipspro.com
 * Text Domain: pmpro-testimonials
 * License: GPL-3.0
 */

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

define( 'PMPRO_TESTIMONIALS_VERSION', '0.1' );
define( 'PMPRO_TESTIMONIALS_DIR', dirname( __FILE__ ) );
define( 'PMPRO_TESTIMONIALS_BASENAME', plugin_basename( __FILE__ ) );
define( 'PMPRO_TESTIMONIALS_URL', plugin_dir_url( __FILE__ ) );

// Includes.
require_once PMPRO_TESTIMONIALS_DIR . '/includes/post-types/testimonials.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/classes/pmpro-testimonial-form.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/classes/pmpro-testimonial-display.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/classes/pmpro-testimonial.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/common.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/admin.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/settings.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/shortcodes/form.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/shortcodes/display.php';
require_once PMPRO_TESTIMONIALS_DIR . '/includes/hooks.php';

/**
 * Load the languages folder for translations.
 *
 * @return void
 */
function pmpro_testimonials_load_textdomain() {
	load_plugin_textdomain( 'pmpro-testimonials', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'pmpro_testimonials_load_textdomain' );

/**
 * Enqueue Admin Scripts and Styles
 */
function pmpro_testimonials_admin_enqueue( $hook ) {
	$screen = get_current_screen();
	if ( ( ! empty( $screen->post_type ) && $screen->post_type == 'pmpro_testimonial' ) ) {
		wp_enqueue_style( 'pmpro-testimonials-admin', plugins_url( 'css/admin.css', __FILE__ ), '', PMPRO_TESTIMONIALS_VERSION, 'screen' );
		pmpro_testimonials_enqueue_stars( 'form' );
	}
	if ( $screen->id == 'pmpro_testimonial_page_pmpro-testimonials-settings' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

	}
}
add_action( 'admin_enqueue_scripts', 'pmpro_testimonials_admin_enqueue' );

/**
 * Enqueue Frontend Scripts and Styles
 */
function pmpro_testimonials_frontend_enqueue() {
	global $post;

	// Return early if PMPro is not active.
	if ( ! defined( 'PMPRO_URL' ) ) {
		return;
	}

	if ( $post && has_shortcode( $post->post_content, 'pmpro_testimonials_form' ) ) {
		pmpro_testimonials_enqueue_stars();
		wp_enqueue_style( 'select2', PMPRO_URL . '/css/select2.min.css', '', '4.1.0-beta.0', 'screen' );
		wp_enqueue_script( 'select2', PMPRO_URL . '/js/select2.min.js', array( 'jquery' ), '4.1.0-beta.0' );
	}

	if ( $post && ( has_shortcode( $post->post_content, 'pmpro_testimonials_display' ) || has_shortcode( $post->post_content, 'pmpro_testimonials_display_custom' ) ) ) {
		pmpro_testimonials_enqueue_stars( 'display' );
		wp_enqueue_style( 'pmpro-testimonials-layouts', PMPRO_TESTIMONIALS_URL . 'css/layouts.css', '', PMPRO_TESTIMONIALS_VERSION, 'screen' );
	}
}
add_action( 'wp_enqueue_scripts', 'pmpro_testimonials_frontend_enqueue' );

function pmpro_testimonials_enqueue_stars( $method = 'form', $star_color = '' ) {
	wp_enqueue_style( 'pmpro-testimonials-stars', PMPRO_TESTIMONIALS_URL . 'css/stars-' . $method . '.css', '', PMPRO_TESTIMONIALS_VERSION, 'screen' );
	// Set custom star color CSS variable.
	if ( empty( $star_color ) ) {
		$star_color = empty( get_option( 'pmpro_testimonials_star_color' ) ) ? '#ffd700' : get_option( 'pmpro_testimonials_star_color' );
	}
	$star_css = ':root { --pmpro--testimonials--star: ' . esc_attr( $star_color ) . '; }';
	wp_add_inline_style( 'pmpro-testimonials-stars', $star_css );
	if ( $method === 'form' ) {
		wp_enqueue_script( 'pmpro-testimonials-scripts', PMPRO_TESTIMONIALS_URL . 'js/stars.js', array( 'jquery' ), PMPRO_TESTIMONIALS_VERSION );
	}
}
