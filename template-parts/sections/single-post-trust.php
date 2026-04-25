<?php
/**
 * Single post trust section.
 *
 * @package BNH_Core
 */

$bnh_args            = isset( $args ) && is_array( $args ) ? $args : array();
$bnh_sources         = isset( $bnh_args['sources'] ) ? (string) $bnh_args['sources'] : '';
$bnh_update_history  = isset( $bnh_args['update_history'] ) && is_array( $bnh_args['update_history'] ) ? $bnh_args['update_history'] : array();
$bnh_review_heading  = function_exists( 'bnh_core_get_single_article_review_heading' ) ? bnh_core_get_single_article_review_heading() : '';
$bnh_review_content  = function_exists( 'bnh_core_get_single_article_review_content' ) ? bnh_core_get_single_article_review_content() : '';
$bnh_editorial_title = function_exists( 'bnh_core_get_single_article_editorial_heading' ) ? bnh_core_get_single_article_editorial_heading() : '';
$bnh_editorial_body  = function_exists( 'bnh_core_get_single_article_editorial_content' ) ? bnh_core_get_single_article_editorial_content() : '';
$bnh_disclaimer_title = function_exists( 'bnh_core_get_single_article_disclaimer_heading' ) ? bnh_core_get_single_article_disclaimer_heading() : '';
$bnh_disclaimer_body  = function_exists( 'bnh_core_get_single_article_disclaimer_content' ) ? bnh_core_get_single_article_disclaimer_content() : '';

if ( '' === trim( $bnh_review_heading . $bnh_review_content . $bnh_editorial_title . $bnh_editorial_body . $bnh_disclaimer_title . $bnh_disclaimer_body . $bnh_sources ) && empty( $bnh_update_history ) ) {
	return;
}
?>

<section class="single-article__trust">
	<div class="single-article__trust-items">
		<?php if ( '' !== trim( $bnh_review_heading . $bnh_review_content ) ) : ?>
			<section class="single-article__trust-item">
				<?php if ( '' !== $bnh_review_heading ) : ?>
					<h2 class="single-article__trust-title"><?php echo esc_html( $bnh_review_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( '' !== $bnh_review_content ) : ?>
					<div class="single-article__trust-content">
						<?php echo wp_kses_post( $bnh_review_content ); ?>
					</div>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php if ( '' !== trim( $bnh_editorial_title . $bnh_editorial_body ) ) : ?>
			<section class="single-article__trust-item">
				<?php if ( '' !== $bnh_editorial_title ) : ?>
					<h2 class="single-article__trust-title"><?php echo esc_html( $bnh_editorial_title ); ?></h2>
				<?php endif; ?>
				<?php if ( '' !== $bnh_editorial_body ) : ?>
					<div class="single-article__trust-content">
						<?php echo wp_kses_post( $bnh_editorial_body ); ?>
					</div>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php if ( '' !== trim( $bnh_disclaimer_title . $bnh_disclaimer_body ) ) : ?>
			<section class="single-article__trust-item">
				<?php if ( '' !== $bnh_disclaimer_title ) : ?>
					<h2 class="single-article__trust-title"><?php echo esc_html( $bnh_disclaimer_title ); ?></h2>
				<?php endif; ?>
				<?php if ( '' !== $bnh_disclaimer_body ) : ?>
					<div class="single-article__trust-content">
						<?php echo wp_kses_post( $bnh_disclaimer_body ); ?>
					</div>
				<?php endif; ?>
			</section>
		<?php endif; ?>
	</div>

	<div class="single-article__trust-toggles">
		<?php if ( '' !== $bnh_sources ) : ?>
			<section id="article-sources" class="single-article__sources single-article__disclosure" aria-labelledby="article-sources-heading">
				<h2 class="sr-only"><?php esc_html_e( 'Article Sources', 'bnh-core' ); ?></h2>
				<button id="article-sources-heading" class="single-article__sources-toggle single-article__disclosure-toggle" type="button" aria-expanded="false">
					<?php esc_html_e( 'Sources', 'bnh-core' ); ?>
				</button>
				<div class="single-article__sources-content single-article__disclosure-content">
					<?php echo wp_kses_post( $bnh_sources ); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( ! empty( $bnh_update_history ) ) : ?>
			<section class="single-article__update-history single-article__disclosure" aria-labelledby="article-update-history-heading">
				<h2 class="sr-only"><?php esc_html_e( 'Article Update History', 'bnh-core' ); ?></h2>
				<button id="article-update-history-heading" class="single-article__update-history-toggle single-article__disclosure-toggle" type="button" aria-expanded="false">
					<?php esc_html_e( 'Update History', 'bnh-core' ); ?>
				</button>
				<div class="single-article__update-history-content single-article__disclosure-content">
					<?php foreach ( $bnh_update_history as $bnh_history_item ) : ?>
						<?php $bnh_person = isset( $bnh_history_item['person'] ) && is_array( $bnh_history_item['person'] ) ? $bnh_history_item['person'] : null; ?>
						<div class="single-article__update-history-item">
							<?php if ( ! empty( $bnh_history_item['heading'] ) ) : ?>
								<h3 class="single-article__update-history-title"><?php echo esc_html( $bnh_history_item['heading'] ); ?></h3>
							<?php endif; ?>

							<?php if ( $bnh_person && ! empty( $bnh_person['name'] ) ) : ?>
								<p class="single-article__update-history-byline">
									<?php echo esc_html( (string) ( $bnh_history_item['label'] ?? '' ) ); ?>
									<?php echo ' '; ?>
									<a href="<?php echo esc_url( $bnh_person['url'] ); ?>"><?php echo esc_html( $bnh_person['name'] ); ?></a>
									<?php if ( ! empty( $bnh_person['job_title'] ) ) : ?>
										<?php echo esc_html( ' - ' . $bnh_person['job_title'] ); ?>
									<?php endif; ?>
								</p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>
	</div>
</section>
