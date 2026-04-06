<?php
/**
 * Featured research placeholder.
 *
 * @package BNH_Core
 */

$bnh_context   = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();
$active_parent = $bnh_context['active_parent'] ?? null;
?>

<section class="topic-featured-research">
	<h2 class="section-title"><?php esc_html_e( 'Featured Research', 'bnh-core' ); ?></h2>
	<p>
		<?php
		printf(
			esc_html__( 'Placeholder content for %s research highlights.', 'bnh-core' ),
			esc_html( $active_parent instanceof WP_Term ? $active_parent->name : __( 'topic', 'bnh-core' ) )
		);
		?>
	</p>
</section>
