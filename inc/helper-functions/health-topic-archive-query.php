<?php
/**
 * Health topic archive query adjustments.
 *
 * @package BNH_Core
 */

/**
 * Resolve the queried health_topic term from a main query.
 *
 * @param WP_Query $query Query instance.
 * @return WP_Term|null
 */
function bnh_core_get_health_topic_term_from_query( $query ) {
	$queried_object = get_queried_object();

	if ( $queried_object instanceof WP_Term && 'health_topic' === $queried_object->taxonomy ) {
		return $queried_object;
	}

	$term_id = absint( $query->get( 'term_id' ) );

	if ( $term_id ) {
		$term = get_term( $term_id, 'health_topic' );

		return $term instanceof WP_Term ? $term : null;
	}

	$term_slug = (string) $query->get( 'health_topic' );

	if ( '' === $term_slug ) {
		$term_slug = (string) $query->get( 'term' );
	}

	if ( '' === $term_slug ) {
		return null;
	}

	$term = get_term_by( 'slug', $term_slug, 'health_topic' );

	return $term instanceof WP_Term ? $term : null;
}

/**
 * Limit child health_topic archives to 8 posts per page.
 *
 * Parent topic archives keep the Topic Hub behavior and are intentionally not
 * affected by this query adjustment.
 *
 * @param WP_Query $query Query instance.
 * @return void
 */
function bnh_core_limit_child_health_topic_archives_per_page( $query ) {
	if ( is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() || ! $query->is_tax( 'health_topic' ) ) {
		return;
	}

	$term = bnh_core_get_health_topic_term_from_query( $query );

	if ( ! ( $term instanceof WP_Term ) || 0 === (int) $term->parent ) {
		return;
	}

	$posts_per_page = 8;

	$paged = absint( filter_input( INPUT_GET, 'topic-page', FILTER_SANITIZE_NUMBER_INT ) );

	$query->set( 'posts_per_page', $posts_per_page );

	if ( $paged > 1 ) {
		$query->set( 'offset', ( $paged - 1 ) * $posts_per_page );
	}
}
add_action( 'pre_get_posts', 'bnh_core_limit_child_health_topic_archives_per_page' );
