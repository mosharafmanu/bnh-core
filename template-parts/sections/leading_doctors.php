<?php
/**
 * Leading doctors flexible content section.
 *
 * @package BNH_Core
 */

$bnh_heading = (string) get_sub_field( 'heading' );
$bnh_intro   = (string) get_sub_field( 'intro_content' );
$bnh_source  = (string) get_sub_field( 'data_source' );
$bnh_items   = array();

if ( '' === $bnh_heading ) {
	$bnh_heading = __( 'Built for Long-Term Health. Endorsed by Leading Doctors.', 'bnh-core' );
}

if ( 'dynamic' === $bnh_source ) {
	$bnh_selected_users = get_sub_field( 'doctor_users' );

	if ( is_array( $bnh_selected_users ) ) {
		foreach ( $bnh_selected_users as $bnh_user ) {
			if ( is_numeric( $bnh_user ) ) {
				$bnh_user = get_user_by( 'id', (int) $bnh_user );
			}

			if ( ! ( $bnh_user instanceof WP_User ) ) {
				continue;
			}

			$bnh_user_id  = (int) $bnh_user->ID;
			$bnh_user_job = function_exists( 'get_field' ) ? (string) get_field( 'job_title', 'user_' . $bnh_user_id ) : '';
			$bnh_avatar   = get_avatar_url( $bnh_user_id, array( 'size' => 480 ) );
			$bnh_link     = get_author_posts_url( $bnh_user_id );

			$bnh_items[] = array(
				'image_url' => $bnh_avatar,
				'name'      => $bnh_user->display_name,
				'role'      => $bnh_user_job,
				'link_url'  => $bnh_link,
			);
		}
	}
} else {
	$bnh_items = get_sub_field( 'doctor_items' );
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
					$bnh_image     = isset( $bnh_item['image'] ) && is_array( $bnh_item['image'] ) ? $bnh_item['image'] : array();
					$bnh_image_url = isset( $bnh_item['image_url'] ) ? (string) $bnh_item['image_url'] : '';
					$bnh_name      = isset( $bnh_item['name'] ) ? (string) $bnh_item['name'] : '';
					$bnh_role      = isset( $bnh_item['role'] ) ? (string) $bnh_item['role'] : '';
					$bnh_link      = isset( $bnh_item['link'] ) && is_array( $bnh_item['link'] ) ? $bnh_item['link'] : array();
					$bnh_link_url  = isset( $bnh_item['link_url'] ) ? (string) $bnh_item['link_url'] : '';
					$bnh_link_href = '';
					$bnh_link_text = '';
					$bnh_link_target = '';

					if ( isset( $bnh_link['url'] ) ) {
						$bnh_link_url = (string) $bnh_link['url'];
					}

					if ( isset( $bnh_link['title'] ) ) {
						$bnh_link_text = (string) $bnh_link['title'];
					}

					if ( isset( $bnh_link['target'] ) ) {
						$bnh_link_target = (string) $bnh_link['target'];
					}

					if ( '' !== $bnh_link_url ) {
						$bnh_link_href = esc_url( $bnh_link_url );
					}

					if ( '' === $bnh_name && empty( $bnh_image ) && '' === $bnh_image_url && '' === $bnh_role ) {
						continue;
					}
					?>

					<article class="leading-doctors__card">
						<?php if ( '' !== $bnh_link_href ) : ?>
							<a
								class="leading-doctors__link"
								href="<?php echo $bnh_link_href; ?>"
								<?php echo '' !== $bnh_link_target ? ' target="' . esc_attr( $bnh_link_target ) . '"' : ''; ?>
								<?php echo '_blank' === $bnh_link_target ? ' rel="noopener noreferrer"' : ''; ?>
								aria-label="<?php echo esc_attr( '' !== $bnh_link_text ? $bnh_link_text : $bnh_name ); ?>"
							>
						<?php endif; ?>

						<?php if ( ! empty( $bnh_image ) || '' !== $bnh_image_url ) : ?>
							<div class="leading-doctors__media media">
								<?php
								if ( ! empty( $bnh_image ) && function_exists( 'bnh_core_render_responsive_picture' ) ) {
									bnh_core_render_responsive_picture(
										$bnh_image,
										array(
											'class'      => 'leading-doctors__image',
											'alt'        => $bnh_image['alt'] ?? $bnh_name,
											'sizes'      => '(max-width: 767px) 100vw, (max-width: 1199px) 50vw, 25vw',
											'size_group' => 'card-4col',
										)
									);
								} elseif ( '' !== $bnh_image_url ) {
									?>
									<img class="leading-doctors__image" src="<?php echo esc_url( $bnh_image_url ); ?>" alt="<?php echo esc_attr( $bnh_name ); ?>">
									<?php
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

						<?php if ( '' !== $bnh_link_href ) : ?>
							</a>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
