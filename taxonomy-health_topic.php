<?php
/**
 * Taxonomy template for health_topic archives.
 *
 * @package BNH_Core
 */

get_header();

$bnh_term    = get_queried_object();
?>

<main id="primary" class="site-main topic-archive">
	<header class="page-header">
		<?php if ( $bnh_term instanceof WP_Term ) : ?>
			<p class="page-eyebrow">
				<?php echo esc_html( 0 === (int) $bnh_term->parent ? __( 'Parent Topic', 'bnh-core' ) : __( 'Child Topic', 'bnh-core' ) ); ?>
			</p>
			<h1 class="page-title"><?php echo esc_html( single_term_title( '', false ) ); ?></h1>
			<?php if ( term_description() ) : ?>
				<div class="archive-description"><?php echo wp_kses_post( term_description() ); ?></div>
			<?php endif; ?>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="archive-posts">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;
			?>
		</div>

		<?php the_posts_navigation(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
