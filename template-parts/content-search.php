<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BNH_Core
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
			printf(
				'<time class="entry-date published" datetime="%1$s">%2$s</time>',
				esc_attr( get_the_date( DATE_W3C ) ),
				esc_html( get_the_date() )
			);
			printf(
				' <span class="byline">%s</span>',
				esc_html(
					sprintf(
						/* translators: %s: post author name. */
						__( 'by %s', 'bnh-core' ),
						get_the_author()
					)
				)
			);
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( ! post_password_required() && has_post_thumbnail() ) : ?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'post-thumbnail' ); ?>
		</a>
	<?php endif; ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php if ( 'post' === get_post_type() ) : ?>
			<?php
			$categories_list = get_the_category_list( esc_html__( ', ', 'bnh-core' ) );

			if ( $categories_list ) {
				printf(
					'<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'bnh-core' ) . '</span>',
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
			?>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
