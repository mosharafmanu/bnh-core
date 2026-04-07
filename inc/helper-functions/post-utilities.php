<?php
/**
 * Post utility helpers.
 *
 * @package BNH_Core
 */

if ( ! function_exists( 'purple_surgical_render_back_to_blogs_button' ) ) {
	/**
	 * Render back to blogs button
	 *
	 * Only works for WordPress default blog posts (post type 'post').
	 * Does not render for custom post types.
	 *
	 * @param array $args {
	 *     Optional arguments.
	 *
	 *     @type string $url           Blog page URL. Default uses page_for_posts option.
	 *     @type string $text          Button label. Default "Back to Blogs".
	 *     @type string $class         CSS classes. Default "site-btn green-mid-black".
	 *     @type string $wrapper_class Wrapper div classes. Default "back-to-blogs-button layout-margin".
	 * }
	 * @return void
	 */
	function purple_surgical_render_back_to_blogs_button( $args = [] ) {
		// Only render for WordPress default blog posts
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		$defaults = [
			'url'           => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ),
			'text'          => __( 'Back to News & Events', 'bnh-core' ),
			'class'         => 'site-btn pink-purple',
			'wrapper_class' => 'back-to-blogs-button text-center layout-margin mt-50 mt-md-70 mt-lg-100 pt-50 pt-md-70 pt-lg-100',
		];
		$args = wp_parse_args( $args, $defaults );
		?>

		<div class="<?php echo esc_attr( $args['wrapper_class'] ); ?>">
			<a href="<?php echo esc_url( $args['url'] ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>">
				<?php echo esc_html( $args['text'] ); ?>
			</a>
		</div>

		<?php
	}
}

if ( ! function_exists( 'bnh_core_render_back_to_blogs_button' ) ) {
	/**
	 * BNH alias for the back-to-posts button helper.
	 *
	 * @param array $args Render arguments.
	 * @return void
	 */
	function bnh_core_render_back_to_blogs_button( $args = [] ) {
		purple_surgical_render_back_to_blogs_button( $args );
	}
}
