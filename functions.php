<?php
/**
 * BNH Core functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package BNH_Core
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bnh_core_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on BNH Core, use a find and replace
		* to change 'bnh-core' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'bnh-core', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'mainMenu'              => esc_html__( 'Main Menu', 'bnh-core' ),
			'footerHelpMenu'        => esc_html__( 'Footer Help Menu', 'bnh-core' ),
			'footerInformationMenu' => esc_html__( 'Footer Information Menu', 'bnh-core' ),
			'footerResearchMenu'    => esc_html__( 'Footer Research Menu', 'bnh-core' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
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

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'bnh_core_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'bnh_core_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bnh_core_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bnh_core_content_width', 640 );
}
add_action( 'after_setup_theme', 'bnh_core_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
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

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function bnh_core_scripts() {
	$theme_uri = get_template_directory_uri();
	$theme_dir = get_template_directory();

	wp_enqueue_style( 'bnh-core-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'bnh-core-style', 'rtl', 'replace' );
	wp_enqueue_style(
		'bnh-core-font-oxanium',
		'https://fonts.googleapis.com/css2?family=Oxanium:wght@200..800&display=swap',
		array(),
		null
	);

	$styles = array(
		'bnh-core-fonts'        => '/assets/css/bnh-core-fonts.css',
		'bnh-core-utilities'    => '/assets/css/utilities.css',
		'bnh-core-spacer'       => '/assets/css/spacer.css',
		'bnh-core-slick'        => '/assets/css/slick.css',
		'bnh-core-slick-custom' => '/assets/css/bnh-core-slick-custom.css',
		'bnh-core-form'         => '/assets/css/bnh-core-form.css',
		'bnh-core-video-popup'  => '/assets/css/video-popup.css',
		'bnh-core-video-ui'     => '/assets/css/video-behaviors.css',
		'bnh-core-theme'        => '/assets/css/bnh-core-theme.css',
	);

	foreach ( $styles as $handle => $relative_path ) {
		$file_path = $theme_dir . $relative_path;

		if ( ! file_exists( $file_path ) ) {
			continue;
		}

		wp_enqueue_style(
			$handle,
			$theme_uri . $relative_path,
			array( 'bnh-core-style', 'bnh-core-font-oxanium' ),
			(string) filemtime( $file_path )
		);
	}

	$temporary_styles = array(
		'bnh-core-faisal-dev' => '/faisal.css',
		'bnh-core-imran-dev'  => '/imran.css',
	);

	foreach ( $temporary_styles as $handle => $relative_path ) {
		$file_path = $theme_dir . $relative_path;

		if ( ! file_exists( $file_path ) ) {
			continue;
		}

		wp_enqueue_style(
			$handle,
			$theme_uri . $relative_path,
			array( 'bnh-core-style', 'bnh-core-font-oxanium' ),
			(string) filemtime( $file_path )
		);
	}

	$scripts = array(
		'bnh-core-slick'        => array(
			'path' => '/assets/js/slick.js',
			'deps' => array( 'jquery' ),
		),
		'bnh-core-vimeo-player' => array(
			'path' => '/assets/js/jquery.mb.vimeo_player.min.js',
			'deps' => array( 'jquery' ),
		),
		'bnh-core-hamburger'    => array(
			'path' => '/assets/js/hamburger-menu.js',
			'deps' => array( 'jquery' ),
		),
		'bnh-core-carousel'     => array(
			'path' => '/assets/js/bnh-core-carousels.js',
			'deps' => array( 'jquery', 'bnh-core-slick' ),
		),
		'bnh-core-scripts'      => array(
			'path' => '/assets/js/scripts.js',
			'deps' => array( 'jquery', 'bnh-core-slick' ),
		),
		'bnh-core-video-popup'  => array(
			'path' => '/assets/js/video-popup.js',
			'deps' => array( 'jquery' ),
		),
		'bnh-core-video-ui'     => array(
			'path' => '/assets/js/video-behaviors.js',
			'deps' => array( 'jquery', 'bnh-core-vimeo-player' ),
		),
		'bnh-core-topic-navigation' => array(
			'path' => '/assets/js/topic-navigation.js',
			'deps' => array(),
		),
	);

	foreach ( $scripts as $handle => $script_config ) {
		$file_path = $theme_dir . $script_config['path'];

		if ( ! file_exists( $file_path ) ) {
			continue;
		}

		wp_enqueue_script(
			$handle,
			$theme_uri . $script_config['path'],
			$script_config['deps'],
			(string) filemtime( $file_path ),
			true
		);
	}

	if ( wp_script_is( 'bnh-core-topic-navigation', 'enqueued' ) ) {
		wp_add_inline_script(
			'bnh-core-topic-navigation',
			'window.bnhCoreTopicNavigation = ' . wp_json_encode(
				array(
					'restUrl' => esc_url_raw( rest_url( 'bnh-core/v1/topic-content' ) ),
				)
			) . ';',
			'before'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'bnh_core_scripts' );

/**
 * Keep search and author results aligned with the card-grid layouts.
 *
 * @param WP_Query $query Query instance.
 * @return void
 */
function bnh_core_limit_archive_grid_results_per_page( $query ) {
	if ( is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_search() || $query->is_author() ) {
		$query->set( 'posts_per_page', 4 );
	}
}
add_action( 'pre_get_posts', 'bnh_core_limit_archive_grid_results_per_page' );

/**
 * Load theme helper files.
 *
 * Keep theme-only utilities here. Permanent routing, redirects, canonicals,
 * and content-model rules remain in the bnh-site-core plugin.
 *
 * @return void
 */
function bnh_core_load_theme_files() {
	$theme_files = array(
		get_template_directory() . '/inc/image-sizes.php',
	);

	foreach ( $theme_files as $theme_file ) {
		if ( file_exists( $theme_file ) ) {
			require_once $theme_file;
		}
	}

	$helper_files = glob( get_template_directory() . '/inc/helper-functions/*.php' );

	if ( empty( $helper_files ) ) {
		return;
	}

	sort( $helper_files );

	foreach ( $helper_files as $helper_file ) {
		if ( ! is_string( $helper_file ) ) {
			continue;
		}

		require_once $helper_file;
	}
}

bnh_core_load_theme_files();



/**
 * Disable the block editor for pages only.
 *
 * The current page workflow is based on classic editing plus ACF-driven
 * flexible content. Other post types keep the block editor enabled.
 *
 * @param bool   $use_block_editor Whether to use the block editor.
 * @param string $post_type        Current post type.
 * @return bool
 */
function bnh_core_disable_gutenberg_for_pages( $use_block_editor, $post_type ) {
	if ( 'page' === $post_type ) {
		return false;
	}

	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'bnh_core_disable_gutenberg_for_pages', 10, 2 );

/**
 * Disable the block widget editor and restore classic widgets.
 *
 * @return void
 */
function bnh_core_disable_block_widgets() {
	remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'bnh_core_disable_block_widgets' );

/**
 * Return frontend block-style handles that should be removed.
 *
 * @return string[]
 */
function bnh_core_get_blocked_style_handles() {
	return array(
		'wp-block-library',
		'wp-block-library-theme',
		'global-styles',
		'classic-theme-styles',
		'wc-blocks-style',
		'wc-blocks-style-active-filters',
		'wc-blocks-style-add-to-cart-form',
		'wc-blocks-style-all-products',
		'wc-blocks-style-all-reviews',
		'wc-blocks-style-attribute-filter',
		'wc-blocks-style-breadcrumbs',
		'wc-blocks-style-catalog-sorting',
		'wc-blocks-style-customer-account',
		'wc-blocks-style-featured-category',
		'wc-blocks-style-featured-product',
		'wc-blocks-style-mini-cart',
		'wc-blocks-style-price-filter',
		'wc-blocks-style-product-add-to-cart',
		'wc-blocks-style-product-button',
		'wc-blocks-style-product-categories',
		'wc-blocks-style-product-image',
		'wc-blocks-style-product-image-gallery',
		'wc-blocks-style-product-query',
		'wc-blocks-style-product-results-count',
		'wc-blocks-style-product-reviews',
		'wc-blocks-style-product-sale-badge',
		'wc-blocks-style-product-search',
		'wc-blocks-style-product-sku',
		'wc-blocks-style-product-stock-indicator',
		'wc-blocks-style-product-summary',
		'wc-blocks-style-product-title',
		'wc-blocks-style-rating-filter',
		'wc-blocks-style-reviews-by-category',
		'wc-blocks-style-reviews-by-product',
		'wc-blocks-style-product-details',
		'wc-blocks-style-single-product',
		'wc-blocks-style-stock-filter',
		'wc-blocks-style-cart',
		'wc-blocks-style-checkout',
		'wc-blocks-style-mini-cart-contents',
	);
}

/**
 * Remove block-library and WooCommerce block styles from the frontend.
 *
 * @return void
 */
function bnh_core_disable_block_library_css() {
	foreach ( bnh_core_get_blocked_style_handles() as $handle ) {
		wp_dequeue_style( $handle );
		wp_deregister_style( $handle );
	}

	$wp_styles = wp_styles();

	if ( ! $wp_styles instanceof WP_Styles ) {
		return;
	}

	foreach ( array_keys( $wp_styles->registered ) as $handle ) {
		if ( 0 === strpos( $handle, 'wc-blocks-style-' ) ) {
			wp_dequeue_style( $handle );
			wp_deregister_style( $handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'bnh_core_disable_block_library_css', 100 );
add_action( 'wp_print_styles', 'bnh_core_disable_block_library_css', 100 );

/**
 * Prevent WooCommerce block styles from printing if they still reach the tag stage.
 *
 * @param string $html   Generated style tag HTML.
 * @param string $handle Style handle.
 * @param string $href   Style URL.
 * @param string $media  Media target.
 * @return string
 */
function bnh_core_block_wc_blocks_style_tag( $html, $handle, $href, $media ) {
	unset( $href, $media );

	if ( 0 === strpos( $handle, 'wc-blocks-style' ) ) {
		return '';
	}

	return $html;
}
add_filter( 'style_loader_tag', 'bnh_core_block_wc_blocks_style_tag', 10, 4 );

/**
 * Remove block-library styles in the admin where they are not needed.
 *
 * @return void
 */
function bnh_core_disable_block_library_css_admin() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'admin_enqueue_scripts', 'bnh_core_disable_block_library_css_admin', 100 );
