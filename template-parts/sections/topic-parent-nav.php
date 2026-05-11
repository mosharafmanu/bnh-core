<?php
/**
 * Parent topic navigation.
 *
 * @package BNH_Core
 */

$bnh_context = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();

if ( empty( $bnh_context ) && function_exists( 'bnh_get_health_topic_context' ) ) {
	$bnh_context = bnh_get_health_topic_context();
}

$bnh_parents = isset( $bnh_context['parent_terms'] ) && is_array( $bnh_context['parent_terms'] ) ? $bnh_context['parent_terms'] : array();
$bnh_active_parent = isset( $bnh_context['active_parent'] ) && $bnh_context['active_parent'] instanceof WP_Term ? $bnh_context['active_parent'] : null;

if ( ! ( $bnh_active_parent instanceof WP_Term ) && isset( $bnh_parents[0] ) && $bnh_parents[0] instanceof WP_Term ) {
	$bnh_active_parent = $bnh_parents[0];
}

$bnh_active_parent_color = $bnh_active_parent instanceof WP_Term && function_exists( 'bnh_core_get_health_topic_color_value' ) ? bnh_core_get_health_topic_color_value( $bnh_active_parent ) : '';

if ( empty( $bnh_parents ) ) {
	return;
}
?>

<nav class="topic-parent-nav layout-padding" aria-label="<?php esc_attr_e( 'Health topics', 'bnh-core' ); ?>"<?php echo ! empty( $bnh_active_parent_color ) ? ' style="--topic-parent-mobile-active-bg: ' . esc_attr( $bnh_active_parent_color ) . ';"' : ''; ?>>
	<div class="topic-parent-nav__inner bens-container">
	<?php if ( $bnh_active_parent instanceof WP_Term ) : ?>
		<div class="topic-parent-nav__mobile-selector">
			<button class="topic-parent-nav__mobile-toggle" type="button" aria-expanded="false" aria-controls="topic-parent-nav-mobile-list">
				<span class="topic-parent-nav__mobile-toggle-line"></span>
				<span class="topic-parent-nav__mobile-toggle-line"></span>
				<span class="topic-parent-nav__mobile-toggle-line"></span>
			</button>
			<span class="topic-parent-nav__mobile-current"<?php echo ! empty( $bnh_active_parent_color ) ? ' style="background-color: ' . esc_attr( $bnh_active_parent_color ) . ';"' : ''; ?>>
				<?php echo esc_html( $bnh_active_parent->name ); ?>
			</span>
		</div>

		<ul id="topic-parent-nav-mobile-list" class="topic-parent-nav__mobile-list">
			<?php foreach ( $bnh_parents as $bnh_parent ) : ?>
				<?php
				if ( (int) $bnh_active_parent->term_id === (int) $bnh_parent->term_id ) {
					continue;
				}

				$topic_url = function_exists( 'bnh_get_health_topic_term_url' ) ? bnh_get_health_topic_term_url( $bnh_parent ) : get_term_link( $bnh_parent );

				if ( ! is_front_page() && 'prostate-health' === $bnh_parent->slug ) {
					$topic_url = home_url( '/' );
				}

				if ( is_wp_error( $topic_url ) || empty( $topic_url ) ) {
					continue;
				}
				?>
				<li class="topic-parent-nav__mobile-item">
					<a class="topic-parent-nav__mobile-link" href="<?php echo esc_url( $topic_url ); ?>">
						<?php echo esc_html( $bnh_parent->name ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<ul class="topic-parent-nav__list">
		<?php foreach ( $bnh_parents as $bnh_parent ) : ?>
			<?php
			$is_active = ! is_search() && ! is_author() && $bnh_active_parent instanceof WP_Term && (int) $bnh_active_parent->term_id === (int) $bnh_parent->term_id;
			$is_homepage_prostate = is_front_page() && 'prostate-health' === $bnh_parent->slug;
			$topic_url = function_exists( 'bnh_get_health_topic_term_url' ) ? bnh_get_health_topic_term_url( $bnh_parent ) : get_term_link( $bnh_parent );
			$parent_color_value = function_exists( 'bnh_core_get_health_topic_color_value' ) ? bnh_core_get_health_topic_color_value( $bnh_parent ) : '';
			$active_style = $is_active && ! empty( $parent_color_value ) ? sprintf( ' style="--topic-link-bg: %1$s; color: var(--bhn-white);"', esc_attr( $parent_color_value ) ) : '';

			if ( ! is_front_page() && 'prostate-health' === $bnh_parent->slug ) {
				$topic_url = home_url( '/' );
			}

			if ( ! $is_homepage_prostate && ( is_wp_error( $topic_url ) || empty( $topic_url ) ) ) {
				continue;
			}
			?>
			<li class="topic-parent-nav__item">
				<?php if ( $is_homepage_prostate ) : ?>
					<span class="topic-parent-nav__link<?php echo $is_active ? ' is-active' : ''; ?>"<?php echo $is_active ? ' aria-current="page"' : ''; ?><?php echo $active_style; ?>>
						<?php echo esc_html( $bnh_parent->name ); ?>
					</span>
				<?php else : ?>
					<a class="topic-parent-nav__link<?php echo $is_active ? ' is-active' : ''; ?>" href="<?php echo esc_url( $topic_url ); ?>"<?php echo $is_active ? ' aria-current="page"' : ''; ?><?php echo $active_style; ?>>
						<?php echo esc_html( $bnh_parent->name ); ?>
					</a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
	</div>
</nav>
