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

			return '';
		}
	}

if ( ! function_exists( 'bnh_core_extract_source_citations' ) ) {
	/**
	 * Extract citation text from source list markup.
	 *
	 * @param string $html Source field HTML.
	 * @return string[]
	 */
	function bnh_core_extract_source_citations( $html ) {
		$html = trim( (string) $html );

		if ( '' === $html || ! class_exists( 'DOMDocument' ) ) {
			return array();
		}

		$dom = new DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( '<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();

		$citations = array();
		$list_items = $dom->getElementsByTagName( 'li' );

		foreach ( $list_items as $list_item ) {
			$citation = trim( preg_replace( '/\s+/', ' ', $list_item->textContent ) );

			if ( '' !== $citation ) {
				$citations[] = $citation;
			}
		}

		if ( empty( $citations ) ) {
			$fallback = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $html ) ) );

			if ( '' !== $fallback ) {
				$citations[] = $fallback;
			}
		}

		return $citations;
	}
}

if ( ! function_exists( 'bnh_core_filter_yoast_medical_webpage_schema' ) ) {
	/**
	 * Add single-post medical metadata to Yoast WebPage schema.
	 *
	 * @param array $data Yoast WebPage schema data.
	 * @return array
	 */
	function bnh_core_filter_yoast_medical_webpage_schema( $data ) {
		if ( ! is_singular( 'post' ) || ! function_exists( 'get_field' ) || ! is_array( $data ) ) {
			return $data;
		}

		$post_id = get_queried_object_id();
		$medical_condition = trim( (string) get_field( 'medical_condition', $post_id ) );
		$sources = (string) get_field( 'sources', $post_id );
		$reviewer = get_field( 'medically_reviewed_by', $post_id );
		$reviewer_context = function_exists( 'bnh_core_get_person_context' ) ? bnh_core_get_person_context( $reviewer ) : null;

		$data['medicalAudience'] = array(
			'@type'          => 'MedicalAudience',
			'requiredMinAge' => 40,
		);
		$data['specialty'] = array( 'https://schema.org/Urologic', 'https://schema.org/Geriatric' );

		if ( '' !== $medical_condition ) {
			$data['about'] = array(
				'@type' => 'MedicalCondition',
				'name'  => $medical_condition,
			);
		}

		if ( is_array( $reviewer_context ) && ! empty( $reviewer_context['name'] ) ) {
			$data['reviewedBy'] = array(
				'@type' => 'Person',
				'name'  => $reviewer_context['name'],
			);
		}

		$citations = bnh_core_extract_source_citations( $sources );

		if ( ! empty( $citations ) ) {
			$data['citation'] = $citations;
		}

		return $data;
	}
}
add_filter( 'wpseo_schema_webpage', 'bnh_core_filter_yoast_medical_webpage_schema', 10, 1 );

if ( ! function_exists( 'bnh_core_get_person_context' ) ) {
	/**
	 * Resolve a user/person context array for author, reviewer, and update history output.
	 *
	 * @param mixed $user_reference User ID, ACF user array, WP_User, or similar value.
	 * @return array|null
	 */
	function bnh_core_get_person_context( $user_reference ) {
		$user_id = 0;

		if ( $user_reference instanceof WP_User ) {
			$user_id = (int) $user_reference->ID;
		} elseif ( is_array( $user_reference ) ) {
			if ( isset( $user_reference['ID'] ) ) {
				$user_id = (int) $user_reference['ID'];
			} elseif ( isset( $user_reference['id'] ) ) {
				$user_id = (int) $user_reference['id'];
			}
		} else {
			$user_id = (int) $user_reference;
		}

		if ( $user_id <= 0 ) {
			return null;
		}

		$user = get_userdata( $user_id );

		if ( ! $user instanceof WP_User ) {
			return null;
		}

		$job_title  = function_exists( 'get_field' ) ? (string) get_field( 'job_title', 'user_' . $user_id ) : '';
		$popup_info = function_exists( 'get_field' ) ? (string) get_field( 'popup_info', 'user_' . $user_id ) : '';
		$avatar_id  = absint( get_user_option( 'metronet_image_id', $user_id ) );

		return array(
			'id'        => $user_id,
			'name'      => $user->display_name,
			'job_title' => $job_title,
			'popup'     => $popup_info,
			'bio'       => (string) get_the_author_meta( 'description', $user_id ),
			'url'       => get_author_posts_url( $user_id ),
			'avatar_id' => $avatar_id,
		);
	}
}

if ( ! function_exists( 'bnh_core_get_post_update_history' ) ) {
	/**
	 * Build dynamic created/updated history rows for a post.
	 *
	 * @param WP_Post|int|null $post Post object or ID.
	 * @return array<int,array<string,mixed>>
	 */
	function bnh_core_get_post_update_history( $post = null ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return array();
		}

		$created_person = bnh_core_get_person_context( (int) $post->post_author );
		$created_stamp  = get_post_time( 'U', true, $post );
		$modified_stamp = get_post_modified_time( 'U', true, $post );

		$history = array(
			array(
				'type'    => 'created',
				'heading' => sprintf(
					/* translators: %s: created date. */
					__( 'Created on %s', 'bnh-core' ),
					get_the_date( 'j F, Y', $post )
				),
				'label'   => __( 'Created by', 'bnh-core' ),
				'person'  => $created_person,
			),
		);

		if ( $modified_stamp > $created_stamp ) {
			$updated_person = null;
			$revisions      = wp_get_post_revisions(
				$post->ID,
				array(
					'posts_per_page' => 20,
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);

			if ( ! empty( $revisions ) ) {
				foreach ( $revisions as $revision ) {
					if ( ! $revision instanceof WP_Post ) {
						continue;
					}

					if ( wp_is_post_autosave( $revision->ID ) ) {
						continue;
					}

					$updated_person = bnh_core_get_person_context( (int) $revision->post_author );

					if ( $updated_person ) {
						break;
					}
				}
			}

			if ( ! $updated_person ) {
				$updated_person = $created_person;
			}

			array_unshift(
				$history,
				array(
					'type'    => 'updated',
					'heading' => sprintf(
						/* translators: %s: updated date. */
						__( 'Updated on %s', 'bnh-core' ),
						get_the_modified_date( 'j F, Y', $post )
					),
					'current_label' => __( 'Current Version', 'bnh-core' ),
					'label'         => __( 'Updated by', 'bnh-core' ),
					'person'        => $updated_person,
				)
			);
		}

		return $history;
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

	/**
	 * Restore paragraph markup for legacy block content that saved bare text.
	 *
	 * Some migrated posts contain wp:paragraph comments without the expected
	 * inner <p> tag. WordPress skips wpautop on block content, so those blocks
	 * render as bare text nodes unless we normalize them before rendering.
	 *
	 * @param string $content Raw post content.
	 * @return string
	 */
	function bnh_core_normalize_legacy_paragraph_blocks( $content ) {
		$content = (string) $content;

		if ( '' === $content || false === strpos( $content, '<!-- wp:paragraph' ) ) {
			return $content;
		}

		return preg_replace_callback(
			'/<!--\s+wp:paragraph(?P<attrs>\s+\{.*?\})?\s+-->(?P<inner>.*?)<!--\s+\/wp:paragraph\s+-->/s',
			static function ( $matches ) {
				$inner = isset( $matches['inner'] ) ? (string) $matches['inner'] : '';

				if ( '' === trim( $inner ) ) {
					return $matches[0];
				}

				if ( preg_match( '/<\s*p(?:\s|>)/i', $inner ) || preg_match( '/<!--\s+wp:/', $inner ) ) {
					return $matches[0];
				}

				if ( preg_match( '/<\s*(address|article|aside|blockquote|div|figure|form|h[1-6]|hr|ol|pre|section|table|ul)(?:\s|>)/i', $inner ) ) {
					return $matches[0];
				}

				$attrs = isset( $matches['attrs'] ) ? (string) $matches['attrs'] : '';

				return '<!-- wp:paragraph' . $attrs . " -->\n<p>" . trim( $inner ) . "</p>\n<!-- /wp:paragraph -->";
			},
			$content
		);
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

		$raw_content       = bnh_core_normalize_legacy_paragraph_blocks( (string) $post->post_content );
		$rendered_content  = apply_filters( 'the_content', $raw_content );
		$content           = bnh_core_prepare_html_for_dom_document( $rendered_content );
		$previous_libxml   = libxml_use_internal_errors( true );

		$document = new DOMDocument();
		$loaded   = $document->loadHTML(
			'<?xml encoding="utf-8" ?><div id="bnh-single-content-root">' . $content . '</div>',
			LIBXML_HTML_NODEFDTD
		);

		libxml_clear_errors();
		libxml_use_internal_errors( $previous_libxml );

		if ( ! $loaded ) {
			return array(
				'content' => $rendered_content,
				'items'   => array(),
			);
		}

		$root = $document->getElementById( 'bnh-single-content-root' );

		if ( ! $root instanceof DOMElement ) {
			return array(
				'content' => $rendered_content,
				'items'   => array(),
			);
		}

		$items = array();
		$index = 1;

		foreach ( $root->getElementsByTagName( 'h2' ) as $heading ) {
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

		$updated_content = '';

		foreach ( $root->childNodes as $child_node ) {
			$updated_content .= $document->saveHTML( $child_node );
		}

		return array(
			'content' => $updated_content,
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

if ( ! function_exists( 'bnh_core_get_explore_more_post' ) ) {
	/**
	 * Resolve the Explore More post for a single article.
	 *
	 * Manual ACF post selection wins. If nothing is selected, fall back to the
	 * most recent published post in the same child topic, then same parent topic.
	 *
	 * @param WP_Post|int|null $post Post object or ID.
	 * @return WP_Post|null
	 */
	function bnh_core_get_explore_more_post( $post = null ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return null;
		}

		if ( function_exists( 'get_field' ) ) {
			$manual_post = get_field( 'explore_more_post', $post->ID );

			if ( $manual_post instanceof WP_Post && 'publish' === get_post_status( $manual_post ) && (int) $manual_post->ID !== (int) $post->ID ) {
				return $manual_post;
			}

			if ( is_numeric( $manual_post ) ) {
				$manual_post = get_post( (int) $manual_post );

				if ( $manual_post instanceof WP_Post && 'publish' === get_post_status( $manual_post ) && (int) $manual_post->ID !== (int) $post->ID ) {
					return $manual_post;
				}
			}
		}

		$parent_term = function_exists( 'bnh_get_post_health_topic_parent_term' ) ? bnh_get_post_health_topic_parent_term( $post->ID ) : null;
		$child_term  = $parent_term instanceof WP_Term && function_exists( 'bnh_core_get_post_health_topic_child_term' ) ? bnh_core_get_post_health_topic_child_term( $post->ID, $parent_term ) : null;

		$query_args = array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'post__not_in'           => array( (int) $post->ID ),
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		if ( $child_term instanceof WP_Term ) {
			$child_query = new WP_Query(
				array_merge(
					$query_args,
					array(
						'tax_query' => array(
							array(
								'taxonomy' => 'health_topic',
								'field'    => 'term_id',
								'terms'    => array( (int) $child_term->term_id ),
							),
						),
					)
				)
			);

			if ( $child_query->have_posts() ) {
				return $child_query->posts[0];
			}
		}

		if ( $parent_term instanceof WP_Term ) {
			$parent_query = new WP_Query(
				array_merge(
					$query_args,
					array(
						'tax_query' => array(
							array(
								'taxonomy'         => 'health_topic',
								'field'            => 'term_id',
								'terms'            => array( (int) $parent_term->term_id ),
								'include_children' => true,
							),
						),
					)
				)
			);

			if ( $parent_query->have_posts() ) {
				return $parent_query->posts[0];
			}
		}

		return null;
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

if ( ! function_exists( 'bnh_core_get_editor_shortcodes' ) ) {
	/**
	 * Return the BNH shortcode registry used by editor UI controls.
	 *
	 * @return array<int,array{tag:string,label:string,description:string}>
	 */
	function bnh_core_get_editor_shortcodes() {
		return array(
			array(
				'tag'         => 'bnh_topic_community',
				'label'       => __( 'Topic Community', 'bnh-core' ),
				'description' => __( 'Insert the reusable topic community signup section.', 'bnh-core' ),
			),
			array(
				'tag'         => 'bnh_book_consultation',
				'label'       => __( 'Book Consultation', 'bnh-core' ),
				'description' => __( 'Insert the reusable book consultation section.', 'bnh-core' ),
			),
		);
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

if ( ! function_exists( 'bnh_core_render_book_consultation_section' ) ) {
	/**
	 * Render the reusable book consultation section and return its markup.
	 *
	 * @return string
	 */
	function bnh_core_render_book_consultation_section() {
		ob_start();
		get_template_part(
			'template-parts/sections/book_consultation',
			null,
			array(
				'source' => 'site_settings',
			)
		);

		return (string) ob_get_clean();
	}
}

if ( ! function_exists( 'bnh_core_book_consultation_shortcode' ) ) {
	/**
	 * Shortcode renderer for the reusable book consultation section.
	 *
	 * Usage: [bnh_book_consultation]
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string
	 */
	function bnh_core_book_consultation_shortcode( $atts = array() ) {
		$atts = shortcode_atts( array(), (array) $atts, 'bnh_book_consultation' );

		return bnh_core_render_book_consultation_section();
	}
}

if ( ! shortcode_exists( 'bnh_book_consultation' ) ) {
	add_shortcode( 'bnh_book_consultation', 'bnh_core_book_consultation_shortcode' );
}

if ( ! function_exists( 'bnh_core_render_topic_community_block' ) ) {
	/**
	 * Dynamic block renderer for the reusable topic community section.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string
	 */
	function bnh_core_render_topic_community_block( $attributes = array(), $content = '' ) {
		unset( $attributes, $content );

		return bnh_core_render_topic_community_section();
	}
}

if ( ! function_exists( 'bnh_core_render_book_consultation_block' ) ) {
	/**
	 * Dynamic block renderer for the reusable book consultation section.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string
	 */
	function bnh_core_render_book_consultation_block( $attributes = array(), $content = '' ) {
		unset( $attributes, $content );

		return bnh_core_render_book_consultation_section();
	}
}
