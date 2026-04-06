<?php
/**
 * Template tags.
 *
 * @package BNH_Core
 */

if ( ! function_exists( 'bnh_core_posted_on' ) ) {
	/**
	 * Print the post date.
	 *
	 * @return void
	 */
	function bnh_core_posted_on() {
		$time_string = sprintf(
			'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		printf(
			'<span class="posted-on">%s</span>',
			wp_kses_post( $time_string )
		);
	}
}

if ( ! function_exists( 'bnh_core_posted_by' ) ) {
	/**
	 * Print the author.
	 *
	 * @return void
	 */
	function bnh_core_posted_by() {
		printf(
			'<span class="byline">%s</span>',
			wp_kses_post(
				sprintf(
					/* translators: %s: post author. */
					esc_html__( 'By %s', 'bnh-core' ),
					'<span class="author vcard">' . esc_html( get_the_author() ) . '</span>'
				)
			)
		);
	}
}

if ( ! function_exists( 'bnh_core_post_thumbnail' ) ) {
	/**
	 * Render the post thumbnail if present.
	 *
	 * @return void
	 */
	function bnh_core_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) {
			?>
			<div class="post-thumbnail">
				<?php the_post_thumbnail( 'large' ); ?>
			</div>
			<?php
			return;
		}
		?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'medium_large' ); ?>
		</a>
		<?php
	}
}

if ( ! function_exists( 'bnh_core_entry_footer' ) ) {
	/**
	 * Print post footer metadata.
	 *
	 * @return void
	 */
	function bnh_core_entry_footer() {
		if ( 'post' !== get_post_type() ) {
			return;
		}

		if ( ! function_exists( 'bnh_get_post_health_topic_parent_term' ) ) {
			return;
		}

		$parent_term = bnh_get_post_health_topic_parent_term( get_the_ID() );

		if ( $parent_term instanceof WP_Term ) {
			$term_link = function_exists( 'bnh_get_health_topic_term_url' ) ? bnh_get_health_topic_term_url( $parent_term ) : get_term_link( $parent_term );

			if ( is_wp_error( $term_link ) || empty( $term_link ) ) {
				return;
			}

			printf(
				'<span class="entry-topic"><a href="%1$s">%2$s</a></span>',
				esc_url( $term_link ),
				esc_html( $parent_term->name )
			);
		}
	}
}
