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
?>

<section class="topic-hub">
	<?php if ( is_tax( 'health_topic' ) && $bnh_term instanceof WP_Term ) : ?>
		<header class="page-header">
			<p class="page-eyebrow">
				<?php echo esc_html( 0 === (int) $bnh_term->parent ? __( 'Parent Topic', 'bnh-core' ) : __( 'Child Topic', 'bnh-core' ) ); ?>
			</p>
			<h1 class="page-title"><?php echo esc_html( $bnh_term->name ); ?></h1>
			<?php if ( term_description( $bnh_term ) ) : ?>
				<div class="archive-description"><?php echo wp_kses_post( term_description( $bnh_term ) ); ?></div>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/sections/topic-featured-article', null, array( 'context' => $bnh_context ) ); ?>
	<?php get_template_part( 'template-parts/sections/topic-latest-articles', null, array( 'context' => $bnh_context ) ); ?>
	<?php get_template_part( 'template-parts/sections/topic-featured-research', null, array( 'context' => $bnh_context ) ); ?>
	<?php get_template_part( 'template-parts/sections/topic-community', null, array( 'context' => $bnh_context ) ); ?>
</section>
