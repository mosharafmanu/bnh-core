<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BNH_Core
 */
$bnh_context = function_exists( 'bnh_get_health_topic_context' ) ? bnh_get_health_topic_context() : array();

if ( ! function_exists( 'bnh_core_render_header_inline_icon' ) ) {
	/**
	 * Render a simple inline header icon.
	 *
	 * @param string $icon Icon name.
	 * @return string
	 */
	function bnh_core_render_header_inline_icon( $icon ) {
		$icons = array(
			'search'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="2"></circle><path d="M16 16L21 21" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"></path></svg>',
		);

		return $icons[ $icon ] ?? '';
	}
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'bnh-core' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-header__main layout-padding">
			<div class="site-header__menu">
				<nav class="main-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'bnh-core' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'mainMenu',
							'container'      => false,
							'menu_class'     => 'main-navigation__menu',
							'fallback_cb'    => false,
							'depth'          => 2,
						)
					);
					?>
				</nav>
			</div>

			<div class="site-branding">
				<?php if ( function_exists( 'bnh_core_render_site_logo' ) ) : ?>
					<?php bnh_core_render_site_logo( array( 'class' => 'site-logo-image', 'link_class' => 'site-logo-link' ) ); ?>
				<?php else : ?>
					<a class="site-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
			</div><!-- .site-branding -->

			<div class="site-header__actions">
				<form role="search" method="get" class="site-header__search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="sr-only" for="site-header-search"><?php esc_html_e( 'Search for:', 'bnh-core' ); ?></label>
					<input id="site-header-search" class="site-header__search-input" type="search" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
					<button class="site-header__search-button" type="submit" aria-label="<?php esc_attr_e( 'Search', 'bnh-core' ); ?>">
						<?php echo bnh_core_render_header_inline_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				</form>

				<div class="site-header__icon-links">
					<?php
					if ( function_exists( 'bnh_core_render_header_account_button' ) ) {
						bnh_core_render_header_account_button(
							array(
								'class'   => 'header-action-link header-action-link--account',
								'content' => '',
							)
						);
					}

					if ( function_exists( 'bnh_core_render_header_cart_button' ) ) {
						bnh_core_render_header_cart_button(
							array(
								'class'   => 'header-action-link header-action-link--cart',
								'content' => '',
							)
						);
					}
					?>
				</div>
			</div>
		</div>

		<div class="site-header__topic-inner">
			<div class="site-topic-navigation">
				<?php get_template_part( 'template-parts/sections/topic', 'parent-nav', array( 'context' => $bnh_context ) ); ?>
				<?php if ( ! is_search() && ! is_author() ) : ?>
					<?php get_template_part( 'template-parts/sections/topic', 'child-nav', array( 'context' => $bnh_context ) ); ?>
				<?php endif; ?>
			</div>
		</div>
		
	</header><!-- #masthead -->
	<div id="content" class="site-content">
