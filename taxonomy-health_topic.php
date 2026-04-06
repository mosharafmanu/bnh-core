<?php
/**
 * Taxonomy template for health_topic archives.
 *
 * @package BNH_Core
 */

get_header();

$bnh_context = function_exists( 'bnh_get_health_topic_context' ) ? bnh_get_health_topic_context() : array();
$bnh_context = function_exists( 'bnh_core_build_topic_content_context' ) ? bnh_core_build_topic_content_context( $bnh_context ) : $bnh_context;
?>

<main id="primary" class="site-main topic-content-page">
	<?php get_template_part( 'template-parts/sections/topic_hub/topic_hub', null, array( 'context' => $bnh_context ) ); ?>
</main>

<?php
get_footer();
