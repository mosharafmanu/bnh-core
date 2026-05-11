<?php
/**
 * Child health topic archive post row.
 *
 * @package BNH_Core
 */

$bnh_post_id      = get_the_ID();
$bnh_post_url     = function_exists( 'bnh_core_get_topic_post_url' ) ? bnh_core_get_topic_post_url( $bnh_post_id ) : get_permalink();
$bnh_excerpt      = function_exists( 'bnh_core_get_topic_post_excerpt' ) ? bnh_core_get_topic_post_excerpt( $bnh_post_id, 24 ) : wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 24, ' [...]' );
$bnh_author_id    = (int) get_the_author_meta( 'ID' );
$bnh_author_name  = get_the_author();
$bnh_author_title = function_exists( 'get_field' ) ? (string) get_field( 'job_title', 'user_' . $bnh_author_id ) : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'child-topic-post' ); ?>>
	<h2 class="child-topic-post__title h4-style">
		<a href="<?php echo esc_url( $bnh_post_url ); ?>"><?php the_title(); ?></a>
	</h2>

	<?php if ( '' !== $bnh_excerpt ) : ?>
		<p class="child-topic-post__excerpt"><?php echo esc_html( $bnh_excerpt ); ?></p>
	<?php endif; ?>

	<div class="child-topic-post__meta">
		<?php echo get_avatar( $bnh_author_id, 48, '', $bnh_author_name, array( 'class' => 'child-topic-post__avatar' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="child-topic-post__byline">
			<?php if ( '' !== $bnh_author_name ) : ?>
				<p class="child-topic-post__author">
					<?php echo esc_html( $bnh_author_name ); ?>
					<?php if ( '' !== $bnh_author_title ) : ?>
						<?php echo esc_html( ' (' . $bnh_author_title . ')' ); ?>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<time class="child-topic-post__date" datetime="<?php echo esc_attr( get_the_modified_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_modified_date( 'd M Y' ) ); ?></time>
			<a class="child-topic-post__read-more" href="<?php echo esc_url( $bnh_post_url ); ?>"><?php esc_html_e( 'Read Article', 'bnh-core' ); ?></a>
		</div>
	</div>
</article>
