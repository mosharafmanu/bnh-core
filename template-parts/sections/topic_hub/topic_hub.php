<?php
/**
 * Topic hub flexible content section.
 *
 * Reuses the shared topic partials so homepage and taxonomy archives stay
 * aligned without duplicating the topic hub rendering logic.
 *
 * @package BNH_Core
 */

$bnh_context = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();

if ( empty( $bnh_context ) && function_exists( 'bnh_get_health_topic_context' ) ) {
	$bnh_context = bnh_get_health_topic_context();
}

if ( function_exists( 'bnh_core_build_topic_content_context' ) ) {
	$bnh_context = bnh_core_build_topic_content_context( $bnh_context );
}

$bnh_term = get_queried_object();
$bnh_page = get_queried_object();
$bnh_home_heading = '';

if ( ! empty( $bnh_context['active_parent'] ) && $bnh_context['active_parent'] instanceof WP_Term ) {
	$bnh_home_heading = $bnh_context['active_parent']->name;
}
?>

<section class="topic-hub mt-50 layout-padding">
	<?php if ( is_tax( 'health_topic' ) && $bnh_term instanceof WP_Term ) : ?>
		<h1 class="page-title sr-only"><?php echo esc_html( $bnh_term->name ); ?></h1>
		<?php if ( term_description( $bnh_term ) ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( term_description( $bnh_term ) ); ?></div>
		<?php endif; ?>
	<?php elseif ( ( is_front_page() || is_home() ) && $bnh_page instanceof WP_Post && '' !== $bnh_home_heading ) : ?>
		<h1 class="page-title sr-only"><?php echo esc_html( $bnh_home_heading ); ?></h1>
	<?php endif; ?>

	<div class="topic-hub__featured-row">
		<?php get_template_part( 'template-parts/sections/topic-featured-article', null, array( 'context' => $bnh_context ) ); ?>
		<?php get_template_part( 'template-parts/sections/topic-featured-research', null, array( 'context' => $bnh_context ) ); ?>
	</div>

	<div class="topic-hub__latest-row">
		<?php get_template_part( 'template-parts/sections/topic-latest-articles', null, array( 'context' => $bnh_context ) ); ?>
	</div>

	<div class="topic-hub__community-row">
		<?php get_template_part( 'template-parts/sections/topic-community', null, array( 'context' => $bnh_context ) ); ?>
	</div>
</section>
