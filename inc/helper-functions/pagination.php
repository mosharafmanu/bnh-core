<?php
/**
 * Pagination Helper
 *
 * Renders blog pagination with custom SVG arrows.
 *
 * @package purple-surgical
 */

/**
 * Render pagination with SVG navigation arrows
 *
 * @return void
 */
function purple_surgical_render_pagination() {
	// Grab SVG icons for arrows
	ob_start();
	get_template_part( 'assets/svgs/angle-left-pagination' );
	$prev_arrow = ob_get_clean();

	ob_start();
	get_template_part( 'assets/svgs/angle-right-pagination' );
	$next_arrow = ob_get_clean();

	$args = [
		'mid_size'  => 1, // Reduced from 2 to 1 for better mobile display
		'end_size'  => 1, // Show first and last page
		'prev_text' => '<span class="pagination-arrow">' . $prev_arrow . '</span>',
		'next_text' => '<span class="pagination-arrow">' . $next_arrow . '</span>',
		'type'      => 'list',
	];

	$pagination = paginate_links( $args );

	if ( ! $pagination ) {
		return;
	}

	// Allow SVG and pagination markup through kses
	$allowed_tags = [
		'nav'  => [
			'class'      => [],
			'aria-label' => [],
		],
		'ul'   => [ 'class' => [] ],
		'li'   => [ 'class' => [] ],
		'a'    => [
			'class' => [],
			'href'  => [],
		],
		'span' => [
			'class'        => [],
			'aria-current' => [],
		],
		'svg'  => [
			'width'   => [],
			'height'  => [],
			'viewbox' => [],
			'fill'    => [],
			'xmlns'   => [],
		],
		'path' => [
			'd'            => [],
			'stroke'       => [],
			'stroke-width' => [],
			'fill'         => [],
		],
	];
	?>
	<nav class="blog-pagination pagination" aria-label="<?php esc_attr_e( 'Blog pagination', 'purple-surgical' ); ?>">
		<?php echo wp_kses( $pagination, $allowed_tags ); ?>
	</nav>
	<?php
}

/**
 * Render WooCommerce pagination with SVG navigation arrows
 *
 * @return void
 */
function purple_surgical_render_woocommerce_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	// Grab SVG icons for arrows
	ob_start();
	get_template_part( 'assets/svgs/angle-left-pagination' );
	$prev_arrow = ob_get_clean();

	ob_start();
	get_template_part( 'assets/svgs/angle-right-pagination' );
	$next_arrow = ob_get_clean();

	$args = [
		'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
		'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'total'     => $wp_query->max_num_pages,
		'mid_size'  => 1, // Reduced from 2 to 1 for better mobile display
		'end_size'  => 1, // Show first and last page
		'prev_text' => '<span class="pagination-arrow">' . $prev_arrow . '</span>',
		'next_text' => '<span class="pagination-arrow">' . $next_arrow . '</span>',
		'type'      => 'list',
	];

	$pagination = paginate_links( $args );

	if ( ! $pagination ) {
		return;
	}

	// Allow SVG and pagination markup through kses
	$allowed_tags = [
		'nav'  => [
			'class'      => [],
			'aria-label' => [],
		],
		'ul'   => [ 'class' => [] ],
		'li'   => [ 'class' => [] ],
		'a'    => [
			'class' => [],
			'href'  => [],
		],
		'span' => [
			'class'        => [],
			'aria-current' => [],
		],
		'svg'  => [
			'width'   => [],
			'height'  => [],
			'viewbox' => [],
			'fill'    => [],
			'xmlns'   => [],
		],
		'path' => [
			'd'            => [],
			'stroke'       => [],
			'stroke-width' => [],
			'fill'         => [],
		],
	];
	?>
	<nav class="woocommerce-pagination pagination layout-padding" aria-label="<?php esc_attr_e( 'Product pagination', 'purple-surgical' ); ?>">
		<?php echo wp_kses( $pagination, $allowed_tags ); ?>
	</nav>
	<?php
}
