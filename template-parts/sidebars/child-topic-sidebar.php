<?php
/**
 * Child health topic archive sidebar.
 *
 * @package BNH_Core
 */

$bnh_args        = isset( $args ) && is_array( $args ) ? $args : array();
$bnh_term        = $bnh_args['term'] ?? null;

if ( ! ( $bnh_term instanceof WP_Term ) ) {
	return;
}

$bnh_sidebar_query = new WP_Query(
	array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => 4,
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'tax_query'              => array(
			array(
				'taxonomy' => 'health_topic',
				'field'    => 'term_id',
				'terms'    => array( (int) $bnh_term->term_id ),
			),
		),
	)
);

if ( ! $bnh_sidebar_query->have_posts() ) {
	return;
}
?>

<aside class="child-topic-sidebar single-post-sidebar" aria-label="<?php esc_attr_e( 'Similar Articles', 'bnh-core' ); ?>">
	<section class="single-post-sidebar__similar child-topic-sidebar__similar">
		<h2 class="single-post-sidebar__similar-title h4-style"><?php esc_html_e( 'Similar Articles', 'bnh-core' ); ?></h2>

		<div class="single-post-sidebar__cards">
			<?php
			while ( $bnh_sidebar_query->have_posts() ) :
				$bnh_sidebar_query->the_post();

				$bnh_post_id      = get_the_ID();
				$bnh_post_url     = function_exists( 'bnh_core_get_topic_post_url' ) ? bnh_core_get_topic_post_url( $bnh_post_id ) : get_permalink();
				$bnh_excerpt      = function_exists( 'bnh_core_get_topic_post_excerpt' ) ? bnh_core_get_topic_post_excerpt( $bnh_post_id, 15 ) : wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 15, ' [...]' );
				$bnh_author       = function_exists( 'bnh_core_get_topic_post_author_name' ) ? bnh_core_get_topic_post_author_name( $bnh_post_id ) : get_the_author();
				$bnh_thumbnail_id = get_post_thumbnail_id();
				?>
				<article class="single-post-sidebar-card<?php echo $bnh_thumbnail_id ? ' single-post-sidebar-card--has-media' : ' single-post-sidebar-card--no-media'; ?>">
					<a class="single-post-sidebar-card__link" href="<?php echo esc_url( $bnh_post_url ); ?>">
						<?php if ( $bnh_thumbnail_id ) : ?>
							<div class="single-post-sidebar-card__media media">
								<?php
								if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
									bnh_core_render_responsive_picture(
										array(
											'ID'  => $bnh_thumbnail_id,
											'url' => wp_get_attachment_url( $bnh_thumbnail_id ),
											'alt' => get_post_meta( $bnh_thumbnail_id, '_wp_attachment_image_alt', true ),
										),
										array(
											'class'      => 'single-post-sidebar-card__image',
											'alt'        => get_the_title(),
											'sizes'      => '(max-width: 991px) 100vw, 320px',
											'size_group' => 'sidebar-card',
										)
									);
								} else {
									echo get_the_post_thumbnail( null, 'medium_large', array( 'class' => 'single-post-sidebar-card__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
							</div>
						<?php endif; ?>

						<div class="single-post-sidebar-card__body">
							<h3 class="single-post-sidebar-card__title h4-style"><?php the_title(); ?></h3>

							<?php if ( '' !== $bnh_excerpt ) : ?>
								<p class="single-post-sidebar-card__excerpt"><?php echo esc_html( $bnh_excerpt ); ?></p>
							<?php endif; ?>

							<div class="single-post-sidebar-card__footer">
								<?php if ( '' !== $bnh_author ) : ?>
									<p class="single-post-sidebar-card__author">
										<?php
										printf(
											/* translators: %s: post author name. */
											esc_html__( 'By %s', 'bnh-core' ),
											esc_html( $bnh_author )
										);
										?>
									</p>
								<?php endif; ?>

								<span class="single-post-sidebar__arrow single-post-sidebar-card__arrow" aria-hidden="true"></span>
							</div>
						</div>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
	</section>
</aside>

<?php
wp_reset_postdata();
