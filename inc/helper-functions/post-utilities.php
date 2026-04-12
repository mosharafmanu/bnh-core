<?php
/**
 * Post utility helpers.
 *
 * @package BNH_Core
 */

if ( ! function_exists( 'purple_surgical_render_back_to_blogs_button' ) ) {
	/**
	 * Render back to blogs button
	 *
	 * Only works for WordPress default blog posts (post type 'post').
	 * Does not render for custom post types.
	 *
	 * @param array $args {
	 *     Optional arguments.
	 *
	 *     @type string $url           Blog page URL. Default uses page_for_posts option.
	 *     @type string $text          Button label. Default "Back to Blogs".
	 *     @type string $class         CSS classes. Default "site-btn green-mid-black".
	 *     @type string $wrapper_class Wrapper div classes. Default "back-to-blogs-button layout-margin".
	 * }
	 * @return void
	 */
	function purple_surgical_render_back_to_blogs_button( $args = [] ) {
		// Only render for WordPress default blog posts
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		$defaults = [
			'url'           => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ),
			'text'          => __( 'Back to News & Events', 'bnh-core' ),
			'class'         => 'site-btn pink-purple',
			'wrapper_class' => 'back-to-blogs-button text-center layout-margin mt-50 mt-md-70 mt-lg-100 pt-50 pt-md-70 pt-lg-100',
		];
		$args = wp_parse_args( $args, $defaults );
		?>

		<div class="<?php echo esc_attr( $args['wrapper_class'] ); ?>">
			<a href="<?php echo esc_url( $args['url'] ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>">
				<?php echo esc_html( $args['text'] ); ?>
			</a>
		</div>

		<?php
	}
}

if ( ! function_exists( 'bnh_core_render_back_to_blogs_button' ) ) {
	/**
	 * BNH alias for the back-to-posts button helper.
	 *
	 * @param array $args Render arguments.
	 * @return void
	 */
	function bnh_core_render_back_to_blogs_button( $args = [] ) {
		purple_surgical_render_back_to_blogs_button( $args );
	}
}

if ( ! function_exists( 'bnh_core_get_post_reading_time' ) ) {
	/**
	 * Estimate post reading time in minutes.
	 *
	 * @param WP_Post|int|null $post Post object or ID.
	 * @param int              $wpm  Words per minute.
	 * @return string
	 */
	function bnh_core_get_post_reading_time( $post = null, $wpm = 200 ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return '';
		}

		$word_count = str_word_count( wp_strip_all_tags( (string) $post->post_content ) );
		$minutes    = max( 1, (int) ceil( $word_count / max( 1, (int) $wpm ) ) );

		/* translators: %d: estimated reading time in minutes. */
		return sprintf( _n( '%d minute', '%d minutes', $minutes, 'bnh-core' ), $minutes );
	}
}

if ( ! function_exists( 'bnh_core_get_post_summary_text' ) ) {
	/**
	 * Get a short single-post summary.
	 *
	 * Uses the excerpt first, then falls back to trimmed content.
	 *
	 * @param WP_Post|int|null $post  Post object or ID.
	 * @param int              $words Number of words.
	 * @return string
	 */
	function bnh_core_get_post_summary_text( $post = null, $words = 26 ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return '';
		}

		if ( has_excerpt( $post ) ) {
			return wp_strip_all_tags( get_the_excerpt( $post ) );
		}

		return wp_trim_words( wp_strip_all_tags( (string) $post->post_content ), $words, '...' );
	}
}

if ( ! function_exists( 'bnh_core_get_post_summary_markup' ) ) {
	/**
	 * Get post summary markup from ACF or excerpt fallback.
	 *
	 * @param WP_Post|int|null $post Post object or ID.
	 * @return string
	 */
	function bnh_core_get_post_summary_markup( $post = null ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return '';
		}

		if ( function_exists( 'get_field' ) ) {
			$acf_summary = (string) get_field( 'article_summary', $post->ID );

			if ( '' !== trim( $acf_summary ) ) {
				return $acf_summary;
			}
		}

		$fallback_summary = bnh_core_get_post_summary_text( $post );

		if ( '' === $fallback_summary ) {
			return '';
		}

		return '<p>' . esc_html( $fallback_summary ) . '</p>';
	}
}

if ( ! function_exists( 'bnh_core_get_post_table_of_contents' ) ) {
	/**
	 * Prepare UTF-8 HTML for DOMDocument parsing.
	 *
	 * @param string $content Raw HTML content.
	 * @return string
	 */
	function bnh_core_prepare_html_for_dom_document( $content ) {
		$content = (string) $content;

		if ( '' === $content ) {
			return '';
		}

		if ( function_exists( 'mb_encode_numericentity' ) ) {
			return mb_encode_numericentity( $content, array( 0x80, 0x10FFFF, 0, 0x10FFFF ), 'UTF-8' );
		}

		return $content;
	}
}

if ( ! function_exists( 'bnh_core_get_post_table_of_contents' ) ) {
	/**
	 * Build a table of contents from post H2 headings and inject matching IDs.
	 *
	 * @param WP_Post|int|null $post Post object or ID.
	 * @return array{content:string,items:array<int,array{id:string,title:string}>}
	 */
	function bnh_core_get_post_table_of_contents( $post = null ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post || '' === trim( (string) $post->post_content ) ) {
			return array(
				'content' => '',
				'items'   => array(),
			);
		}

		$content           = bnh_core_prepare_html_for_dom_document( (string) $post->post_content );
		$previous_libxml   = libxml_use_internal_errors( true );

		$document = new DOMDocument();
		$loaded   = $document->loadHTML(
			'<?xml encoding="utf-8" ?>' . $content,
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
		);

		libxml_clear_errors();
		libxml_use_internal_errors( $previous_libxml );

		if ( ! $loaded ) {
			return array(
				'content' => apply_filters( 'the_content', (string) $post->post_content ),
				'items'   => array(),
			);
		}

		$items = array();
		$index = 1;

		foreach ( $document->getElementsByTagName( 'h2' ) as $heading ) {
			$title = trim( preg_replace( '/\s+/', ' ', $heading->textContent ) );

			if ( '' === $title ) {
				continue;
			}

			$id = $heading->getAttribute( 'id' );

			if ( '' === $id ) {
				$id = sanitize_title( $title );
			}

			if ( '' === $id ) {
				$id = 'section-' . $index;
			}

			$heading->setAttribute( 'id', $id );

			$items[] = array(
				'id'    => $id,
				'title' => $title,
			);

			$index++;
		}

		$updated_content = $document->saveHTML();

		return array(
			'content' => apply_filters( 'the_content', $updated_content ),
			'items'   => $items,
		);
	}
}

if ( ! function_exists( 'bnh_core_get_editorial_guidelines_url' ) ) {
	/**
	 * Resolve the editorial guidelines page URL when available.
	 *
	 * @return string
	 */
	function bnh_core_get_editorial_guidelines_url() {
		$page = get_page_by_path( 'editorial-guidelines' );

		if ( $page instanceof WP_Post ) {
			return (string) get_permalink( $page );
		}

		return 'https://www.bensnaturalhealth.com/editorial-guidelines';
	}
}

if ( ! function_exists( 'bnh_core_render_topic_community_section' ) ) {
	/**
	 * Render the reusable topic community section and return its markup.
	 *
	 * @param array $args Optional render arguments.
	 * @return string
	 */
	function bnh_core_render_topic_community_section( $args = array() ) {
		$args    = is_array( $args ) ? $args : array();
		$context = array();

		if ( isset( $args['context'] ) && is_array( $args['context'] ) ) {
			$context = $args['context'];
		} elseif ( function_exists( 'bnh_get_health_topic_context' ) ) {
			$context = bnh_get_health_topic_context();
		}

		ob_start();
		get_template_part(
			'template-parts/sections/topic-community',
			null,
			array(
				'context' => $context,
			)
		);

		return (string) ob_get_clean();
	}
}

if ( ! function_exists( 'bnh_core_topic_community_shortcode' ) ) {
	/**
	 * Shortcode renderer for the inline topic community section.
	 *
	 * Usage: [bnh_topic_community]
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string
	 */
	function bnh_core_topic_community_shortcode( $atts = array() ) {
		$atts = shortcode_atts( array(), (array) $atts, 'bnh_topic_community' );

		return bnh_core_render_topic_community_section();
	}
}

if ( ! shortcode_exists( 'bnh_topic_community' ) ) {
	add_shortcode( 'bnh_topic_community', 'bnh_core_topic_community_shortcode' );
}
