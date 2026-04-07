<?php
/**
 * Featured research section.
 *
 * @package BNH_Core
 */

$bnh_context                = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();
$active_parent              = $bnh_context['active_parent'] ?? null;
$featured_research_post_id  = function_exists( 'bnh_core_get_topic_featured_research_post_id' ) ? bnh_core_get_topic_featured_research_post_id( $bnh_context ) : 0;
$featured_research_post     = $featured_research_post_id ? get_post( $featured_research_post_id ) : null;

if ( ! ( $featured_research_post instanceof WP_Post ) ) {
	$featured_research_post = null;
}
?>

<section class="topic-featured-research">
	<h2 class="section-title"><?php esc_html_e( 'Featured Research', 'bnh-core' ); ?></h2>

	<?php if ( $featured_research_post ) : ?>
		<?php
		get_template_part(
			'inc/components/cards/topic-featured-card',
			null,
			array(
				'post'       => $featured_research_post,
				'label'      => '',
				'show_label' => false,
			)
		);
		?>
	<?php elseif ( $active_parent instanceof WP_Term ) : ?>
		<p>
			<?php
			printf(
				esc_html__( 'No featured research selected yet for %s.', 'bnh-core' ),
				esc_html( $active_parent->name )
			);
			?>
		</p>
	<?php endif; ?>
</section>
