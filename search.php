<?php
/**
 * Search results template.
 *
 * Normal WordPress search with standard pagination. Topic context is used only
 * for shared header/community presentation, not to alter the search query.
 *
 * @package BNH_Core
 */

get_header();

$bnh_context = function_exists( 'bnh_get_health_topic_context' ) ? bnh_get_health_topic_context() : array();

ob_start();
get_template_part( 'assets/svgs/arrow-left' );
$bnh_prev_icon = trim( ob_get_clean() );

ob_start();
get_template_part( 'assets/svgs/arrow-right' );
$bnh_next_icon = trim( ob_get_clean() );
?>

<main id="primary" class="site-main search-results-page">
	<section class="search-results layout-padding mt-50 mt-md-70 mt-lg-100">
		<header class="search-results__header">
			<h1 class="page-title">
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Search Results for: %s', 'bnh-core' ), esc_html( get_search_query() ) );
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="topic-latest-articles__items">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="topic-latest-articles__item">
						<?php
						get_template_part(
							'inc/components/cards/topic-latest-card',
							null,
							array(
								'post'  => get_post(),
								'label' => function_exists( 'bnh_core_get_topic_post_child_label' ) ? bnh_core_get_topic_post_child_label( get_post() ) : '',
							)
						);
						?>
					</div>
					<?php
				endwhile;
				?>
			</div>

			<?php
			global $wp_query;

			$pagination_links = paginate_links(
				array(
					'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
					'format'    => '',
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'total'     => max( 1, (int) $wp_query->max_num_pages ),
					'type'      => 'list',
					'prev_text' => '<span class="sr-only">' . esc_html__( 'Previous page', 'bnh-core' ) . '</span>' . $bnh_prev_icon,
					'next_text' => '<span class="sr-only">' . esc_html__( 'Next page', 'bnh-core' ) . '</span>' . $bnh_next_icon,
				)
			);

			if ( $pagination_links ) :
				?>
				<nav class="pagination" aria-label="<?php esc_attr_e( 'Search results pagination', 'bnh-core' ); ?>">
					<?php echo $pagination_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</section>

	<?php get_template_part( 'template-parts/sections/topic-community', null, array( 'context' => $bnh_context ) ); ?>
</main>

<?php
get_footer();
