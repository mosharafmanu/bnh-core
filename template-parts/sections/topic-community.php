<?php
/**
 * Topic community placeholder.
 *
 * @package BNH_Core
 */

$bnh_context   = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();
$active_parent = $bnh_context['active_parent'] ?? null;
?>

<section class="topic-community">
	<h2 class="section-title"><?php esc_html_e( 'Community', 'bnh-core' ); ?></h2>
	<p>
		<?php
		printf(
			esc_html__( 'Placeholder community content for %s.', 'bnh-core' ),
			esc_html( $active_parent instanceof WP_Term ? $active_parent->name : __( 'this topic', 'bnh-core' ) )
		);
		?>
	</p>
</section>
