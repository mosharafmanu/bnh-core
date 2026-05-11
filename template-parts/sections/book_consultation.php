<?php
/**
 * Book consultation flexible content section.
 *
 * @package BNH_Core
 */

$bnh_heading = function_exists( 'bnh_core_get_book_consultation_heading' ) ? bnh_core_get_book_consultation_heading() : '';
$bnh_intro   = function_exists( 'bnh_core_get_book_consultation_intro_text' ) ? bnh_core_get_book_consultation_intro_text() : '';
$bnh_items   = function_exists( 'bnh_core_get_book_consultation_items' ) ? bnh_core_get_book_consultation_items() : array();
$bnh_button  = function_exists( 'bnh_core_get_book_consultation_button' ) ? bnh_core_get_book_consultation_button() : array();
$bnh_context = '';
$bnh_context_class = '';
$bnh_spacing_class = ' mt-50 mt-md-70';
$bnh_title_class   = 'section-title book-consultation__title h2-large';

if ( '' === $bnh_heading ) {
	$bnh_heading = __( 'BOOK YOUR 1-ON-1 CONSULTATION TODAY', 'bnh-core' );
}

if ( is_singular( 'post' ) ) {
	$bnh_context       = 'single';
	$bnh_context_class = 'book-consultation-section-context--single';
	$bnh_spacing_class = '';
	$bnh_title_class   = 'section-title book-consultation__title h2-small';
} elseif ( is_front_page() || is_home() ) {
	$bnh_context       = 'home';
	$bnh_context_class = 'book-consultation-section-context--home';
}

$bnh_image            = function_exists( 'bnh_core_get_book_consultation_image' ) ? bnh_core_get_book_consultation_image( $bnh_context ) : array();
$bnh_responsive_image = 'single' === $bnh_context && function_exists( 'bnh_core_get_book_consultation_image' ) ? bnh_core_get_book_consultation_image( 'home' ) : array();
$bnh_has_image        = is_array( $bnh_image ) && ( ! empty( $bnh_image['ID'] ) || ! empty( $bnh_image['url'] ) );
$bnh_has_responsive_image = is_array( $bnh_responsive_image ) && ( ! empty( $bnh_responsive_image['ID'] ) || ! empty( $bnh_responsive_image['url'] ) );

if (
	$bnh_has_image
	&& $bnh_has_responsive_image
	&& ( $bnh_image['ID'] ?? $bnh_image['url'] ?? '' ) === ( $bnh_responsive_image['ID'] ?? $bnh_responsive_image['url'] ?? '' )
) {
	$bnh_responsive_image = array();
	$bnh_has_responsive_image = false;
}

$bnh_primary_image_class = 'book-consultation__image-frame book-consultation__image-frame--primary media';

if ( $bnh_has_responsive_image ) {
	$bnh_primary_image_class .= ' book-consultation__image-frame--has-responsive';
}
?>

	<section class="book-consultation<?php echo esc_attr( $bnh_spacing_class ); ?> layout-padding<?php echo '' !== $bnh_context_class ? ' ' . esc_attr( $bnh_context_class ) : ''; ?>">
	<div class="book-consultation__inner bens-container">
		<header class="book-consultation__header">
			<h2 class="<?php echo esc_attr( $bnh_title_class ); ?>"><?php echo esc_html( $bnh_heading ); ?></h2>
		</header>

		<div class="book-consultation__grid">
			<div class="book-consultation__content">
				<?php if ( '' !== $bnh_intro ) : ?>
					<div class="book-consultation__intro">
						<?php echo wp_kses_post( $bnh_intro ); ?>
					</div>
				<?php endif; ?>

				<?php if ( is_array( $bnh_items ) && ! empty( $bnh_items ) ) : ?>
					<ul class="book-consultation__list">
						<?php $bnh_count = 1; ?>
						<?php foreach ( $bnh_items as $bnh_item ) : ?>
							<?php $bnh_item_text = isset( $bnh_item['item_text'] ) ? (string) $bnh_item['item_text'] : ''; ?>
							<?php if ( '' === $bnh_item_text ) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<li class="book-consultation__item">
								<div class="book-consultation__item-number h3-style"><?php echo esc_html( $bnh_count ); ?></div>
								<div class="book-consultation__item-text"><?php echo wp_kses_post( wpautop( $bnh_item_text ) ); ?></div>
							</li>
							<?php $bnh_count++; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( is_array( $bnh_button ) && ! empty( $bnh_button['url'] ) ) : ?>
					<div class="book-consultation__cta">
						<?php
						$bnh_calendly_url = 'https://calendly.com/bensnaturalhealth/consult';
						$bnh_button_label = ! empty( $bnh_button['title'] ) ? (string) $bnh_button['title'] : __( 'Book Your Free Consultation Now', 'bnh-core' );
						?>
						<a
							href="<?php echo esc_url( $bnh_calendly_url ); ?>"
							class="site-btn btn-secondary btn-radius book-consultation__button js-calendly-popup"
							data-calendly-url="<?php echo esc_url( $bnh_calendly_url ); ?>"
							target="_blank"
							rel="noopener"
						>
							<?php echo esc_html( $bnh_button_label ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $bnh_has_image ) : ?>
				<div class="<?php echo esc_attr( $bnh_primary_image_class ); ?>">
					<?php
					if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
						bnh_core_render_responsive_picture(
							$bnh_image,
							array(
								'class'      => 'book-consultation__image',
								'alt'        => $bnh_image['alt'] ?? $bnh_heading,
								'sizes'      => '(max-width: 991px) 100vw, 50vw',
								'size_group' => 'media-content',
							)
						);
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ( $bnh_has_responsive_image ) : ?>
				<div class="book-consultation__image-frame book-consultation__image-frame--responsive media">
					<?php
					if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
						bnh_core_render_responsive_picture(
							$bnh_responsive_image,
							array(
								'class'      => 'book-consultation__image',
								'alt'        => $bnh_responsive_image['alt'] ?? $bnh_heading,
								'sizes'      => '(max-width: 1399px) 100vw, 50vw',
								'size_group' => 'media-content',
							)
						);
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
