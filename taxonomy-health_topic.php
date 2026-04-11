<?php
/**
 * Taxonomy template for health_topic archives.
 *
 * @package BNH_Core
 */

get_header();

$bnh_context = function_exists( 'bnh_get_health_topic_context' ) ? bnh_get_health_topic_context() : array();
$bnh_context = function_exists( 'bnh_core_build_topic_content_context' ) ? bnh_core_build_topic_content_context( $bnh_context ) : $bnh_context;
$bnh_term    = get_queried_object();

$bnh_front_page_id = 0;

if ( 'page' === get_option( 'show_on_front' ) ) {
	$bnh_front_page_id = (int) get_option( 'page_on_front' );
}
?>

<main id="primary" class="site-main topic-content-page">
	<?php get_template_part( 'template-parts/sections/topic_hub/topic_hub', null, array( 'context' => $bnh_context ) ); ?>

	<?php
	if (
		$bnh_term instanceof WP_Term &&
		'health_topic' === $bnh_term->taxonomy &&
		0 === (int) $bnh_term->parent &&
		$bnh_front_page_id > 0 &&
		function_exists( 'bnh_core_has_flexible_content' ) &&
		function_exists( 'bnh_core_render_flexible_content_excluding_layouts' ) &&
		bnh_core_has_flexible_content( 'cms', $bnh_front_page_id )
	) :
		bnh_core_render_flexible_content_excluding_layouts( 'cms', $bnh_front_page_id, array( 'topic_hub' ) );
	endif;
	?>
</main>

<?php
get_footer();
