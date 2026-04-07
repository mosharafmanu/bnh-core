<?php
/**
 * Topic featured card component.
 *
 * @package BNH_Core
 */

$bnh_post        = isset( $args['post'] ) && $args['post'] instanceof WP_Post ? $args['post'] : null;
$bnh_label       = isset( $args['label'] ) ? (string) $args['label'] : '';
$bnh_show_label  = ! empty( $args['show_label'] );
$bnh_link        = $bnh_post ? ( function_exists( 'bnh_core_get_topic_post_url' ) ? bnh_core_get_topic_post_url( $bnh_post ) : get_permalink( $bnh_post ) ) : '';
$bnh_excerpt     = $bnh_post && function_exists( 'bnh_core_get_topic_post_excerpt' ) ? bnh_core_get_topic_post_excerpt( $bnh_post, 15 ) : '';
$bnh_author_name = $bnh_post && function_exists( 'bnh_core_get_topic_post_author_name' ) ? bnh_core_get_topic_post_author_name( $bnh_post ) : '';
$bnh_date        = $bnh_post && function_exists( 'bnh_core_get_topic_post_date' ) ? bnh_core_get_topic_post_date( $bnh_post ) : '';

if ( ! $bnh_post ) {
	return;
}
?>

<article class="topic-card topic-card--featured">
	<?php if ( has_post_thumbnail( $bnh_post ) ) : ?>
		<a class="topic-card__media" href="<?php echo esc_url( $bnh_link ); ?>">
			<?php echo get_the_post_thumbnail( $bnh_post, 'large' ); ?>
		</a>
	<?php endif; ?>

	<div class="topic-card__content">
		<?php if ( $bnh_show_label && '' !== $bnh_label ) : ?>
			<div class="topic-card__label"><?php echo esc_html( $bnh_label ); ?></div>
		<?php endif; ?>

		<h3 class="topic-card__title">
			<a href="<?php echo esc_url( $bnh_link ); ?>">
				<?php echo esc_html( get_the_title( $bnh_post ) ); ?>
			</a>
		</h3>

		<?php if ( '' !== $bnh_excerpt ) : ?>
			<div class="topic-card__excerpt">
				<p><?php echo esc_html( $bnh_excerpt ); ?></p>
			</div>
		<?php endif; ?>

		<div class="topic-card__meta">
			<?php if ( '' !== $bnh_author_name ) : ?>
				<p class="topic-card__author"><?php echo esc_html( 'Written by ' . $bnh_author_name ); ?></p>
			<?php endif; ?>

			<?php if ( '' !== $bnh_date ) : ?>
				<p class="topic-card__date"><?php echo esc_html( $bnh_date ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</article>
