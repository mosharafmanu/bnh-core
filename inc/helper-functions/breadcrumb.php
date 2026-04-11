<?php
/**
 * Breadcrumb Navigation
 *
 * @package BNH_Core
 */

if ( ! function_exists( 'bnh_core_breadcrumb_get_classes' ) ) {
	/**
	 * Build breadcrumb wrapper classes.
	 *
	 * @param bool   $layout_padding Add layout padding class.
	 * @param string $margin_top Margin top utility class.
	 * @param string $margin_bottom Margin bottom utility class.
	 * @return string[]
	 */
	function bnh_core_breadcrumb_get_classes( $layout_padding = false, $margin_top = 'mt-30', $margin_bottom = '' ) {
		$classes = array( 'bhn-breadcrumb' );

		if ( $layout_padding ) {
			$classes[] = 'layout-padding';
		}

		if ( ! empty( $margin_top ) ) {
			$classes[] = $margin_top;
		}

		if ( ! empty( $margin_bottom ) ) {
			$classes[] = $margin_bottom;
		}

		return $classes;
	}
}

if ( ! function_exists( 'bnh_core_breadcrumb_item' ) ) {
	/**
	 * Render a breadcrumb item.
	 *
	 * @param string $label Item label.
	 * @param string $url Optional URL.
	 * @param bool   $current Whether item is current page.
	 * @return string
	 */
	function bnh_core_breadcrumb_item( $label, $url = '', $current = false ) {
		$label = (string) $label;

		if ( '' === $label ) {
			return '';
		}

		if ( $current || '' === $url ) {
			return '<span class="current" aria-current="page">' . esc_html( $label ) . '</span>';
		}

		return '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
	}
}

if ( ! function_exists( 'bnh_core_breadcrumb_separator' ) ) {
	/**
	 * Render breadcrumb separator markup.
	 *
	 * @return string
	 */
	function bnh_core_breadcrumb_separator() {
		$icon_path = get_template_directory() . '/assets/svgs/angle-right.php';
		$icon      = '';

		if ( file_exists( $icon_path ) ) {
			ob_start();
			include $icon_path;
			$icon = (string) ob_get_clean();
		}

		if ( '' === $icon ) {
			return '<span class="breadcrumb-separator" aria-hidden="true">/</span>';
		}

		return '<span class="breadcrumb-separator" aria-hidden="true">' . $icon . '</span>';
	}
}

if ( ! function_exists( 'bnh_core_get_posts_page_breadcrumb_item' ) ) {
	/**
	 * Get the posts page breadcrumb item.
	 *
	 * @return array<string,string>|null
	 */
	function bnh_core_get_posts_page_breadcrumb_item() {
		$posts_page_id = (int) get_option( 'page_for_posts' );

		if ( $posts_page_id > 0 ) {
			return array(
				'label' => __( 'Blogs', 'bnh-core' ),
				'url'   => get_permalink( $posts_page_id ),
			);
		}

		if ( 'posts' === get_option( 'show_on_front' ) || is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_date() || is_author() ) {
			return array(
				'label' => __( 'Blog', 'bnh-core' ),
				'url'   => home_url( '/' ),
			);
		}

		return null;
	}
}

if ( ! function_exists( 'bnh_core_breadcrumb' ) ) {
	/**
	 * Display breadcrumb navigation.
	 *
	 * @param bool   $layout_padding Add layout padding class.
	 * @param string $margin_top Margin top utility class.
	 * @param string $margin_bottom Margin bottom utility class.
	 * @return void
	 */
	function bnh_core_breadcrumb( $layout_padding = false, $margin_top = 'mt-30', $margin_bottom = '' ) {
		if ( is_front_page() ) {
			return;
		}

		$items   = array();
		$items[] = bnh_core_breadcrumb_item( __( 'Home', 'bnh-core' ), home_url( '/' ) );

		if ( is_home() ) {
			$posts_page = bnh_core_get_posts_page_breadcrumb_item();
			$items[]    = bnh_core_breadcrumb_item(
				$posts_page ? $posts_page['label'] : __( 'Blogs', 'bnh-core' ),
				'',
				true
			);
		} elseif ( is_singular( 'post' ) ) {
			$parent_term = function_exists( 'bnh_get_post_health_topic_parent_term' ) ? bnh_get_post_health_topic_parent_term( get_the_ID() ) : false;
			$child_term  = function_exists( 'bnh_core_get_post_health_topic_child_term' ) ? bnh_core_get_post_health_topic_child_term( get_the_ID(), $parent_term ) : false;

			if ( $parent_term instanceof WP_Term ) {
				$parent_url = function_exists( 'bnh_get_health_topic_term_url' ) ? bnh_get_health_topic_term_url( $parent_term ) : '';
				$items[]    = bnh_core_breadcrumb_item( $parent_term->name, $parent_url );
			}

			if ( $child_term instanceof WP_Term ) {
				$child_url = function_exists( 'bnh_get_health_topic_term_url' ) ? bnh_get_health_topic_term_url( $child_term ) : '';
				$items[]   = bnh_core_breadcrumb_item( $child_term->name, $child_url );
			}

			$items[] = bnh_core_breadcrumb_item( get_the_title(), '', true );
		} elseif ( is_page() ) {
			$parent_ids = array_reverse( get_post_ancestors( get_the_ID() ) );

			foreach ( $parent_ids as $parent_id ) {
				$items[] = bnh_core_breadcrumb_item( get_the_title( $parent_id ), get_permalink( $parent_id ) );
			}

			$items[] = bnh_core_breadcrumb_item( get_the_title(), '', true );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();

			if ( $term instanceof WP_Term ) {
				$items[] = bnh_core_breadcrumb_item( $term->name, '', true );
			}
		} elseif ( is_post_type_archive() ) {
			$items[] = bnh_core_breadcrumb_item( post_type_archive_title( '', false ), '', true );
		} elseif ( is_search() ) {
			$items[] = bnh_core_breadcrumb_item(
				sprintf(
					/* translators: %s: search query. */
					__( 'Search results for: %s', 'bnh-core' ),
					get_search_query()
				),
				'',
				true
			);
		} elseif ( is_404() ) {
			$items[] = bnh_core_breadcrumb_item( __( '404 Not Found', 'bnh-core' ), '', true );
		} elseif ( is_author() ) {
			$posts_page = bnh_core_get_posts_page_breadcrumb_item();

			if ( $posts_page ) {
				$items[] = bnh_core_breadcrumb_item( $posts_page['label'], $posts_page['url'] );
			}

			$items[] = bnh_core_breadcrumb_item( get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) ), '', true );
		} elseif ( is_date() ) {
			$posts_page = bnh_core_get_posts_page_breadcrumb_item();

			if ( $posts_page ) {
				$items[] = bnh_core_breadcrumb_item( $posts_page['label'], $posts_page['url'] );
			}

			$items[] = bnh_core_breadcrumb_item( wp_get_document_title(), '', true );
		} elseif ( is_singular() ) {
			$post_type = get_post_type_object( get_post_type() );

			if ( $post_type && ! empty( $post_type->has_archive ) ) {
				$items[] = bnh_core_breadcrumb_item( $post_type->labels->name, get_post_type_archive_link( $post_type->name ) );
			}

			$items[] = bnh_core_breadcrumb_item( get_the_title(), '', true );
		}

		$items = array_filter( $items );

		if ( count( $items ) < 2 ) {
			return;
		}

		echo '<nav class="' . esc_attr( implode( ' ', bnh_core_breadcrumb_get_classes( $layout_padding, $margin_top, $margin_bottom ) ) ) . '" aria-label="' . esc_attr__( 'Breadcrumb navigation', 'bnh-core' ) . '">';
		echo '<h2 class="sr-only">' . esc_html__( 'Breadcrumb navigation', 'bnh-core' ) . '</h2>';

		foreach ( $items as $index => $item ) {
			if ( 0 !== $index ) {
				echo bnh_core_breadcrumb_separator(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo $item; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</nav>';
	}
}

if ( ! function_exists( 'purple_surgical_breadcrumb' ) ) {
	/**
	 * Backward-compatible alias.
	 *
	 * @param bool   $layout_padding Add layout padding class.
	 * @param string $margin_top Margin top utility class.
	 * @param string $margin_bottom Margin bottom utility class.
	 * @return void
	 */
	function purple_surgical_breadcrumb( $layout_padding = false, $margin_top = 'mt-30', $margin_bottom = '' ) {
		bnh_core_breadcrumb( $layout_padding, $margin_top, $margin_bottom );
	}
}
