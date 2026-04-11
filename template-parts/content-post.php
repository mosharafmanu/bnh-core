<?php
/**
 * Post single content template
 *
 * @package BNH_Core
 */

$bnh_post_id          = get_the_ID();
$bnh_summary_markup   = function_exists( 'bnh_core_get_post_summary_markup' ) ? bnh_core_get_post_summary_markup( $bnh_post_id ) : '';
$bnh_reading_time     = function_exists( 'bnh_core_get_post_reading_time' ) ? bnh_core_get_post_reading_time( $bnh_post_id ) : '';
$bnh_toc              = function_exists( 'bnh_core_get_post_table_of_contents' ) ? bnh_core_get_post_table_of_contents( $bnh_post_id ) : array(
	'content' => apply_filters( 'the_content', get_the_content() ),
	'items'   => array(),
);
$bnh_thumbnail_id     = get_post_thumbnail_id();
$bnh_author_id        = (int) get_the_author_meta( 'ID' );
$bnh_author_name      = get_the_author();
$bnh_published_date   = get_the_date( 'F j, Y' );
$bnh_author_job       = function_exists( 'get_field' ) ? (string) get_field( 'job_title', 'user_' . $bnh_author_id ) : '';
$bnh_author_popup     = function_exists( 'get_field' ) ? (string) get_field( 'popup_info', 'user_' . $bnh_author_id ) : '';
$bnh_author_bio       = (string) get_the_author_meta( 'description', $bnh_author_id );
$bnh_author_url       = get_author_posts_url( $bnh_author_id );
$bnh_editorial_url    = function_exists( 'bnh_core_get_editorial_guidelines_url' ) ? bnh_core_get_editorial_guidelines_url() : '';
$bnh_sources          = function_exists( 'get_field' ) ? (string) get_field( 'sources', $bnh_post_id ) : '';
$bnh_has_list_summary = false !== stripos( $bnh_summary_markup, '<li' );

if ( '' !== $bnh_sources ) {
	$bnh_toc['items'][] = array(
		'id'    => 'article-sources',
		'title' => __( 'Source', 'bnh-core' ),
	);
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article article-content-block-content' ); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( $bnh_thumbnail_id ) : ?>
			<div class="entry-thumbnail">
				<?php
				if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
					bnh_core_render_responsive_picture(
						array(
							'ID'  => $bnh_thumbnail_id,
							'url' => wp_get_attachment_url( $bnh_thumbnail_id ),
							'alt' => get_post_meta( $bnh_thumbnail_id, '_wp_attachment_image_alt', true ),
						),
						array(
							'class'             => 'entry-thumbnail__image',
							'alt'               => get_the_title(),
							'sizes'             => '(max-width: 991px) 100vw, 972px',
							'size_group'        => 'article-full',
							'mobile_size_group' => 'article-full',
						)
					);
				} else {
					echo get_the_post_thumbnail( null, 'bhn-972', array( 'class' => 'entry-thumbnail__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		<?php endif; ?>

		<div class="entry-meta" aria-label="<?php esc_attr_e( 'Article metadata', 'bnh-core' ); ?>">
			<?php if ( $bnh_author_id ) : ?>
				<div class="entry-meta__item entry-meta__item--author">
					<button class="entry-meta__author-trigger" type="button" aria-expanded="false">
						<?php echo get_avatar( $bnh_author_id, 40, '', $bnh_author_name, array( 'class' => 'entry-meta__avatar' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="entry-meta__text">
							<?php
							printf(
								/* translators: %s: author name. */
								esc_html__( 'Written by %s', 'bnh-core' ),
								esc_html( $bnh_author_name )
							);
							?>
						</span>
					</button>

					<div class="entry-meta__author-popup" role="dialog" aria-label="<?php esc_attr_e( 'Author information', 'bnh-core' ); ?>">
						<div class="entry-meta__author-popup-header">
							<?php echo get_avatar( $bnh_author_id, 120, '', $bnh_author_name, array( 'class' => 'entry-meta__author-popup-avatar' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<div class="entry-meta__author-popup-heading">
								<h2 class="entry-meta__author-popup-title h4-style"><?php echo esc_html( $bnh_author_name ); ?></h2>
								<?php if ( '' !== $bnh_author_job ) : ?>
									<p class="entry-meta__author-popup-job"><?php echo esc_html( $bnh_author_job ); ?></p>
								<?php endif; ?>
							</div>
						</div>

						<div class="entry-meta__author-popup-content">
							<?php
							if ( '' !== $bnh_author_popup ) {
								echo wp_kses_post( $bnh_author_popup );
							} elseif ( '' !== $bnh_author_bio ) {
								echo wpautop( esc_html( wp_trim_words( $bnh_author_bio, 40, '...' ) ) );
							}
							?>
						</div>

						<div class="entry-meta__author-popup-links">
							<a class="entry-meta__author-popup-link entry-meta__author-popup-link--primary site-btn btn-secondary btn-radius" href="<?php echo esc_url( $bnh_author_url ); ?>"><?php esc_html_e( 'See Full Bio', 'bnh-core' ); ?></a>
							<?php if ( '' !== $bnh_editorial_url ) : ?>
								<a class="entry-meta__author-popup-link entry-meta__author-popup-link--secondary" href="<?php echo esc_url( $bnh_editorial_url ); ?>"><?php esc_html_e( 'Our Editorial Process', 'bnh-core' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( '' !== $bnh_published_date ) : ?>
				<div class="entry-meta__item">
					<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( $bnh_published_date ); ?></time>
				</div>
			<?php endif; ?>

			<?php if ( '' !== $bnh_reading_time ) : ?>
				<div class="entry-meta__item"><?php echo esc_html( $bnh_reading_time ); ?></div>
			<?php endif; ?>
		</div>

		<?php if ( '' !== $bnh_summary_markup ) : ?>
			<section class="single-article__summary<?php echo $bnh_has_list_summary ? ' single-article__summary--collapsible' : ''; ?>" aria-labelledby="article-summary-heading">
				<h2 id="article-summary-heading" class="single-article__section-title"><?php esc_html_e( 'Article Summary', 'bnh-core' ); ?></h2>
				<div class="single-article__summary-content">
					<?php echo wp_kses_post( $bnh_summary_markup ); ?>
				</div>
				<?php if ( $bnh_has_list_summary ) : ?>
					<button class="single-article__summary-toggle" type="button" aria-expanded="false"><?php esc_html_e( 'Read Full Summary', 'bnh-core' ); ?> &#8595;</button>
				<?php endif; ?>
			</section>
		<?php endif; ?>
	</header>

	<?php if ( ! empty( $bnh_toc['items'] ) ) : ?>
		<nav class="single-article__toc" aria-labelledby="article-contents-heading">
			<h2 id="article-contents-heading" class="single-article__section-title"><?php esc_html_e( 'Article Contents', 'bnh-core' ); ?></h2>
			<ul class="single-article__toc-list">
				<?php foreach ( $bnh_toc['items'] as $bnh_toc_item ) : ?>
					<li class="single-article__toc-item">
						<a href="#<?php echo esc_attr( $bnh_toc_item['id'] ); ?>"><?php echo esc_html( $bnh_toc_item['title'] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
	<?php endif; ?>

	<div class="entry-content">
		<?php echo $bnh_toc['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bnh-core' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>

	<?php if ( '' !== $bnh_sources ) : ?>
		<section id="article-sources" class="single-article__sources" aria-labelledby="article-sources-heading">
			<h2 class="sr-only"><?php esc_html_e( 'Article Sources', 'bnh-core' ); ?></h2>
			<button id="article-sources-heading" class="single-article__sources-toggle" type="button" aria-expanded="false">
				<?php esc_html_e( 'Sources', 'bnh-core' ); ?>
			</button>
			<div class="single-article__sources-content">
				<?php echo wp_kses_post( $bnh_sources ); ?>
			</div>
		</section>
	<?php endif; ?>
</article>
