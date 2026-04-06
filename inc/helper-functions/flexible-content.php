<?php
/**
 * Flexible content helpers.
 *
 * Theme-side ACF rendering only. This file resolves flexible content layouts to
 * section templates and keeps the page template free of layout lookup logic.
 *
 * @package BNH_Core
 */

if ( ! function_exists( 'bnh_core_has_flexible_content' ) ) {
	/**
	 * Check whether a post has rows in the configured flexible content field.
	 *
	 * @param string   $field_name Flexible content field name.
	 * @param int|null $post_id    Optional post ID. Uses the current post if omitted.
	 * @return bool
	 */
	function bnh_core_has_flexible_content( $field_name = 'cms', $post_id = null ) {
		if ( ! function_exists( 'have_rows' ) ) {
			return false;
		}

		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		if ( empty( $post_id ) ) {
			return false;
		}

		return have_rows( $field_name, $post_id );
	}
}

if ( ! function_exists( 'bnh_core_render_flexible_content' ) ) {
	/**
	 * Render ACF flexible content sections.
	 *
	 * Missing section templates are skipped silently on the frontend. In debug
	 * mode, administrators get an HTML comment to speed up development.
	 *
	 * @param string   $field_name Flexible content field name.
	 * @param int|null $post_id    Optional post ID. Uses the current post if omitted.
	 * @return void
	 */
	function bnh_core_render_flexible_content( $field_name = 'cms', $post_id = null ) {
		if ( ! bnh_core_has_flexible_content( $field_name, $post_id ) ) {
			return;
		}

		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		while ( have_rows( $field_name, $post_id ) ) {
			the_row();

			$layout = get_row_layout();

			if ( empty( $layout ) ) {
				continue;
			}

			$template_slug = 'template-parts/sections/' . $layout . '/' . $layout;
			$template_file = locate_template( $template_slug . '.php' );

			if ( ! $template_file ) {
				$template_slug = 'template-parts/sections/' . $layout;
				$template_file = locate_template( $template_slug . '.php' );
			}

			if ( $template_file ) {
				get_template_part( $template_slug );
				continue;
			}

			if ( current_user_can( 'manage_options' ) && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				echo '<!-- Missing flexible content template: ' . esc_html( $template_slug ) . '.php -->';
			}
		}
	}
}

if ( ! function_exists( 'purple_surgical_flexible_content' ) ) {
	/**
	 * Legacy alias for older theme calls.
	 *
	 * @param string   $field_name Flexible content field name.
	 * @param int|null $post_id    Optional post ID.
	 * @return void
	 */
	function purple_surgical_flexible_content( $field_name = 'cms', $post_id = null ) {
		bnh_core_render_flexible_content( $field_name, $post_id );
	}
}
