<?php
/**
 * Topic content helpers.
 *
 * @package BNH_Core
 */

/**
 * Build the shared topic content context.
 *
 * @param array<string,mixed> $context Base context array.
 * @param int|null            $paged   Optional current page.
 * @return array<string,mixed>
 */
function bnh_core_build_topic_content_context( $context, $paged = null ) {
	$topic_context = is_array( $context ) ? $context : array();
	$topic_context['paged'] = max( 1, absint( null === $paged ? ( $topic_context['paged'] ?? 1 ) : $paged ) );

	return $topic_context;
}

/**
 * Return the featured topic post ID.
 *
 * For this phase, the featured article is the most recent post assigned to the
 * active child term.
 *
 * @param array<string,mixed> $context Topic context.
 * @return int
 */
function bnh_core_get_topic_featured_post_id( $context ) {
	$child_term = $context['active_child'] ?? null;

	if ( ! ( $child_term instanceof WP_Term ) ) {
		return 0;
	}

	$query = new WP_Query(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query'              => array(
				array(
					'taxonomy' => 'health_topic',
					'field'    => 'term_id',
					'terms'    => array( (int) $child_term->term_id ),
				),
			),
		)
	);

	if ( ! $query->have_posts() ) {
		return 0;
	}

	return (int) $query->posts[0]->ID;
}

/**
 * Return the latest topic query.
 *
 * The latest articles list excludes the featured article to avoid duplication.
 *
 * @param array<string,mixed> $context Topic context.
 * @return WP_Query|null
 */
function bnh_core_get_topic_latest_articles_query( $context ) {
	$child_term = $context['active_child'] ?? null;

	if ( ! ( $child_term instanceof WP_Term ) ) {
		return null;
	}

	$featured_post_id = bnh_core_get_topic_featured_post_id( $context );
	$paged            = max( 1, absint( $context['paged'] ?? 1 ) );

	return new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 6,
			'paged'               => $paged,
			'ignore_sticky_posts' => true,
			'post__not_in'        => $featured_post_id ? array( $featured_post_id ) : array(),
			'tax_query'           => array(
				array(
					'taxonomy' => 'health_topic',
					'field'    => 'term_id',
					'terms'    => array( (int) $child_term->term_id ),
				),
			),
		)
	);
}

/**
 * Render a template part and return the HTML.
 *
 * @param string               $slug Template slug.
 * @param array<string,mixed>  $args Template arguments.
 * @return string
 */
function bnh_core_get_template_part_html( $slug, $args = array() ) {
	ob_start();
	get_template_part( $slug, null, $args );
	return (string) ob_get_clean();
}
