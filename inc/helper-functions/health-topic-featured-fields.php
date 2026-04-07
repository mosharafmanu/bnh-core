<?php
/**
 * Health topic featured-content field visibility helpers.
 *
 * Keeps the taxonomy term editing UI focused by only showing the relevant
 * featured-content field for parent vs child health_topic terms.
 *
 * @package BNH_Core
 */

/**
 * Return whether the current admin request is for the health_topic taxonomy.
 *
 * @return bool
 */
function bnh_core_is_health_topic_taxonomy_admin() {
	if ( ! is_admin() ) {
		return false;
	}

	$taxonomy = isset( $_GET['taxonomy'] ) ? sanitize_key( wp_unslash( $_GET['taxonomy'] ) ) : '';

	if ( 'health_topic' === $taxonomy ) {
		return true;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	return $screen && isset( $screen->taxonomy ) && 'health_topic' === $screen->taxonomy;
}

/**
 * Return the current term mode for health_topic admin screens.
 *
 * @return string One of: parent, child, add, unknown.
 */
function bnh_core_get_health_topic_term_admin_mode() {
	if ( ! bnh_core_is_health_topic_taxonomy_admin() ) {
		return 'unknown';
	}

	$term_id = isset( $_GET['tag_ID'] ) ? absint( $_GET['tag_ID'] ) : 0;

	if ( ! $term_id ) {
		return 'add';
	}

	$term = get_term( $term_id, 'health_topic' );

	if ( ! ( $term instanceof WP_Term ) ) {
		return 'unknown';
	}

	return $term->parent ? 'child' : 'parent';
}

/**
 * Resolve a health_topic term object from an ACF post_id value.
 *
 * @param mixed $post_id ACF post ID context.
 * @return WP_Term|null
 */
function bnh_core_get_health_topic_term_from_post_id( $post_id ) {
	if ( ! is_string( $post_id ) && ! is_numeric( $post_id ) ) {
		return null;
	}

	$post_id = (string) $post_id;

	if ( ! preg_match( '/(?:health_topic_|term_)(\d+)/', $post_id, $matches ) ) {
		return null;
	}

	$term_id = absint( $matches[1] ?? 0 );

	if ( ! $term_id ) {
		return null;
	}

	$term = get_term( $term_id, 'health_topic' );

	return $term instanceof WP_Term ? $term : null;
}

/**
 * Return the current health_topic term object from the admin request.
 *
 * @param mixed $post_id Optional ACF post ID context.
 * @return WP_Term|null
 */
function bnh_core_get_current_health_topic_admin_term( $post_id = null ) {
	$term = bnh_core_get_health_topic_term_from_post_id( $post_id );

	if ( $term instanceof WP_Term ) {
		return $term;
	}

	if ( isset( $_POST['post_id'] ) ) {
		$term = bnh_core_get_health_topic_term_from_post_id( wp_unslash( $_POST['post_id'] ) );

		if ( $term instanceof WP_Term ) {
			return $term;
		}
	}

	if ( ! bnh_core_is_health_topic_taxonomy_admin() ) {
		return null;
	}

	$term_id = isset( $_GET['tag_ID'] ) ? absint( wp_unslash( $_GET['tag_ID'] ) ) : 0;

	if ( ! $term_id ) {
		return null;
	}

	$term = get_term( $term_id, 'health_topic' );

	return $term instanceof WP_Term ? $term : null;
}

/**
 * Hide the child featured article field on parent term edit screens.
 *
 * @param array<string,mixed> $field ACF field config.
 * @return array<string,mixed>|false
 */
function bnh_core_prepare_health_topic_featured_article_field( $field ) {
	if ( 'parent' === bnh_core_get_health_topic_term_admin_mode() ) {
		return false;
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=featured_article', 'bnh_core_prepare_health_topic_featured_article_field' );

/**
 * Hide the parent featured research field on child term edit screens.
 *
 * @param array<string,mixed> $field ACF field config.
 * @return array<string,mixed>|false
 */
function bnh_core_prepare_health_topic_featured_research_field( $field ) {
	if ( 'child' === bnh_core_get_health_topic_term_admin_mode() ) {
		return false;
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=featured_research', 'bnh_core_prepare_health_topic_featured_research_field' );

/**
 * Hide the parent topic color field on child term edit screens.
 *
 * @param array<string,mixed> $field ACF field config.
 * @return array<string,mixed>|false
 */
function bnh_core_prepare_health_topic_color_field( $field ) {
	if ( 'child' === bnh_core_get_health_topic_term_admin_mode() ) {
		return false;
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=topic_color', 'bnh_core_prepare_health_topic_color_field' );

/**
 * Filter the Featured Article selector to posts from the active child term.
 *
 * @param array<string,mixed> $args    WP_Query arguments.
 * @param array<string,mixed> $field   ACF field config.
 * @param mixed               $post_id ACF post ID context.
 * @return array<string,mixed>
 */
function bnh_core_filter_featured_article_field_query( $args, $field, $post_id ) {
	$term = bnh_core_get_current_health_topic_admin_term( $post_id );

	if ( ! ( $term instanceof WP_Term ) || ! $term->parent ) {
		return $args;
	}

	$args['post_status'] = array( 'publish' );
	$args['tax_query']   = array(
		array(
			'taxonomy'         => 'health_topic',
			'field'            => 'term_id',
			'terms'            => array( (int) $term->term_id ),
			'include_children' => false,
		),
	);

	return $args;
}
add_filter( 'acf/fields/post_object/query/name=featured_article', 'bnh_core_filter_featured_article_field_query', 10, 3 );

/**
 * Filter the Featured Research selector to posts from the active parent term tree.
 *
 * @param array<string,mixed> $args    WP_Query arguments.
 * @param array<string,mixed> $field   ACF field config.
 * @param mixed               $post_id ACF post ID context.
 * @return array<string,mixed>
 */
function bnh_core_filter_featured_research_field_query( $args, $field, $post_id ) {
	$term = bnh_core_get_current_health_topic_admin_term( $post_id );

	if ( ! ( $term instanceof WP_Term ) || $term->parent ) {
		return $args;
	}

	$args['post_status'] = array( 'publish' );
	$args['tax_query']   = array(
		array(
			'taxonomy'         => 'health_topic',
			'field'            => 'term_id',
			'terms'            => array( (int) $term->term_id ),
			'include_children' => true,
		),
	);

	return $args;
}
add_filter( 'acf/fields/post_object/query/name=featured_research', 'bnh_core_filter_featured_research_field_query', 10, 3 );

/**
 * Toggle the relevant featured-content field on taxonomy add/edit screens.
 *
 * The add form needs client-side switching because the parent/child state is
 * chosen through the taxonomy parent dropdown before the term exists.
 *
 * @return void
 */
function bnh_core_output_health_topic_featured_field_toggle_script() {
	if ( ! bnh_core_is_health_topic_taxonomy_admin() ) {
		return;
	}
	?>
	<script>
	(function() {
		var parentField = document.getElementById('parent');
		var articleField = document.querySelector('.acf-field[data-name="featured_article"]');
		var researchField = document.querySelector('.acf-field[data-name="featured_research"]');
		var colorField = document.querySelector('.acf-field[data-name="topic_color"]');

		if (!articleField && !researchField && !colorField) {
			return;
		}

		function toggleFeaturedFields() {
			var isChildTerm = parentField && parseInt(parentField.value || '0', 10) > 0;

			if (articleField) {
				articleField.style.display = isChildTerm ? '' : 'none';
			}

			if (researchField) {
				researchField.style.display = isChildTerm ? 'none' : '';
			}

			if (colorField) {
				colorField.style.display = isChildTerm ? 'none' : '';
			}
		}

		if (parentField) {
			parentField.addEventListener('change', toggleFeaturedFields);
		}

		toggleFeaturedFields();
	})();
	</script>
	<?php
}
add_action( 'admin_footer-edit-tags.php', 'bnh_core_output_health_topic_featured_field_toggle_script' );
add_action( 'admin_footer-term.php', 'bnh_core_output_health_topic_featured_field_toggle_script' );
