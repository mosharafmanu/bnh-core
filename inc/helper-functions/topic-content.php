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
 * Prefer the manually selected featured article on the active child term.
 * Fall back to the most recent post assigned to the active child term.
 *
 * @param array<string,mixed> $context Topic context.
 * @return int
 */
function bnh_core_get_topic_featured_post_id( $context ) {
	$child_term = $context['active_child'] ?? null;

	if ( ! ( $child_term instanceof WP_Term ) ) {
		return 0;
	}

	if ( function_exists( 'get_field' ) ) {
		$manual_featured_post = get_field( 'featured_article', 'health_topic_' . $child_term->term_id );
		$manual_featured_id   = 0;

		if ( $manual_featured_post instanceof WP_Post ) {
			$manual_featured_id = (int) $manual_featured_post->ID;
		} elseif ( is_numeric( $manual_featured_post ) ) {
			$manual_featured_id = absint( $manual_featured_post );
		}

		if ( $manual_featured_id && 'publish' === get_post_status( $manual_featured_id ) && has_term( (int) $child_term->term_id, 'health_topic', $manual_featured_id ) ) {
			return $manual_featured_id;
		}
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
 * Return the featured research post ID for the active parent topic.
 *
 * @param array<string,mixed> $context Topic context.
 * @return int
 */
function bnh_core_get_topic_featured_research_post_id( $context ) {
	$parent_term = $context['active_parent'] ?? null;

	if ( ! ( $parent_term instanceof WP_Term ) || ! function_exists( 'get_field' ) ) {
		return 0;
	}

	$featured_research = get_field( 'featured_research', 'health_topic_' . $parent_term->term_id );
	$featured_id       = 0;

	if ( $featured_research instanceof WP_Post ) {
		$featured_id = (int) $featured_research->ID;
	} elseif ( is_numeric( $featured_research ) ) {
		$featured_id = absint( $featured_research );
	}

	if ( ! $featured_id || 'publish' !== get_post_status( $featured_id ) ) {
		return 0;
	}

	return $featured_id;
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
			'posts_per_page'      => 4,
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
 * Return the final topic-aware URL for a post when available.
 *
 * @param WP_Post|int|null $post Post object or ID.
 * @return string
 */
function bnh_core_get_topic_post_url( $post ) {
	$post_id = $post instanceof WP_Post ? (int) $post->ID : absint( $post );

	if ( ! $post_id ) {
		return '';
	}

	if ( function_exists( 'bnh_get_post_health_topic_permalink' ) ) {
		$topic_url = bnh_get_post_health_topic_permalink( $post_id );

		if ( is_string( $topic_url ) && '' !== $topic_url ) {
			return $topic_url;
		}
	}

	return get_permalink( $post_id );
}

/**
 * Return a trimmed excerpt for topic cards.
 *
 * @param WP_Post|int|null $post       Post object or ID.
 * @param int              $word_count Max word count.
 * @return string
 */
function bnh_core_get_topic_post_excerpt( $post, $word_count = 15 ) {
	$post_obj = $post instanceof WP_Post ? $post : get_post( $post );

	if ( ! ( $post_obj instanceof WP_Post ) ) {
		return '';
	}

	$excerpt = has_excerpt( $post_obj ) ? $post_obj->post_excerpt : $post_obj->post_content;
	$excerpt = wp_strip_all_tags( (string) $excerpt );

	return trim( wp_trim_words( $excerpt, $word_count, ' [...]' ) );
}

/**
 * Return the author display name for a topic card.
 *
 * @param WP_Post|int|null $post Post object or ID.
 * @return string
 */
function bnh_core_get_topic_post_author_name( $post ) {
	$post_obj = $post instanceof WP_Post ? $post : get_post( $post );

	if ( ! ( $post_obj instanceof WP_Post ) ) {
		return '';
	}

	return (string) get_the_author_meta( 'display_name', (int) $post_obj->post_author );
}

/**
 * Return the formatted publish date for a topic card.
 *
 * @param WP_Post|int|null $post Post object or ID.
 * @return string
 */
function bnh_core_get_topic_post_date( $post ) {
	$post_id = $post instanceof WP_Post ? (int) $post->ID : absint( $post );

	if ( ! $post_id ) {
		return '';
	}

	return (string) get_the_date( 'j M Y', $post_id );
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
