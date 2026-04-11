<?php
/**
 * Topic latest card component.
 *
 * @package BNH_Core
 */

$bnh_post        = isset( $args['post'] ) && $args['post'] instanceof WP_Post ? $args['post'] : null;
$bnh_label       = isset( $args['label'] ) ? (string) $args['label'] : '';
$bnh_link        = $bnh_post ? ( function_exists( 'bnh_core_get_topic_post_url' ) ? bnh_core_get_topic_post_url( $bnh_post ) : get_permalink( $bnh_post ) ) : '';
$bnh_excerpt     = $bnh_post && function_exists( 'bnh_core_get_topic_post_excerpt' ) ? bnh_core_get_topic_post_excerpt( $bnh_post, 15 ) : '';
$bnh_author_name = $bnh_post && function_exists( 'bnh_core_get_topic_post_author_name' ) ? bnh_core_get_topic_post_author_name( $bnh_post ) : '';
$bnh_thumbnail_id = $bnh_post ? get_post_thumbnail_id( $bnh_post ) : 0;

if ( ! $bnh_post ) {
	return;
}

ob_start();
get_template_part( 'assets/svgs/long-arrow-right' );
$bnh_arrow_icon = trim( ob_get_clean() );
?>

<article class="topic-card topic-card--latest">
	<a class="topic-card__link" href="<?php echo esc_url( $bnh_link ); ?>">
		<?php if ( $bnh_thumbnail_id ) : ?>
			<div class="topic-card__media">
				<?php
				if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
					bnh_core_render_responsive_picture(
						array(
							'ID'  => $bnh_thumbnail_id,
							'url' => wp_get_attachment_url( $bnh_thumbnail_id ),
							'alt' => get_post_meta( $bnh_thumbnail_id, '_wp_attachment_image_alt', true ),
						),
						array(
							'class'             => 'topic-card__image',
							'alt'               => get_the_title( $bnh_post ),
							'sizes'             => '(max-width: 767px) 85vw, (max-width: 1199px) 22rem, 25vw',
							'size_group'        => 'topic-latest',
							'mobile_size_group' => 'topic-latest',
						)
					);
				} else {
					echo get_the_post_thumbnail( $bnh_post, 'bhn-405', array( 'class' => 'topic-card__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		<?php endif; ?>

		<div class="topic-card__content">
			<?php if ( '' !== $bnh_label ) : ?>
				<div class="topic-card__label"><?php echo esc_html( $bnh_label ); ?></div>
			<?php endif; ?>

			<h3 class="topic-card__title h4-style">
				<?php echo esc_html( get_the_title( $bnh_post ) ); ?>
			</h3>

			<?php if ( '' !== $bnh_excerpt ) : ?>
				<div class="topic-card__excerpt">
					<p><?php echo esc_html( $bnh_excerpt ); ?></p>
				</div>
			<?php endif; ?>

			<div class="topic-card__footer">
				<?php if ( '' !== $bnh_author_name ) : ?>
					<p class="topic-card__author"><?php echo esc_html( 'By ' . $bnh_author_name ); ?></p>
				<?php endif; ?>

				<?php if ( '' !== $bnh_arrow_icon ) : ?>
					<span class="topic-card__arrow" aria-hidden="true">
						<?php echo $bnh_arrow_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</a>
</article>
