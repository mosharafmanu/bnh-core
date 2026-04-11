<?php
/**
 * Leading doctors flexible content section.
 *
 * @package BNH_Core
 */

$bnh_heading = (string) get_sub_field( 'heading' );
$bnh_intro   = (string) get_sub_field( 'intro_content' );
$bnh_items   = get_sub_field( 'doctor_items' );

if ( '' === $bnh_heading ) {
	$bnh_heading = __( 'Built for Long-Term Health. Endorsed by Leading Doctors.', 'bnh-core' );
}
?>

<section class="leading-doctors mt-50 mt-md-70 mt-lg-100 layout-padding">
	<div class="leading-doctors__inner">
		<header class="leading-doctors__header">
			<h2 class="section-title leading-doctors__title"><?php echo esc_html( $bnh_heading ); ?></h2>

			<?php if ( '' !== $bnh_intro ) : ?>
				<div class="leading-doctors__intro">
					<?php echo wp_kses_post( $bnh_intro ); ?>
				</div>
			<?php endif; ?>
		</header>

		<?php if ( is_array( $bnh_items ) && ! empty( $bnh_items ) ) : ?>
			<div class="leading-doctors__grid">
				<?php foreach ( $bnh_items as $bnh_item ) : ?>
					<?php
					$bnh_image = isset( $bnh_item['image'] ) && is_array( $bnh_item['image'] ) ? $bnh_item['image'] : array();
					$bnh_name  = isset( $bnh_item['name'] ) ? (string) $bnh_item['name'] : '';
					$bnh_role  = isset( $bnh_item['role'] ) ? (string) $bnh_item['role'] : '';

					if ( '' === $bnh_name && empty( $bnh_image ) && '' === $bnh_role ) {
						continue;
					}
					?>

					<article class="leading-doctors__card">
						<?php if ( ! empty( $bnh_image ) ) : ?>
							<div class="leading-doctors__media">
								<?php
								if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
									bnh_core_render_responsive_picture(
										$bnh_image,
										array(
											'class'      => 'leading-doctors__image',
											'alt'        => $bnh_image['alt'] ?? $bnh_name,
											'sizes'      => '(max-width: 767px) 100vw, (max-width: 1199px) 50vw, 25vw',
											'size_group' => 'card-4col',
										)
									);
								}
								?>
							</div>
						<?php endif; ?>

						<div class="leading-doctors__content">
							<?php if ( '' !== $bnh_name ) : ?>
								<h3 class="leading-doctors__name"><?php echo esc_html( $bnh_name ); ?></h3>
							<?php endif; ?>

							<?php if ( '' !== $bnh_role ) : ?>
								<div class="leading-doctors__role">
									<?php echo wp_kses_post( $bnh_role ); ?>
								</div>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
