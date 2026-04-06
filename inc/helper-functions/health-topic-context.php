<?php
/**
 * Health topic presentation context helpers.
 *
 * @package BNH_Core
 */

/**
 * Return ordered parent terms.
 *
 * @return WP_Term[]
 */
function bnh_get_health_topic_parent_terms() {
	if ( ! function_exists( 'bnh_get_allowed_health_topic_parent_slugs' ) ) {
		return array();
	}

	$ordered_slugs = bnh_get_allowed_health_topic_parent_slugs();
	$terms         = get_terms(
		array(
			'taxonomy'   => 'health_topic',
			'hide_empty' => false,
			'slug'       => $ordered_slugs,
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	$terms_by_slug = array();

	foreach ( $terms as $term ) {
		$terms_by_slug[ $term->slug ] = $term;
	}

	$ordered_terms = array();

	foreach ( $ordered_slugs as $slug ) {
		if ( isset( $terms_by_slug[ $slug ] ) ) {
			$ordered_terms[] = $terms_by_slug[ $slug ];
		}
	}

	return $ordered_terms;
}

/**
 * Return ordered child terms for a parent.
 *
 * @param int $parent_term_id Parent term ID.
 * @return WP_Term[]
 */
function bnh_get_health_topic_child_terms( $parent_term_id ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'health_topic',
			'hide_empty' => false,
			'parent'     => (int) $parent_term_id,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	return $terms;
}

/**
 * Check whether a child topic term has at least one published post.
 *
 * @param int $term_id Child term ID.
 * @return bool
 */
function bnh_core_health_topic_term_has_published_posts( $term_id ) {
	static $term_post_state = array();

	$term_id = (int) $term_id;

	if ( isset( $term_post_state[ $term_id ] ) ) {
		return $term_post_state[ $term_id ];
	}

	$query = new WP_Query(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'fields'                 => 'ids',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query'              => array(
				array(
					'taxonomy' => 'health_topic',
					'field'    => 'term_id',
					'terms'    => array( $term_id ),
				),
			),
		)
	);

	$term_post_state[ $term_id ] = $query->have_posts();

	return $term_post_state[ $term_id ];
}

/**
 * Return child terms for a parent that have at least one published post.
 *
 * @param int $parent_term_id Parent term ID.
 * @return WP_Term[]
 */
function bnh_core_get_visible_health_topic_child_terms( $parent_term_id ) {
	$child_terms = bnh_get_health_topic_child_terms( $parent_term_id );

	if ( empty( $child_terms ) ) {
		return array();
	}

	return array_values(
		array_filter(
			$child_terms,
			static function ( $term ) {
				return $term instanceof WP_Term && bnh_core_health_topic_term_has_published_posts( $term->term_id );
			}
		)
	);
}

/**
 * Return the first child term for a parent term.
 *
 * @param WP_Term|null $parent_term Parent topic term.
 * @return WP_Term|null
 */
function bnh_core_get_first_health_topic_child_term( $parent_term ) {
	if ( ! ( $parent_term instanceof WP_Term ) ) {
		return null;
	}

	$child_terms = bnh_core_get_visible_health_topic_child_terms( $parent_term->term_id );

	if ( empty( $child_terms ) ) {
		return null;
	}

	$first_child = reset( $child_terms );

	return $first_child instanceof WP_Term ? $first_child : null;
}

/**
 * Resolve a post child term under its active parent, if available.
 *
 * @param int          $post_id      Post ID.
 * @param WP_Term|null $parent_term  Parent topic term.
 * @return WP_Term|null
 */
function bnh_core_get_post_health_topic_child_term( $post_id, $parent_term ) {
	if ( ! ( $parent_term instanceof WP_Term ) ) {
		return null;
	}

	$terms = get_the_terms( $post_id, 'health_topic' );

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return null;
	}

	foreach ( $terms as $term ) {
		if ( ! $term instanceof WP_Term ) {
			continue;
		}

		if ( (int) $term->parent === (int) $parent_term->term_id ) {
			if ( ! bnh_core_health_topic_term_has_published_posts( $term->term_id ) ) {
				continue;
			}

			return $term;
		}
	}

	return null;
}

/**
 * Build active topic context for templates.
 *
 * @return array<string,mixed>
 */
function bnh_get_health_topic_context() {
	$context = array(
		'active_parent'    => null,
		'active_child'     => null,
		'parent_terms'     => bnh_get_health_topic_parent_terms(),
		'child_terms'      => array(),
		'is_parent_archive'=> false,
		'is_child_archive' => false,
		'is_topic_single'  => false,
		'paged'            => max( 1, absint( wp_unslash( $_GET['topic-page'] ?? 1 ) ) ),
	);

	if ( is_tax( 'health_topic' ) ) {
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			if ( 0 === (int) $term->parent ) {
				$context['active_parent']     = $term;
				$context['child_terms']       = bnh_core_get_visible_health_topic_child_terms( $term->term_id );
				$context['active_child']      = bnh_core_get_first_health_topic_child_term( $term );
				$context['is_parent_archive'] = true;
			} else {
				$context['active_child']     = $term;
				$context['active_parent']    = get_term( $term->parent, 'health_topic' );
				$context['is_child_archive'] = true;

				if ( $context['active_parent'] instanceof WP_Term ) {
					$context['child_terms'] = bnh_core_get_visible_health_topic_child_terms( $context['active_parent']->term_id );
				}
			}
		}
	} elseif ( is_singular( 'post' ) ) {
		$context['active_parent']   = function_exists( 'bnh_get_post_health_topic_parent_term' ) ? bnh_get_post_health_topic_parent_term( get_queried_object_id() ) : null;
		$context['is_topic_single'] = $context['active_parent'] instanceof WP_Term;

		if ( $context['active_parent'] instanceof WP_Term ) {
			$context['child_terms'] = bnh_core_get_visible_health_topic_child_terms( $context['active_parent']->term_id );
			$context['active_child'] = bnh_core_get_post_health_topic_child_term( get_queried_object_id(), $context['active_parent'] );
		}
	} elseif ( is_home() || is_front_page() || is_search() || is_author() ) {
		$context['active_parent'] = function_exists( 'bnh_get_health_topic_parent_term_by_slug' ) ? bnh_get_health_topic_parent_term_by_slug( 'prostate-health' ) : null;

		if ( $context['active_parent'] instanceof WP_Term ) {
			$context['child_terms'] = bnh_core_get_visible_health_topic_child_terms( $context['active_parent']->term_id );
			$context['active_child'] = bnh_core_get_first_health_topic_child_term( $context['active_parent'] );
		}
	}

	if ( ! ( $context['active_child'] instanceof WP_Term ) && $context['active_parent'] instanceof WP_Term ) {
		$context['active_child'] = bnh_core_get_first_health_topic_child_term( $context['active_parent'] );
	}

	return $context;
}
