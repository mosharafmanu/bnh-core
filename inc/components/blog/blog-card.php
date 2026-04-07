<?php
/**
 * Blog Card Component
 *
 * Reusable blog post card component for displaying post information
 * Used in Latest News section and other blog-related sections
 *
 * @package purple-surgical
 */

/**
 * Render a blog post card
 *
 * @param int|WP_Post $post Post ID or post object.
 * @param array       $args Optional. Additional arguments for customization.
 *                          - 'class' (string) Additional CSS classes for the card wrapper.
 *                          - 'read_more_text' (string) Text for read more link. Default 'Find out more'.
 *                          - 'image_size' (string) WordPress image size. Default 'large'.
 *                          - 'lazy' (bool) Whether to lazy load image. Default true.
 */
function purple_surgical_render_blog_card( $post, $args = [] ) {
	// Get post object
	if ( is_numeric( $post ) ) {
		$post = get_post( $post );
	}

	if ( ! $post instanceof WP_Post ) {
		return;
	}

	// Default arguments
	$defaults = [
		'class'          => '',
		'read_more_text' => __( 'Read more', 'purple-surgical' ),
		'image_size'     => 'large',
		'lazy'           => true,
		'excerpt_length' => 156, // Character length for excerpt
	];

	$args = wp_parse_args( $args, $defaults );

	// Get post data
	$post_id        = $post->ID;
	$post_title     = get_the_title( $post_id );
	$post_permalink = get_permalink( $post_id );
	$post_excerpt   = get_the_excerpt( $post_id );
	$thumbnail_id   = get_post_thumbnail_id( $post_id );

	// Trim excerpt to specified length
	if ( ! empty( $post_excerpt ) && ! empty( $args['excerpt_length'] ) ) {
		if ( strlen( $post_excerpt ) > $args['excerpt_length'] ) {
			$post_excerpt = substr( $post_excerpt, 0, $args['excerpt_length'] ) . '...';
		}
	}

	// Convert thumbnail ID to ACF-style image array for responsive picture helper
	$featured_image = null;
	if ( $thumbnail_id ) {
		$image_url = wp_get_attachment_url( $thumbnail_id );
		if ( $image_url ) {
			$featured_image = [
				'ID'  => $thumbnail_id,
				'url' => $image_url,
				'alt' => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
			];
		}
	}

	// Additional CSS classes
	$card_classes = 'blog-card';
	if ( ! empty( $args['class'] ) ) {
		$card_classes .= ' ' . esc_attr( $args['class'] );
	}

	?>

	<article class="<?php echo esc_attr( $card_classes ); ?>">
		<a href="<?php echo esc_url( $post_permalink ); ?>" class="blog-card__link image-hover" aria-label="<?php echo esc_attr( $post_title ); ?>">

			<!-- Featured Image -->
			<?php if ( $featured_image ) : ?>
				<div class="blog-card__image media">
					<?php
					bnh_core_render_responsive_picture(
						$featured_image,
						[
							'class'         => 'blog-card-img',
							'alt'           => $post_title,
							'sizes'         => '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 25vw',
							'lazy'          => $args['lazy'],
							'fetchpriority' => 'auto',
							'size_group'    => 'blog-card',
						]
					);
					?>
				</div>
			<?php endif; ?>

			<!-- Card Content -->
			<div class="blog-card__content">

				<!-- Post Title -->
				<?php if ( $post_title ) : ?>
					<h3 class="blog-card__title h5-style">
						<?php echo esc_html( $post_title ); ?>
					</h3>
				<?php endif; ?>

				<!-- Post Excerpt -->
				<?php if ( ! empty( $post_excerpt ) ) : ?>
					<div class="blog-card__excerpt">
						<?php echo esc_html( $post_excerpt ); ?>
					</div>
				<?php endif; ?>

				<!-- Read More Link -->
				<?php if ( $args['read_more_text'] ) : ?>
					<div class="blog-card__footer">
						<div class="blog-card__read-more">
							<span class="btn-text">
								<?php echo esc_html( $args['read_more_text'] ); ?>
							</span>
							<span class="btn-icon">
								<?php get_template_part( 'assets/svgs/hover-arrow' ); ?>
							</span>
						</div>
					</div>
				<?php endif; ?>

			</div>

		</a>
	</article>

	<?php
}
