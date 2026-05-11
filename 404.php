<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package BNH_Core
 */

get_header();

$bnh_health_topics = get_terms(
	array(
		'taxonomy'   => 'health_topic',
		'parent'     => 0,
		'hide_empty' => false,
		'number'     => 4,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

if ( is_wp_error( $bnh_health_topics ) ) {
	$bnh_health_topics = array();
}

ob_start();
get_template_part( 'assets/svgs/search-icon' );
$bnh_search_icon = trim( ob_get_clean() );
?>

	<main id="primary" class="site-main page-404">

		<section class="error-404 not-found layout-padding" aria-labelledby="error-404-title">
			<div class="error-404-wrapper bens-container">
				<div class="error-404__content">
					<p class="error-404__eyebrow"><?php esc_html_e( '404 Error', 'bnh-core' ); ?></p>

					<header class="page-header error-404__header">
						<h1 id="error-404-title" class="page-title error-404__title h2-large"><?php esc_html_e( 'We could not find that page.', 'bnh-core' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content error-404__body">
						<p><?php esc_html_e( 'The page may have moved, or the link may no longer be active. Search our health library or return to the homepage.', 'bnh-core' ); ?></p>

						<form role="search" method="get" class="error-404__search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<label class="screen-reader-text" for="error-404-search-field"><?php esc_html_e( 'Search for:', 'bnh-core' ); ?></label>
							<input id="error-404-search-field" class="error-404__search-input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search articles and topics', 'bnh-core' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
							<button class="error-404__search-button" type="submit" aria-label="<?php esc_attr_e( 'Search', 'bnh-core' ); ?>">
								<?php echo $bnh_search_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</button>
						</form>

						<div class="error-404-actions">
							<a class="site-btn btn-secondary btn-radius error-404__button" href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php esc_html_e( 'Back to Home', 'bnh-core' ); ?>
							</a>
						</div>
					</div><!-- .page-content -->
				</div>

				<?php if ( ! empty( $bnh_health_topics ) ) : ?>
					<nav class="error-404__topics" aria-label="<?php esc_attr_e( 'Popular health topics', 'bnh-core' ); ?>">
						<h2 class="error-404__topics-title h4-style"><?php esc_html_e( 'Explore health topics', 'bnh-core' ); ?></h2>
						<ul class="error-404__topics-list">
							<?php foreach ( $bnh_health_topics as $bnh_topic ) : ?>
								<?php
								$bnh_topic_url = function_exists( 'bnh_get_health_topic_term_url' ) ? bnh_get_health_topic_term_url( $bnh_topic ) : get_term_link( $bnh_topic );

								if ( is_wp_error( $bnh_topic_url ) || empty( $bnh_topic_url ) ) {
									continue;
								}
								?>
								<li class="error-404__topics-item">
									<a class="error-404__topics-link" href="<?php echo esc_url( $bnh_topic_url ); ?>">
										<?php echo esc_html( $bnh_topic->name ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>

			</div>
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
