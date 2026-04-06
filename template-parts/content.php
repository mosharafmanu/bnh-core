<?php
/**
 * Post content template
 *
 * @package BNH_Core
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'layout-padding mt-50 mt-md-70 mt-lg-75' ); ?>>
	<div class="entry-content article-content-block-content">
		<?php
		the_content();

		// Handle paginated posts
		wp_link_pages(
			[
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bnh-core' ),
				'after'  => '</div>',
			]
		);
		?>
	</div>
</article>
