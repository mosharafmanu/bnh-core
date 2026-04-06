<?php
/**
 * Theme setup.
 *
 * @package BNH_Core
 */

/**
 * Register theme supports.
 *
 * @return void
 */
function bnh_core_setup() {
	load_theme_textdomain( 'bnh-core', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Navigation', 'bnh-core' ),
			'footer'  => esc_html__( 'Footer Navigation', 'bnh-core' ),
		)
	);
}
add_action( 'after_setup_theme', 'bnh_core_setup' );

/**
 * Set the content width.
 *
 * @return void
 */
function bnh_core_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bnh_core_content_width', 960 );
}
add_action( 'after_setup_theme', 'bnh_core_content_width', 0 );

/**
 * Register widget areas.
 *
 * @return void
 */
function bnh_core_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'bnh-core' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'bnh-core' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'bnh_core_widgets_init' );
