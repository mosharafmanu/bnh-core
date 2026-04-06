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

if ( empty( $bnh_parents ) ) {
	return;
}
?>

<nav class="topic-parent-nav" aria-label="<?php esc_attr_e( 'Health topics', 'bnh-core' ); ?>">
	<ul class="topic-parent-nav__list">
		<?php foreach ( $bnh_parents as $bnh_parent ) : ?>
			<?php
			$is_active = isset( $bnh_context['active_parent'] ) && $bnh_context['active_parent'] instanceof WP_Term && (int) $bnh_context['active_parent']->term_id === (int) $bnh_parent->term_id;
			$is_homepage_prostate = is_front_page() && 'prostate-health' === $bnh_parent->slug;
			$topic_url = function_exists( 'bnh_get_health_topic_term_url' ) ? bnh_get_health_topic_term_url( $bnh_parent ) : get_term_link( $bnh_parent );

			if ( ! is_front_page() && 'prostate-health' === $bnh_parent->slug ) {
				$topic_url = home_url( '/' );
			}

			if ( ! $is_homepage_prostate && ( is_wp_error( $topic_url ) || empty( $topic_url ) ) ) {
				continue;
			}
			?>
			<li class="topic-parent-nav__item">
				<?php if ( $is_homepage_prostate ) : ?>
					<span class="topic-parent-nav__link<?php echo $is_active ? ' is-active' : ''; ?>"<?php echo $is_active ? ' aria-current="page"' : ''; ?>>
						<?php echo esc_html( $bnh_parent->name ); ?>
					</span>
				<?php else : ?>
					<a class="topic-parent-nav__link<?php echo $is_active ? ' is-active' : ''; ?>" href="<?php echo esc_url( $topic_url ); ?>"<?php echo $is_active ? ' aria-current="page"' : ''; ?>>
						<?php echo esc_html( $bnh_parent->name ); ?>
					</a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
