<?php
/**
 * Theme assets.
 *
 * @package BNH_Core
 */

/**
 * Enqueue frontend assets.
 *
 * @return void
 */
function bnh_core_enqueue_assets() {
	wp_enqueue_style( 'bnh-core-style', get_stylesheet_uri(), array(), BNH_CORE_VERSION );
	wp_style_add_data( 'bnh-core-style', 'rtl', 'replace' );
}
add_action( 'wp_enqueue_scripts', 'bnh_core_enqueue_assets' );
