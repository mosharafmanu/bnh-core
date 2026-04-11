<?php
/**
 * BNH site settings helpers.
 *
 * Keeps global header and footer option access small and specific to the
 * current BNH theme requirements.
 *
 * @package BNH_Core
 */

if ( ! function_exists( 'bnh_core_get_site_setting' ) ) {
	/**
	 * Read an ACF option field safely.
	 *
	 * @param string $field_name Option field name.
	 * @return mixed
	 */
	function bnh_core_get_site_setting( $field_name ) {
		if ( ! function_exists( 'get_field' ) || empty( $field_name ) ) {
			return null;
		}

		return get_field( $field_name, 'options' );
	}
}

if ( ! function_exists( 'bnh_core_get_site_logo' ) ) {
	/**
	 * Get the main site logo.
	 *
	 * @return array|false
	 */
	function bnh_core_get_site_logo() {
		$logo = bnh_core_get_site_setting( 'site_logo' );
		return is_array( $logo ) ? $logo : false;
	}
}

if ( ! function_exists( 'bnh_core_render_site_logo' ) ) {
	/**
	 * Render the main site logo linked to the homepage.
	 *
	 * @param array $args Render arguments.
	 * @return string|void
	 */
	function bnh_core_render_site_logo( $args = array() ) {
		$logo = bnh_core_get_site_logo();

		if ( ! $logo ) {
			return;
		}

		$defaults = array(
			'class'      => 'site-logo',
			'alt'        => get_bloginfo( 'name' ),
			'link_class' => 'site-logo-link',
			'echo'       => true,
		);
		$args = wp_parse_args( $args, $defaults );

		ob_start();
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo esc_attr( $args['link_class'] ); ?>" rel="home">
			<?php
			if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
				bnh_core_render_responsive_picture(
					$logo,
					array(
						'class' => $args['class'],
						'alt'   => $args['alt'],
						'sizes' => '(max-width: 767px) 160px, 220px',
					)
				);
			} else {
				printf(
					'<img src="%1$s" alt="%2$s" class="%3$s" />',
					esc_url( $logo['url'] ?? '' ),
					esc_attr( $args['alt'] ),
					esc_attr( $args['class'] )
				);
			}
			?>
		</a>
		<?php
		$output = ob_get_clean();

		if ( $args['echo'] ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		return $output;
	}
}

if ( ! function_exists( 'bnh_core_get_footer_logo' ) ) {
	/**
	 * Get the footer logo, with fallback to the main site logo.
	 *
	 * @return array|false
	 */
	function bnh_core_get_footer_logo() {
		$logo = bnh_core_get_site_setting( 'footer_logo' );

		if ( is_array( $logo ) ) {
			return $logo;
		}

		return bnh_core_get_site_logo();
	}
}

if ( ! function_exists( 'bnh_core_render_footer_logo' ) ) {
	/**
	 * Render the footer logo linked to the homepage.
	 *
	 * @param array $args Render arguments.
	 * @return string|void
	 */
	function bnh_core_render_footer_logo( $args = array() ) {
		$logo = bnh_core_get_footer_logo();

		if ( ! $logo ) {
			return;
		}

		$defaults = array(
			'class'      => 'footer-logo',
			'alt'        => get_bloginfo( 'name' ),
			'link_class' => 'footer-logo-link',
			'echo'       => true,
		);
		$args = wp_parse_args( $args, $defaults );

		ob_start();
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo esc_attr( $args['link_class'] ); ?>" rel="home">
			<?php
			if ( function_exists( 'bnh_core_render_responsive_picture' ) ) {
				bnh_core_render_responsive_picture(
					$logo,
					array(
						'class' => $args['class'],
						'alt'   => $args['alt'],
						'sizes' => '(max-width: 767px) 220px, 320px',
					)
				);
			} else {
				printf(
					'<img src="%1$s" alt="%2$s" class="%3$s" />',
					esc_url( $logo['url'] ?? '' ),
					esc_attr( $args['alt'] ),
					esc_attr( $args['class'] )
				);
			}
			?>
		</a>
		<?php
		$output = ob_get_clean();

		if ( $args['echo'] ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		return $output;
	}
}

if ( ! function_exists( 'bnh_core_get_header_account_button' ) ) {
	/**
	 * Get the header account button link field.
	 *
	 * @return array|false
	 */
	function bnh_core_get_header_account_button() {
		$link = bnh_core_get_site_setting( 'header_account_button' );
		return is_array( $link ) ? $link : false;
	}
}

if ( ! function_exists( 'bnh_core_get_header_account_button_icon' ) ) {
	/**
	 * Get the header account button icon.
	 *
	 * @return array|false
	 */
	function bnh_core_get_header_account_button_icon() {
		$icon = bnh_core_get_site_setting( 'header_account_button_icon' );
		return is_array( $icon ) ? $icon : false;
	}
}

if ( ! function_exists( 'bnh_core_get_header_cart_button' ) ) {
	/**
	 * Get the header cart button link field.
	 *
	 * @return array|false
	 */
	function bnh_core_get_header_cart_button() {
		$link = bnh_core_get_site_setting( 'header_cart_button' );
		return is_array( $link ) ? $link : false;
	}
}

if ( ! function_exists( 'bnh_core_get_header_cart_button_icon' ) ) {
	/**
	 * Get the header cart button icon.
	 *
	 * @return array|false
	 */
	function bnh_core_get_header_cart_button_icon() {
		$icon = bnh_core_get_site_setting( 'header_cart_button_icon' );
		return is_array( $icon ) ? $icon : false;
	}
}

if ( ! function_exists( 'bnh_core_render_header_account_button' ) ) {
	/**
	 * Render the header account button link.
	 *
	 * @param array $args Render arguments.
	 * @return string|void
	 */
	function bnh_core_render_header_account_button( $args = array() ) {
		$link = bnh_core_get_header_account_button();

		if ( ! $link || empty( $link['url'] ) ) {
			return;
		}

		$defaults = array(
			'class'   => 'header-action-link header-action-link--account',
			'content' => '',
			'echo'    => true,
		);
		$args = wp_parse_args( $args, $defaults );

		$link_title = (string) ( $link['title'] ?? __( 'Account', 'bnh-core' ) );
		$icon       = function_exists( 'bnh_core_get_header_account_button_icon' ) ? bnh_core_get_header_account_button_icon() : false;
		$content    = $args['content'];

		if ( '' === $content && $icon && function_exists( 'bnh_core_render_icon' ) ) {
			$content = bnh_core_render_icon(
				$icon,
				array(
					'class' => 'header-action-link__icon',
					'alt'   => $link_title,
					'echo'  => false,
				)
			);
		}

		$link_html  = sprintf(
			'<a href="%1$s" class="%2$s"%3$s aria-label="%4$s">%5$s</a>',
			esc_url( $link['url'] ),
			esc_attr( $args['class'] ),
			'_blank' === ( $link['target'] ?? '' ) ? ' target="_blank" rel="noopener noreferrer"' : '',
			esc_attr( $link_title ),
			'' !== $content ? $content : esc_html( $link_title )
		);

		if ( $args['echo'] ) {
			echo $link_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		return $link_html;
	}
}

if ( ! function_exists( 'bnh_core_render_header_cart_button' ) ) {
	/**
	 * Render the header cart button link.
	 *
	 * @param array $args Render arguments.
	 * @return string|void
	 */
	function bnh_core_render_header_cart_button( $args = array() ) {
		$link = bnh_core_get_header_cart_button();

		if ( ! $link || empty( $link['url'] ) ) {
			return;
		}

		$defaults = array(
			'class'   => 'header-action-link header-action-link--cart',
			'content' => '',
			'echo'    => true,
		);
		$args = wp_parse_args( $args, $defaults );

		$link_title = (string) ( $link['title'] ?? __( 'Cart', 'bnh-core' ) );
		$icon       = function_exists( 'bnh_core_get_header_cart_button_icon' ) ? bnh_core_get_header_cart_button_icon() : false;
		$content    = $args['content'];

		if ( '' === $content && $icon && function_exists( 'bnh_core_render_icon' ) ) {
			$content = bnh_core_render_icon(
				$icon,
				array(
					'class' => 'header-action-link__icon',
					'alt'   => $link_title,
					'echo'  => false,
				)
			);
		}

		$link_html  = sprintf(
			'<a href="%1$s" class="%2$s"%3$s aria-label="%4$s">%5$s</a>',
			esc_url( $link['url'] ),
			esc_attr( $args['class'] ),
			'_blank' === ( $link['target'] ?? '' ) ? ' target="_blank" rel="noopener noreferrer"' : '',
			esc_attr( $link_title ),
			'' !== $content ? $content : esc_html( $link_title )
		);

		if ( $args['echo'] ) {
			echo $link_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		return $link_html;
	}
}

if ( ! function_exists( 'bnh_core_get_footer_phone_rows' ) ) {
	/**
	 * Get configured footer phone rows.
	 *
	 * @return array
	 */
	function bnh_core_get_footer_phone_rows() {
		$rows = bnh_core_get_site_setting( 'footer_phone_rows' );
		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'bnh_core_get_topic_community_heading' ) ) {
	/**
	 * Get reusable topic community heading.
	 *
	 * @return string
	 */
	function bnh_core_get_topic_community_heading() {
		return (string) bnh_core_get_site_setting( 'topic_community_heading' );
	}
}

if ( ! function_exists( 'bnh_core_get_topic_community_description' ) ) {
	/**
	 * Get reusable topic community description.
	 *
	 * @return string
	 */
	function bnh_core_get_topic_community_description() {
		return (string) bnh_core_get_site_setting( 'topic_community_description' );
	}
}

if ( ! function_exists( 'bnh_core_get_topic_community_embed_code' ) ) {
	/**
	 * Get reusable topic community embed code.
	 *
	 * @return string
	 */
	function bnh_core_get_topic_community_embed_code() {
		return (string) bnh_core_get_site_setting( 'topic_community_embed_code' );
	}
}

if ( ! function_exists( 'bnh_core_get_footer_social_links' ) ) {
	/**
	 * Get footer social links.
	 *
	 * @return array
	 */
	function bnh_core_get_footer_social_links() {
		$rows = bnh_core_get_site_setting( 'footer_social_links' );
		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'bnh_core_get_footer_social_heading' ) ) {
	/**
	 * Get footer social heading.
	 *
	 * @return string
	 */
	function bnh_core_get_footer_social_heading() {
		return (string) bnh_core_get_site_setting( 'footer_social_heading' );
	}
}

if ( ! function_exists( 'bnh_core_get_footer_disclaimer_heading' ) ) {
	/**
	 * Get disclaimer heading.
	 *
	 * @return string
	 */
	function bnh_core_get_footer_disclaimer_heading() {
		return (string) bnh_core_get_site_setting( 'footer_disclaimer_heading' );
	}
}

if ( ! function_exists( 'bnh_core_get_footer_disclaimer_content' ) ) {
	/**
	 * Get disclaimer content.
	 *
	 * @return string
	 */
	function bnh_core_get_footer_disclaimer_content() {
		return (string) bnh_core_get_site_setting( 'footer_disclaimer_content' );
	}
}

if ( ! function_exists( 'bnh_core_get_footer_copyright' ) ) {
	/**
	 * Get footer copyright text with {year} support.
	 *
	 * @return string
	 */
	function bnh_core_get_footer_copyright() {
		$copyright = (string) bnh_core_get_site_setting( 'footer_copyright' );

		if ( '' === $copyright ) {
			return '';
		}

		return str_replace( '{year}', gmdate( 'Y' ), $copyright );
	}
}

if ( ! function_exists( 'bnh_core_get_phone_href' ) ) {
	/**
	 * Build a tel: href from a phone number.
	 *
	 * @param string $phone Phone number.
	 * @return string
	 */
	function bnh_core_get_phone_href( $phone ) {
		$clean = preg_replace( '/[^0-9+]/', '', (string) $phone );
		return '' !== $clean ? 'tel:' . $clean : '';
	}
}

if ( ! function_exists( 'bnh_core_render_footer_phone_rows' ) ) {
	/**
	 * Render footer phone rows.
	 *
	 * @param array $args Render arguments.
	 * @return string|void
	 */
	function bnh_core_render_footer_phone_rows( $args = array() ) {
		$rows = bnh_core_get_footer_phone_rows();

		if ( empty( $rows ) ) {
			return;
		}

		$defaults = array(
			'container_class' => 'footer-phone-list',
			'item_class'      => 'footer-phone-list__item',
			'flag_class'      => 'footer-phone-list__flag',
			'text_class'      => 'footer-phone-list__text',
			'link_class'      => 'footer-phone-list__link',
			'echo'            => true,
		);
		$args = wp_parse_args( $args, $defaults );

		ob_start();
		?>
		<div class="<?php echo esc_attr( $args['container_class'] ); ?>">
			<?php foreach ( $rows as $row ) : ?>
				<?php
				$label        = (string) ( $row['phone_label'] ?? '' );
				$display      = (string) ( $row['phone_display'] ?? '' );
				$link_value   = (string) ( $row['phone_link'] ?? '' );
				$flag         = $row['phone_flag'] ?? null;
				$phone_href   = '' !== $link_value ? bnh_core_get_phone_href( $link_value ) : bnh_core_get_phone_href( $display );

				if ( '' === $label || '' === $display || '' === $phone_href ) {
					continue;
				}
				?>
				<div class="<?php echo esc_attr( $args['item_class'] ); ?>">
					<?php if ( is_array( $flag ) && ! empty( $flag['url'] ) ) : ?>
						<img class="<?php echo esc_attr( $args['flag_class'] ); ?>" src="<?php echo esc_url( $flag['url'] ); ?>" alt="<?php echo esc_attr( $flag['alt'] ?? $label ); ?>" />
					<?php endif; ?>
					<p class="<?php echo esc_attr( $args['text_class'] ); ?>">
						<?php echo esc_html( $label ); ?>
						<a class="<?php echo esc_attr( $args['link_class'] ); ?>" href="<?php echo esc_url( $phone_href ); ?>">
							<?php echo esc_html( $display ); ?>
						</a>
					</p>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		$output = ob_get_clean();

		if ( $args['echo'] ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		return $output;
	}
}

if ( ! function_exists( 'bnh_core_render_social_medias' ) ) {
	/**
	 * Render footer social links.
	 *
	 * @param array $args Render arguments.
	 * @return string|void
	 */
	function bnh_core_render_social_medias( $args = array() ) {
		$rows = bnh_core_get_footer_social_links();

		if ( empty( $rows ) ) {
			return;
		}

		$defaults = array(
			'list_class' => 'footer-social-list',
			'item_class' => 'footer-social-list__item',
			'link_class' => 'footer-social-list__link',
			'icon_class' => 'footer-social-list__icon',
			'label_class'=> 'footer-social-list__label',
			'echo'       => true,
		);
		$args = wp_parse_args( $args, $defaults );

		ob_start();
		?>
		<ul class="<?php echo esc_attr( $args['list_class'] ); ?>">
			<?php foreach ( $rows as $row ) : ?>
				<?php
				$label = (string) ( $row['social_label'] ?? '' );
				$link  = (string) ( $row['social_link'] ?? '' );
				$icon  = $row['social_icon'] ?? null;

				if ( '' === $label || '' === $link ) {
					continue;
				}
				?>
				<li class="<?php echo esc_attr( $args['item_class'] ); ?>">
					<a class="<?php echo esc_attr( $args['link_class'] ); ?>" href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer">
						<?php if ( is_array( $icon ) && ! empty( $icon['url'] ) ) : ?>
							<img class="<?php echo esc_attr( $args['icon_class'] ); ?>" src="<?php echo esc_url( $icon['url'] ); ?>" alt="<?php echo esc_attr( $icon['alt'] ?? $label ); ?>" />
						<?php endif; ?>
						<span class="<?php echo esc_attr( $args['label_class'] ); ?>"><?php echo esc_html( $label ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		$output = ob_get_clean();

		if ( $args['echo'] ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		return $output;
	}
}

if ( ! function_exists( 'bnh_core_render_footer_copyright' ) ) {
	/**
	 * Render footer copyright text.
	 *
	 * @param array $args Render arguments.
	 * @return string|void
	 */
	function bnh_core_render_footer_copyright( $args = array() ) {
		$copyright = bnh_core_get_footer_copyright();

		if ( '' === $copyright ) {
			return;
		}

		$defaults = array(
			'class' => 'footer-copyright-text',
			'echo'  => true,
		);
		$args = wp_parse_args( $args, $defaults );

		$output = sprintf(
			'<div class="%1$s"><p>%2$s</p></div>',
			esc_attr( $args['class'] ),
			esc_html( $copyright )
		);

		if ( $args['echo'] ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		return $output;
	}
}
