<?php
/**
 * Topic community signup section.
 *
 * Reusable topic-aware signup block for topic hub and future topic-context
 * templates such as single posts.
 *
 * @package BNH_Core
 */

$bnh_context            = isset( $args['context'] ) && is_array( $args['context'] ) ? $args['context'] : array();
$bnh_heading            = function_exists( 'bnh_core_get_topic_community_heading' ) ? bnh_core_get_topic_community_heading() : '';
$bnh_description        = function_exists( 'bnh_core_get_topic_community_description' ) ? bnh_core_get_topic_community_description() : '';
$bnh_embed_code         = function_exists( 'bnh_core_get_topic_community_embed_code' ) ? bnh_core_get_topic_community_embed_code() : '';
$bnh_gradient_start     = ! empty( $bnh_context['active_topic_color_value'] ) ? (string) $bnh_context['active_topic_color_value'] : '#0B3276';
$bnh_section_style_attr = sprintf(
	' style="%s"',
	esc_attr( '--topic-community-gradient-start: ' . $bnh_gradient_start . ';' )
);

if ( '' === $bnh_heading ) {
	$bnh_heading = __( 'Join Our Growing Community!', 'bnh-core' );
}

if ( '' === $bnh_description ) {
	$bnh_description = __( 'Get daily tips and tricks on how to improve your quality of life, manage your symptoms and restore your health naturally.', 'bnh-core' );
}
?>

<section class="topic-community mt-50 mt-md-70 mt-lg-100"<?php echo $bnh_section_style_attr; ?>>
	<div class="topic-community__content">
		<div class="topic-community__copy">
			<h2 class="section-title topic-community__title"><?php echo esc_html( $bnh_heading ); ?></h2>
			<div class="topic-community__description">
				<?php echo wp_kses_post( $bnh_description ); ?>
			</div>
		</div>

		<div class="topic-community__form">
			<?php if ( '' !== trim( $bnh_embed_code ) ) : ?>
				<?php echo $bnh_embed_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<form class="topic-community__form-placeholder" action="#" method="post" onsubmit="return false;">
					<label class="sr-only" for="topic-community-email"><?php esc_html_e( 'Email address', 'bnh-core' ); ?></label>
					<input id="topic-community-email" type="email" class="topic-community__input" placeholder="<?php esc_attr_e( 'Email address*', 'bnh-core' ); ?>" disabled />
					<button type="button" class="topic-community__button" disabled><?php esc_html_e( 'Join Now', 'bnh-core' ); ?></button>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>
