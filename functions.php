<?php
/**
 * Theme-only bootstrap for BNH Core.
 *
 * Permanent site architecture is loaded by the bnh-site-core plugin.
 *
 * @package BNH_Core
 */

if ( ! defined( 'BNH_CORE_VERSION' ) ) {
	define( 'BNH_CORE_VERSION', '1.0.0' );
}

$bnh_core_theme_setup = get_template_directory() . '/inc/theme/setup.php';
if ( file_exists( $bnh_core_theme_setup ) ) {
	require_once $bnh_core_theme_setup;
}

$bnh_core_theme_assets = get_template_directory() . '/inc/theme/assets.php';
if ( file_exists( $bnh_core_theme_assets ) ) {
	require_once $bnh_core_theme_assets;
}

$bnh_core_template_tags = get_template_directory() . '/inc/theme/template-tags.php';
if ( file_exists( $bnh_core_template_tags ) ) {
	require_once $bnh_core_template_tags;
}

$bnh_core_health_topic_context = get_template_directory() . '/inc/theme/health-topic-context.php';
if ( file_exists( $bnh_core_health_topic_context ) ) {
	require_once $bnh_core_health_topic_context;
}
