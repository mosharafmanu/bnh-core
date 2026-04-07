<?php
/**
 * Featured article section.
 *
 * Uses the manually selected child-term featured article when available,
 * then falls back to the latest post in the active child term.
 *
 * @package BNH_Core
 */

$bnh_context      = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();
$featured_post_id = function_exists( 'bnh_core_get_topic_featured_post_id' ) ? bnh_core_get_topic_featured_post_id( $bnh_context ) : 0;
$active_child     = $bnh_context['active_child'] ?? null;
?>

<section class="topic-featured-article" data-topic-featured>
	<header class="section-header">
		<h2 class="section-title"><?php esc_html_e( 'Featured Article', 'bnh-core' ); ?></h2>
	</header>

	<?php if ( $featured_post_id ) : ?>
		<?php
		$featured_post = get_post( $featured_post_id );
		if ( ! ( $featured_post instanceof WP_Post ) ) {
			$featured_post = null;
		}
		?>
		<?php if ( $featured_post ) : ?>
			<?php
			get_template_part(
				'inc/components/cards/topic-featured-card',
				null,
				array(
					'post'       => $featured_post,
					'label'      => $active_child instanceof WP_Term ? $active_child->name : '',
					'show_label' => true,
				)
			);
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'No featured article found for this topic yet.', 'bnh-core' ); ?></p>
		<?php endif; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No featured article found for this topic yet.', 'bnh-core' ); ?></p>
	<?php endif; ?>
</section>
