<?php
/**
 * Generic content fallback template
 *
 * @package BNH_Core
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'layout-padding mt-50 mt-md-70 mt-lg-75' ); ?>>
	<?php if ( ! is_singular() ) : ?>
		<header class="entry-header">
			<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
		</header>
	<?php endif; ?>

	<div class="entry-content article-content-block-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bnh-core' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>
</article>
