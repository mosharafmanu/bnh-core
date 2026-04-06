<?php
/**
 * Child topic navigation.
 *
 * @package BNH_Core
 */

$bnh_context = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();

if ( empty( $bnh_context ) && function_exists( 'bnh_get_health_topic_context' ) ) {
	$bnh_context = bnh_get_health_topic_context();
}

$bnh_parent  = isset( $bnh_context['active_parent'] ) && $bnh_context['active_parent'] instanceof WP_Term ? $bnh_context['active_parent'] : null;
$bnh_child   = isset( $bnh_context['active_child'] ) && $bnh_context['active_child'] instanceof WP_Term ? $bnh_context['active_child'] : null;
$bnh_terms   = isset( $bnh_context['child_terms'] ) && is_array( $bnh_context['child_terms'] ) ? $bnh_context['child_terms'] : array();

if ( ! ( $bnh_parent instanceof WP_Term ) || empty( $bnh_terms ) ) {
	return;
}
?>

<nav class="topic-child-nav" aria-label="<?php esc_attr_e( 'Child topics', 'bnh-core' ); ?>">
	<ul class="topic-child-nav__list">
		<?php foreach ( $bnh_terms as $bnh_term ) : ?>
			<?php
			$is_active = $bnh_child instanceof WP_Term && (int) $bnh_child->term_id === (int) $bnh_term->term_id;
			$topic_url = function_exists( 'bnh_get_health_topic_term_url' ) ? bnh_get_health_topic_term_url( $bnh_term ) : get_term_link( $bnh_term );

			if ( is_wp_error( $topic_url ) || empty( $topic_url ) ) {
				continue;
			}
			?>
			<li class="topic-child-nav__item">
				<a
					class="topic-child-nav__link<?php echo $is_active ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( $topic_url ); ?>"
					data-parent-slug="<?php echo esc_attr( $bnh_parent->slug ); ?>"
					data-child-slug="<?php echo esc_attr( $bnh_term->slug ); ?>"
					<?php echo $is_active ? ' aria-current="page"' : ''; ?>
				>
					<?php echo esc_html( $bnh_term->name ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
