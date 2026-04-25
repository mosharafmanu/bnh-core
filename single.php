<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package BNH_Core
 */

get_header();
?>

<main id="primary" class="site-main single-post-page">
	<?php
	if ( function_exists( 'bnh_core_breadcrumb' ) ) {
		bnh_core_breadcrumb( true, 'mt-30', 'mt-30' );
	}
	?>

	<div class="single-post layout-padding mt-50 mt-md-70 mt-lg-100">
		<div class="single-post__grid">
			<div class="single-post__content">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;
				?>
			</div>

			<div class="single-post__sidebar">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
