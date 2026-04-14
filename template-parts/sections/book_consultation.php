<?php
/**
 * Book consultation flexible content section.
 *
 * @package BNH_Core
 */

$bnh_heading = (string) get_sub_field( 'heading' );
$bnh_intro   = (string) get_sub_field( 'intro_text' );
$bnh_items   = get_sub_field( 'consultation_items' );
$bnh_button  = get_sub_field( 'button' );
$bnh_image   = get_sub_field( 'image' );

if ( '' === $bnh_heading ) {
	$bnh_heading = __( 'BOOK YOUR 1-ON-1 CONSULTATION TODAY', 'bnh-core' );
}
?>

<section class="book-consultation mt-50 mt-md-70 layout-padding">
	<div class="book-consultation__inner">
		<header class="book-consultation__header">
			<h2 class="section-title book-consultation__title"><?php echo esc_html( $bnh_heading ); ?></h2>
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
						if ( function_exists( 'bnh_core_render_button' ) ) {
							bnh_core_render_button(
								$bnh_button,
								array(
									'class' => 'site-btn btn-secondary btn-radius book-consultation__button',
								)
							);
						}
						?>
					</div>
				<?php endif; ?>
			</div>

			<div class="book-consultation__media">
				<?php if ( is_array( $bnh_image ) ) : ?>
					<div class="book-consultation__image-frame media">
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
			</div>
		</div>
	</div>
</section>
