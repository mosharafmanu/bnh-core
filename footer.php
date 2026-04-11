<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BNH_Core
 */

?>
	</div><!-- #content -->
	<footer id="colophon" class="site-footer">
		<div class="site-footer__inner layout-padding">
			<div class="site-footer__top">
				<div class="site-footer__brand-column">
					<div class="site-footer__brand-box">
						<?php
						if ( function_exists( 'bnh_core_render_footer_logo' ) ) {
							bnh_core_render_footer_logo(
								array(
									'class' => 'footer-logo-image',
								)
							);
						}
						?>

						<?php
						if ( function_exists( 'bnh_core_render_footer_phone_rows' ) ) {
							bnh_core_render_footer_phone_rows();
						}
						?>
					</div>

					<div class="site-footer__social-box">
						<?php
						$bnh_social_heading = function_exists( 'bnh_core_get_footer_social_heading' ) ? bnh_core_get_footer_social_heading() : '';
						?>
						<?php if ( '' !== $bnh_social_heading ) : ?>
							<h3 class="site-footer__column-title footer-social-heading h5-style"><?php echo esc_html( $bnh_social_heading ); ?></h3>
						<?php endif; ?>

						<?php
						if ( function_exists( 'bnh_core_render_social_medias' ) ) {
							bnh_core_render_social_medias();
						}
						?>
					</div>
				</div>

				<div class="site-footer__content-column">
					<div class="site-footer__menu-columns">
						<div class="site-footer__menu-column">
							<h3 class="site-footer__column-title h5-style"><?php esc_html_e( 'Help', 'bnh-core' ); ?></h3>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footerHelpMenu',
									'container'      => false,
									'menu_class'     => 'footer-menu',
									'fallback_cb'    => false,
								)
							);
							?>
						</div>

						<div class="site-footer__menu-column">
							<h3 class="site-footer__column-title h5-style"><?php esc_html_e( 'Information', 'bnh-core' ); ?></h3>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footerInformationMenu',
									'container'      => false,
									'menu_class'     => 'footer-menu',
									'fallback_cb'    => false,
								)
							);
							?>
						</div>

						<div class="site-footer__menu-column">
							<h3 class="site-footer__column-title h5-style"><?php esc_html_e( 'Research', 'bnh-core' ); ?></h3>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footerResearchMenu',
									'container'      => false,
									'menu_class'     => 'footer-menu',
									'fallback_cb'    => false,
								)
							);
							?>
						</div>
					</div>

					<?php
					$bnh_disclaimer_heading = function_exists( 'bnh_core_get_footer_disclaimer_heading' ) ? bnh_core_get_footer_disclaimer_heading() : '';
					$bnh_disclaimer_content = function_exists( 'bnh_core_get_footer_disclaimer_content' ) ? bnh_core_get_footer_disclaimer_content() : '';
					?>

					<?php if ( '' !== $bnh_disclaimer_heading || '' !== $bnh_disclaimer_content ) : ?>
						<div class="site-footer__disclaimer">
							<?php if ( '' !== $bnh_disclaimer_heading ) : ?>
								<h3 class="site-footer__column-title h5-style"><?php echo esc_html( $bnh_disclaimer_heading ); ?></h3>
							<?php endif; ?>

							<?php if ( '' !== $bnh_disclaimer_content ) : ?>
								<div class="site-footer__disclaimer-content">
									<?php echo wp_kses_post( $bnh_disclaimer_content ); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="site-footer__bottom">
				<div class="site-footer__bottom-inner">
					<?php
					if ( function_exists( 'bnh_core_render_footer_copyright' ) ) {
						bnh_core_render_footer_copyright(
							array(
								'class' => 'site-footer__copyright',
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
