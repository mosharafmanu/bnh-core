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
	);

	if ( is_tax( 'health_topic' ) ) {
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			if ( 0 === (int) $term->parent ) {
				$context['active_parent']     = $term;
				$context['child_terms']       = bnh_get_health_topic_child_terms( $term->term_id );
				$context['is_parent_archive'] = true;
			} else {
				$context['active_child']     = $term;
				$context['active_parent']    = get_term( $term->parent, 'health_topic' );
				$context['is_child_archive'] = true;

				if ( $context['active_parent'] instanceof WP_Term ) {
					$context['child_terms'] = bnh_get_health_topic_child_terms( $context['active_parent']->term_id );
				}
			}
		}
	} elseif ( is_singular( 'post' ) ) {
		$context['active_parent']   = bnh_get_post_health_topic_parent_term( get_queried_object_id() );
		$context['is_topic_single'] = $context['active_parent'] instanceof WP_Term;

		if ( $context['active_parent'] instanceof WP_Term ) {
			$context['child_terms'] = bnh_get_health_topic_child_terms( $context['active_parent']->term_id );
		}
	} elseif ( is_home() || is_front_page() ) {
		$context['active_parent'] = bnh_get_health_topic_parent_term_by_slug( 'prostate-health' );

		if ( $context['active_parent'] instanceof WP_Term ) {
			$context['child_terms'] = bnh_get_health_topic_child_terms( $context['active_parent']->term_id );
		}
	}

	return $context;
}
