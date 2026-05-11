<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BNH_Core
 */

if ( is_singular( 'post' ) ) {
	$bnh_post_id       = get_queried_object_id();
	$bnh_topic_context = function_exists( 'bnh_get_health_topic_context' ) ? bnh_get_health_topic_context() : array();
	$bnh_parent_term   = isset( $bnh_topic_context['active_parent'] ) && $bnh_topic_context['active_parent'] instanceof WP_Term ? $bnh_topic_context['active_parent'] : null;
	$bnh_child_term    = isset( $bnh_topic_context['active_child'] ) && $bnh_topic_context['active_child'] instanceof WP_Term ? $bnh_topic_context['active_child'] : null;
	$bnh_manual_guide  = function_exists( 'get_field' ) ? trim( (string) get_field( 'related_post', $bnh_post_id ) ) : '';
	$bnh_topic_color   = ! empty( $bnh_topic_context['active_topic_color_value'] ) ? (string) $bnh_topic_context['active_topic_color_value'] : '';

	$bnh_similar_query_args = array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => 4,
		'post__not_in'           => array( (int) $bnh_post_id ),
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	if ( $bnh_child_term instanceof WP_Term ) {
		$bnh_similar_query_args['tax_query'] = array(
			array(
				'taxonomy' => 'health_topic',
				'field'    => 'term_id',
				'terms'    => array( (int) $bnh_child_term->term_id ),
			),
		);
	} elseif ( $bnh_parent_term instanceof WP_Term ) {
		$bnh_similar_query_args['tax_query'] = array(
			array(
				'taxonomy'         => 'health_topic',
				'field'            => 'term_id',
				'terms'            => array( (int) $bnh_parent_term->term_id ),
				'include_children' => true,
			),
		);
	}

	$bnh_similar_query = new WP_Query( $bnh_similar_query_args );

	if ( '' === $bnh_manual_guide && ! $bnh_similar_query->have_posts() ) {
		return;
	}
	?>

	<aside id="secondary" class="single-post-sidebar" aria-label="<?php esc_attr_e( 'Article sidebar', 'bnh-core' ); ?>"<?php echo '' !== $bnh_topic_color ? ' style="' . esc_attr( '--single-post-sidebar-topic-color: ' . $bnh_topic_color . ';' ) . '"' : ''; ?>>
		<?php if ( '' !== $bnh_manual_guide ) : ?>
			<section class="single-post-sidebar__guide">
				<div class="single-post-sidebar__guide-content">
					<?php echo wp_kses_post( $bnh_manual_guide ); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $bnh_similar_query->have_posts() ) : ?>
			<section class="single-post-sidebar__similar">
				<h2 class="single-post-sidebar__similar-title h4-style"><?php esc_html_e( 'Similar Articles', 'bnh-core' ); ?></h2>

				<div class="single-post-sidebar__cards">
					<?php foreach ( $bnh_similar_query->posts as $bnh_similar_post ) : ?>
						<?php
						$bnh_similar_post_id      = (int) $bnh_similar_post->ID;
						$bnh_similar_url          = function_exists( 'bnh_core_get_topic_post_url' ) ? bnh_core_get_topic_post_url( $bnh_similar_post ) : get_permalink( $bnh_similar_post );
						$bnh_similar_excerpt      = function_exists( 'bnh_core_get_topic_post_excerpt' ) ? bnh_core_get_topic_post_excerpt( $bnh_similar_post, 15 ) : wp_trim_words( wp_strip_all_tags( get_the_excerpt( $bnh_similar_post_id ) ), 15, ' [...]' );
						$bnh_similar_author       = function_exists( 'bnh_core_get_topic_post_author_name' ) ? bnh_core_get_topic_post_author_name( $bnh_similar_post ) : get_the_author_meta( 'display_name', (int) $bnh_similar_post->post_author );
						$bnh_similar_thumbnail_id = get_post_thumbnail_id( $bnh_similar_post_id );
						?>
						<article class="single-post-sidebar-card<?php echo $bnh_similar_thumbnail_id ? ' single-post-sidebar-card--has-media' : ' single-post-sidebar-card--no-media'; ?>">
							<a class="single-post-sidebar-card__link" href="<?php echo esc_url( $bnh_similar_url ); ?>">
								<?php if ( $bnh_similar_thumbnail_id ) : ?>
									<div class="single-post-sidebar-card__media media">
										<?php
										if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
											bnh_core_render_responsive_picture(
												array(
													'ID'  => $bnh_similar_thumbnail_id,
													'url' => wp_get_attachment_url( $bnh_similar_thumbnail_id ),
													'alt' => get_post_meta( $bnh_similar_thumbnail_id, '_wp_attachment_image_alt', true ),
												),
												array(
													'class'      => 'single-post-sidebar-card__image',
													'alt'        => get_the_title( $bnh_similar_post ),
													'sizes'      => '(max-width: 991px) 100vw, 320px',
													'size_group' => 'sidebar-card',
												)
											);
										} else {
											echo get_the_post_thumbnail( $bnh_similar_post, 'bhn-405', array( 'class' => 'single-post-sidebar-card__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
										?>
									</div>
								<?php endif; ?>

								<div class="single-post-sidebar-card__body">
									<h3 class="single-post-sidebar-card__title h4-style"><?php echo esc_html( get_the_title( $bnh_similar_post ) ); ?></h3>

									<?php if ( '' !== $bnh_similar_excerpt ) : ?>
										<p class="single-post-sidebar-card__excerpt"><?php echo esc_html( $bnh_similar_excerpt ); ?></p>
									<?php endif; ?>

									<div class="single-post-sidebar-card__footer">
										<?php if ( '' !== $bnh_similar_author ) : ?>
											<p class="single-post-sidebar-card__author">
												<?php
												printf(
													/* translators: %s: Post author name. */
													esc_html__( 'By %s', 'bnh-core' ),
													esc_html( $bnh_similar_author )
												);
												?>
											</p>
										<?php endif; ?>

										<span class="single-post-sidebar__arrow single-post-sidebar-card__arrow" aria-hidden="true"></span>
									</div>
								</div>
							</a>
						</article>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>
	</aside>

	<?php
	wp_reset_postdata();
	return;
}

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
