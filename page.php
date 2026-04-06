<?php
/**
 * Page template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BNH_Core
 */

get_header();

$page_slug = '';

if ( is_singular( 'page' ) ) {
	$post = get_post();

	if ( $post instanceof WP_Post ) {
		$page_slug = 'page-' . sanitize_html_class( $post->post_name );
	}
}
?>

	<main id="primary" class="site-main <?php echo esc_attr( $page_slug ); ?>">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();

				if ( function_exists( 'bnh_core_has_flexible_content' ) && bnh_core_has_flexible_content( 'cms', get_the_ID() ) && function_exists( 'bnh_core_render_flexible_content' ) ) :
					bnh_core_render_flexible_content( 'cms', get_the_ID() );
				else :
					get_template_part( 'template-parts/content', 'page' );
				endif;
			endwhile;
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
