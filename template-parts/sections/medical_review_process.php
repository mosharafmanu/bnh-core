<?php
/**
 * Medical review process flexible content section.
 *
 * @package BNH_Core
 */

$bnh_heading = (string) get_sub_field( 'heading' );
$bnh_items   = get_sub_field( 'process_items' );
$bnh_button  = get_sub_field( 'button' );

if ( '' === $bnh_heading ) {
	$bnh_heading = __( 'How Our Medical Review Process Works', 'bnh-core' );
}
?>

<section class="medical-review-process mt-50 layout-padding">
	<div class="medical-review-process__inner">
		<header class="medical-review-process__header">
			<h2 class="section-title medical-review-process__title h2-small"><?php echo esc_html( $bnh_heading ); ?></h2>
		</header>

		<?php if ( is_array( $bnh_items ) && ! empty( $bnh_items ) ) : ?>
			<div class="medical-review-process__panel">
				<div class="medical-review-process__grid">
					<?php foreach ( $bnh_items as $bnh_item ) : ?>
						<?php
						$bnh_icon        = isset( $bnh_item['icon'] ) && is_array( $bnh_item['icon'] ) ? $bnh_item['icon'] : array();
						$bnh_item_title  = isset( $bnh_item['title'] ) ? (string) $bnh_item['title'] : '';
						$bnh_description = isset( $bnh_item['description'] ) ? (string) $bnh_item['description'] : '';

						if ( '' === $bnh_item_title && empty( $bnh_icon ) && '' === $bnh_description ) {
							continue;
						}
						?>

						<article class="medical-review-process__item">
							<?php if ( ! empty( $bnh_icon ) ) : ?>
								<div class="medical-review-process__icon">
									<?php
									if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
										bnh_core_render_responsive_picture(
											$bnh_icon,
											array(
												'class'      => 'medical-review-process__icon-image',
												'alt'        => $bnh_icon['alt'] ?? $bnh_item_title,
												'sizes'      => '120px',
												'size_group' => 'small',
											)
										);
									}
									?>
								</div>
							<?php endif; ?>

							<?php if ( '' !== $bnh_item_title ) : ?>
								<h3 class="medical-review-process__item-title"><?php echo esc_html( $bnh_item_title ); ?></h3>
							<?php endif; ?>

							<?php if ( '' !== $bnh_description ) : ?>
								<div class="medical-review-process__item-description">
									<?php echo wp_kses_post( $bnh_description ); ?>
								</div>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( is_array( $bnh_button ) && ! empty( $bnh_button['url'] ) ) : ?>
			<div class="medical-review-process__cta">
				<?php
				if ( function_exists( 'bnh_core_render_button' ) ) {
					bnh_core_render_button(
						$bnh_button,
						array(
							'class' => 'site-btn btn-primary medical-review-process__button',
						)
					);
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
