<?php
/**
 * Child health topic archive layout.
 *
 * @package BNH_Core
 */

$bnh_args         = isset( $args ) && is_array( $args ) ? $args : array();
$bnh_context      = isset( $bnh_args['context'] ) && is_array( $bnh_args['context'] ) ? $bnh_args['context'] : array();
$bnh_child_term   = $bnh_context['active_child'] ?? get_queried_object();
$bnh_parent_term  = $bnh_context['active_parent'] ?? null;
$bnh_topic_color  = ! empty( $bnh_context['active_topic_color_value'] ) ? (string) $bnh_context['active_topic_color_value'] : '';

if ( ! ( $bnh_child_term instanceof WP_Term ) ) {
	$bnh_child_term = null;
}

if ( ! ( $bnh_parent_term instanceof WP_Term ) && $bnh_child_term instanceof WP_Term && $bnh_child_term->parent ) {
	$bnh_parent_term = get_term( $bnh_child_term->parent, 'health_topic' );
}

$bnh_archive_title = $bnh_child_term instanceof WP_Term ? $bnh_child_term->name : single_term_title( '', false );

if ( $bnh_parent_term instanceof WP_Term && $bnh_child_term instanceof WP_Term ) {
	$bnh_archive_title = sprintf(
		/* translators: 1: parent topic name, 2: child topic name. */
		__( '%1$s: %2$s', 'bnh-core' ),
		$bnh_parent_term->name,
		$bnh_child_term->name
	);
}
?>

<main id="primary" class="site-main child-topic-archive"<?php echo '' !== $bnh_topic_color ? ' style="' . esc_attr( '--child-topic-color: ' . $bnh_topic_color . ';' ) . '"' : ''; ?>>
	<div class="child-topic-archive__inner layout-padding">
		<div class="child-topic-archive__grid bens-container">
			<section class="child-topic-archive__main">
				<header class="child-topic-archive__header">
					<h1 class="child-topic-archive__title"><?php echo esc_html( $bnh_archive_title ); ?></h1>
					<?php if ( term_description() ) : ?>
						<div class="child-topic-archive__description">
							<?php echo wp_kses_post( term_description() ); ?>
						</div>
					<?php endif; ?>
				</header>

				<?php if ( have_posts() ) : ?>
					<div class="child-topic-archive__posts">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/archives/child-topic-post-card' );
						endwhile;
						?>
					</div>

					<?php
					$bnh_child_archive_current_page = max( 1, absint( get_query_var( 'paged' ) ), absint( filter_input( INPUT_GET, 'topic-page', FILTER_SANITIZE_NUMBER_INT ) ) );
					$bnh_child_archive_term_link    = get_term_link( $bnh_child_term );

					if ( ! is_wp_error( $bnh_child_archive_term_link ) ) :
						ob_start();
						get_template_part( 'assets/svgs/arrow-left' );
						$bnh_child_archive_prev_icon = ob_get_clean();

						ob_start();
						get_template_part( 'assets/svgs/arrow-right' );
						$bnh_child_archive_next_icon = ob_get_clean();

						$bnh_child_archive_pagination_links = paginate_links(
							array(
								'base'      => esc_url_raw( add_query_arg( 'topic-page', '%#%', $bnh_child_archive_term_link ) ),
								'format'    => '',
								'current'   => $bnh_child_archive_current_page,
								'total'     => max( 1, (int) $GLOBALS['wp_query']->max_num_pages ),
								'mid_size'  => 1,
								'end_size'  => 1,
								'type'      => 'list',
								'prev_text' => '<span class="pagination-arrow">' . $bnh_child_archive_prev_icon . '</span>',
								'next_text' => '<span class="pagination-arrow">' . $bnh_child_archive_next_icon . '</span>',
							)
						);

						if ( $bnh_child_archive_pagination_links ) :
							?>
							<nav class="blog-pagination pagination" aria-label="<?php esc_attr_e( 'Child topic archive pagination', 'bnh-core' ); ?>">
								<?php echo $bnh_child_archive_pagination_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</nav>
							<?php
						endif;
					endif;
					?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</section>

			<?php
			get_template_part(
				'template-parts/sidebars/child-topic-sidebar',
				null,
				array(
					'term' => $bnh_child_term,
				)
			);
			?>
		</div>
	</div>
</main>
