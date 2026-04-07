<?php
/**
 * Health topic color helpers.
 *
 * Parent topics define the editorial color selection. Child topics inherit the
 * selected parent color automatically.
 *
 * @package BNH_Core
 */

/**
 * Return the parent topic term that owns the color value.
 *
 * @param WP_Term|null $term Topic term.
 * @return WP_Term|null
 */
function bnh_core_get_health_topic_color_source_term( $term ) {
	if ( ! ( $term instanceof WP_Term ) ) {
		return null;
	}

	if ( 0 === (int) $term->parent ) {
		return $term;
	}

	$parent_term = get_term( $term->parent, 'health_topic' );

	return $parent_term instanceof WP_Term ? $parent_term : null;
}

/**
 * Return the manually configured topic color hex value, if valid.
 *
 * @param WP_Term|null $term Topic term.
 * @return string Empty string when no valid manual color is stored.
 */
function bnh_core_get_health_topic_color_key( $term ) {
	$source_term = bnh_core_get_health_topic_color_source_term( $term );

	if ( ! ( $source_term instanceof WP_Term ) || ! function_exists( 'get_field' ) ) {
		return '';
	}

	$raw_value = get_field( 'topic_color', 'health_topic_' . $source_term->term_id );
	$hex_color = is_string( $raw_value ) ? sanitize_hex_color( trim( $raw_value ) ) : '';

	return is_string( $hex_color ) ? $hex_color : '';
}

/**
 * Return the final CSS color value for a health_topic term.
 *
 * Uses only the manually entered parent-term hex code.
 *
 * @param WP_Term|null $term Topic term.
 * @return string Empty string when no color can be resolved.
 */
function bnh_core_get_health_topic_color_value( $term ) {
	return bnh_core_get_health_topic_color_key( $term );
}
