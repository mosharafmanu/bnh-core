<?php
/**
 * Featured article section.
 *
 * @package BNH_Core
 */

$bnh_context      = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();
$featured_post_id = function_exists( 'bnh_core_get_topic_featured_post_id' ) ? bnh_core_get_topic_featured_post_id( $bnh_context ) : 0;
?>

<section class="topic-featured-article" data-topic-featured>
	<header class="section-header">
		<h2 class="section-title"><?php esc_html_e( 'Featured Article', 'bnh-core' ); ?></h2>
	</header>

	<?php if ( $featured_post_id ) : ?>
		<?php
		$featured_post = get_post( $featured_post_id );
		setup_postdata( $featured_post );
		?>
		<article class="topic-featured-article__item">
			<?php if ( has_post_thumbnail( $featured_post ) ) : ?>
				<a class="topic-featured-article__thumbnail" href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>">
					<?php echo get_the_post_thumbnail( $featured_post, 'large' ); ?>
				</a>
			<?php endif; ?>

			<div class="topic-featured-article__content">
				<h3 class="topic-featured-article__title">
					<a href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>">
						<?php echo esc_html( get_the_title( $featured_post ) ); ?>
					</a>
				</h3>
				<div class="topic-featured-article__excerpt">
					<?php echo wp_kses_post( wpautop( get_the_excerpt( $featured_post ) ) ); ?>
				</div>
			</div>
		</article>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No featured article found for this topic yet.', 'bnh-core' ); ?></p>
	<?php endif; ?>
</section>
