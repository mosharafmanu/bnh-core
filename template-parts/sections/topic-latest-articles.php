<?php
/**
 * Latest articles section.
 *
 * @package BNH_Core
 */

$bnh_context = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();
$latest_query = function_exists( 'bnh_core_get_topic_latest_articles_query' ) ? bnh_core_get_topic_latest_articles_query( $bnh_context ) : null;
$active_parent = $bnh_context['active_parent'] ?? null;
$active_child  = $bnh_context['active_child'] ?? null;
$current_url   = remove_query_arg( 'topic-page' );

ob_start();
get_template_part( 'assets/svgs/arrow-left' );
$bnh_prev_icon = trim( ob_get_clean() );

ob_start();
get_template_part( 'assets/svgs/arrow-right' );
$bnh_next_icon = trim( ob_get_clean() );
?>

<section
	class="topic-latest-articles"
	data-topic-latest
	data-parent-slug="<?php echo esc_attr( $active_parent instanceof WP_Term ? $active_parent->slug : '' ); ?>"
	data-child-slug="<?php echo esc_attr( $active_child instanceof WP_Term ? $active_child->slug : '' ); ?>"
>
	<header class="section-header">
		<h2 class="section-title"><?php esc_html_e( 'Latest Articles', 'bnh-core' ); ?></h2>
	</header>

	<?php if ( $latest_query instanceof WP_Query && $latest_query->have_posts() ) : ?>
		<div class="topic-latest-articles__items">
			<?php
			while ( $latest_query->have_posts() ) :
				$latest_query->the_post();
				?>
				<div class="topic-latest-articles__item">
					<?php
					get_template_part(
						'inc/components/cards/topic-latest-card',
						null,
						array(
							'post'  => get_post(),
							'label' => $active_child instanceof WP_Term ? $active_child->name : '',
						)
					);
					?>
				</div>
				<?php
			endwhile;
			?>
		</div>

		<?php
		$pagination_links = paginate_links(
			array(
				'base'      => esc_url_raw( add_query_arg( 'topic-page', '%#%', $current_url ) ),
				'format'    => '',
				'current'   => max( 1, absint( $bnh_context['paged'] ?? 1 ) ),
				'total'     => max( 1, (int) $latest_query->max_num_pages ),
				'type'      => 'list',
				'prev_text' => '<span class="sr-only">' . esc_html__( 'Previous page', 'bnh-core' ) . '</span>' . $bnh_prev_icon,
				'next_text' => '<span class="sr-only">' . esc_html__( 'Next page', 'bnh-core' ) . '</span>' . $bnh_next_icon,
			)
		);

		if ( $pagination_links ) :
			?>
			<nav class="pagination" aria-label="<?php esc_attr_e( 'Latest articles pagination', 'bnh-core' ); ?>">
				<?php echo $pagination_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</nav>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No latest articles found for this topic yet.', 'bnh-core' ); ?></p>
	<?php endif; ?>
</section>
