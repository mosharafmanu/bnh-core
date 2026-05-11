<?php
/**
 * Author archive template.
 *
 * @package BNH_Core
 */

get_header();

$bnh_author = get_queried_object();

if ( ! ( $bnh_author instanceof WP_User ) ) {
	get_footer();
	return;
}

$bnh_author_id          = (int) $bnh_author->ID;
$bnh_display_name       = $bnh_author->display_name;
$bnh_job_title          = function_exists( 'get_field' ) ? (string) get_field( 'job_title', 'user_' . $bnh_author_id ) : '';
$bnh_about_content      = (string) get_the_author_meta( 'description', $bnh_author_id );
$bnh_more_info          = function_exists( 'get_field' ) ? (string) get_field( 'author_bio_more_info', 'user_' . $bnh_author_id ) : '';
$bnh_education          = function_exists( 'get_field' ) ? (string) get_field( 'education', 'user_' . $bnh_author_id ) : '';
$bnh_licenses           = function_exists( 'get_field' ) ? (string) get_field( 'licenses', 'user_' . $bnh_author_id ) : '';
$bnh_expertise          = function_exists( 'get_field' ) ? (string) get_field( 'expertise', 'user_' . $bnh_author_id ) : '';
$bnh_linkedin_url       = (string) get_the_author_meta( 'linkedin', $bnh_author_id );
$bnh_youtube_url        = (string) get_the_author_meta( 'youtube', $bnh_author_id );

ob_start();
get_template_part( 'assets/svgs/arrow-left' );
$bnh_prev_icon = trim( ob_get_clean() );

ob_start();
get_template_part( 'assets/svgs/arrow-right' );
$bnh_next_icon = trim( ob_get_clean() );

if ( ! function_exists( 'bnh_core_render_author_inline_icon' ) ) {
	/**
	 * Render social icons for author profile links.
	 *
	 * @param string $icon Icon slug.
	 * @return string
	 */
	function bnh_core_render_author_inline_icon( $icon ) {
		$template_parts = array(
			'linkedin' => 'assets/svgs/linkedin',
			'youtube'  => 'assets/svgs/youtube',
		);

		if ( ! isset( $template_parts[ $icon ] ) ) {
			return '';
		}

		ob_start();
		get_template_part( $template_parts[ $icon ] );

		return trim( ob_get_clean() );
	}
}

if ( ! function_exists( 'bnh_core_render_author_social_links' ) ) {
	/**
	 * Render social links for author profile.
	 *
	 * @param string $linkedin_url LinkedIn profile URL.
	 * @param string $youtube_url  YouTube profile URL.
	 * @param string $modifier     Optional BEM modifier.
	 * @return void
	 */
	function bnh_core_render_author_social_links( $linkedin_url, $youtube_url, $modifier = '' ) {
		if ( '' === $linkedin_url && '' === $youtube_url ) {
			return;
		}

		$class_name = 'author-archive__social';

		if ( '' !== $modifier ) {
			$class_name .= ' author-archive__social--' . sanitize_html_class( $modifier );
		}
		?>
		<div class="<?php echo esc_attr( $class_name ); ?>">
			<?php if ( '' !== $linkedin_url ) : ?>
				<a class="author-archive__social-link author-archive__social-link--linkedin" href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'bnh-core' ); ?>">
					<?php echo bnh_core_render_author_inline_icon( 'linkedin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			<?php endif; ?>
			<?php if ( '' !== $youtube_url ) : ?>
				<a class="author-archive__social-link author-archive__social-link--youtube" href="<?php echo esc_url( $youtube_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'YouTube', 'bnh-core' ); ?>">
					<?php echo bnh_core_render_author_inline_icon( 'youtube' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
?>

<main id="primary" class="site-main author-archive-page">
	<div class="author-archive layout-padding mt-50 mt-md-70 mt-lg-100">
		<div class="author-archive__grid bens-container">
			<div class="author-archive__profile-column">
				<header class="author-archive__profile-header">
					<h1 class="page-title author-archive__name"><?php echo esc_html( $bnh_display_name ); ?></h1>
					<?php if ( '' !== $bnh_job_title ) : ?>
						<p class="author-archive__job-title"><?php echo esc_html( $bnh_job_title ); ?></p>
					<?php endif; ?>
					<?php bnh_core_render_author_social_links( $bnh_linkedin_url, $bnh_youtube_url, 'mobile' ); ?>
				</header>

				<section class="author-archive__summary" aria-labelledby="author-profile-heading">
					<h2 id="author-profile-heading" class="sr-only"><?php esc_html_e( 'Professional Profile', 'bnh-core' ); ?></h2>
					<div class="author-archive__media">
						<?php echo get_avatar( $bnh_author_id, 337, '', $bnh_display_name, array( 'class' => 'author-archive__avatar' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

						<?php bnh_core_render_author_social_links( $bnh_linkedin_url, $bnh_youtube_url, 'desktop' ); ?>
					</div>

					<div class="author-archive__credentials">
						<?php if ( '' !== $bnh_education ) : ?>
							<div class="author-archive__credential-block">
								<h3 class="author-archive__credential-title h5-style"><?php esc_html_e( 'Education:', 'bnh-core' ); ?></h3>
								<div class="author-archive__credential-content"><?php echo wp_kses_post( $bnh_education ); ?></div>
							</div>
						<?php endif; ?>

						<?php if ( '' !== $bnh_licenses ) : ?>
							<div class="author-archive__credential-block">
								<h3 class="author-archive__credential-title h5-style"><?php esc_html_e( 'Licenses and Certifications:', 'bnh-core' ); ?></h3>
								<div class="author-archive__credential-content"><?php echo wp_kses_post( $bnh_licenses ); ?></div>
							</div>
						<?php endif; ?>

						<?php if ( '' !== $bnh_expertise ) : ?>
							<div class="author-archive__credential-block">
								<h3 class="author-archive__credential-title h5-style"><?php esc_html_e( 'Expertise:', 'bnh-core' ); ?></h3>
								<div class="author-archive__credential-content"><?php echo wp_kses_post( $bnh_expertise ); ?></div>
							</div>
						<?php endif; ?>
					</div>
				</section>

				<?php if ( '' !== $bnh_about_content ) : ?>
					<section class="author-archive__content-block" aria-labelledby="author-about-heading">
						<h2 id="author-about-heading" class="author-archive__content-title h5-style"><?php esc_html_e( 'About', 'bnh-core' ); ?></h2>
						<div class="author-archive__content"><?php echo wpautop( esc_html( $bnh_about_content ) ); ?></div>
					</section>
				<?php endif; ?>

				<?php if ( '' !== $bnh_more_info ) : ?>
					<section class="author-archive__content-block" aria-label="<?php esc_attr_e( 'Additional author information', 'bnh-core' ); ?>">
						<div class="author-archive__content"><?php echo wp_kses_post( $bnh_more_info ); ?></div>
					</section>
				<?php endif; ?>
			</div>

			<section class="author-archive__articles topic-latest-articles" aria-labelledby="author-articles-heading">
				<header class="author-archive__articles-header">
					<h2 id="author-articles-heading" class="page-title"><?php echo esc_html( $bnh_display_name . ' ' . __( 'Articles', 'bnh-core' ) ); ?></h2>
				</header>

				<?php if ( have_posts() ) : ?>
					<div class="author-archive__posts topic-latest-articles__items">
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

					<div class="topic-latest-articles__scroll-ui" aria-hidden="true">
						<button class="topic-latest-articles__scroll-button topic-latest-articles__scroll-button--prev" type="button" aria-label="<?php esc_attr_e( 'Scroll author articles left', 'bnh-core' ); ?>">
							<?php echo $bnh_prev_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
						<div class="topic-latest-articles__scrollbar">
							<div class="topic-latest-articles__scrollbar-progress"></div>
						</div>
						<button class="topic-latest-articles__scroll-button topic-latest-articles__scroll-button--next" type="button" aria-label="<?php esc_attr_e( 'Scroll author articles right', 'bnh-core' ); ?>">
							<?php echo $bnh_next_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
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
						<nav class="pagination" aria-label="<?php esc_attr_e( 'Author archive pagination', 'bnh-core' ); ?>">
							<?php echo $pagination_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</nav>
					<?php endif; ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</section>
		</div>
	</div>
</main>

<?php
get_footer();
