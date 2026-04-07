<?php
/**
 * BNH Core responsive image sizes.
 *
 * Implements the shared image-size ladder from IMAGE_SIZE_POLICY.md.
 *
 * Approved generated widths:
 * - 100
 * - 150x150 thumbnail (kept from WordPress core)
 * - 300
 * - 600
 * - 750
 * - 900
 * - 1200
 *
 * These widths use flexible height and are reused across the theme. Section
 * tokens remain stable in the responsive picture helper and map internally to
 * this shared size ladder.
 *
 * @package BNH_Core
 */

add_action( 'after_setup_theme', 'bnh_core_register_image_sizes' );
function bnh_core_register_image_sizes() {
	add_image_size( 'bhn-100', 100, 9999, false );
	add_image_size( 'bhn-300', 300, 9999, false );
	add_image_size( 'bhn-600', 600, 9999, false );
	add_image_size( 'bhn-750', 750, 9999, false );
	add_image_size( 'bhn-900', 900, 9999, false );
	add_image_size( 'bhn-1200', 1200, 9999, false );
}

/**
 * Set the maximum srcset width.
 *
 * @return int
 */
add_filter( 'max_srcset_image_width', 'bnh_core_max_srcset_image_width' );
function bnh_core_max_srcset_image_width() {
	return 3840;
}

/**
 * Add shared image sizes to the media library dropdown.
 *
 * @param array $sizes Existing media size labels.
 * @return array
 */
add_filter( 'image_size_names_choose', 'bnh_core_custom_image_sizes_choose' );
function bnh_core_custom_image_sizes_choose( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'bhn-100'  => __( 'Shared Width 100', 'bnh-core' ),
			'bhn-300'  => __( 'Shared Width 300', 'bnh-core' ),
			'bhn-600'  => __( 'Shared Width 600', 'bnh-core' ),
			'bhn-750'  => __( 'Shared Width 750', 'bnh-core' ),
			'bhn-900'  => __( 'Shared Width 900', 'bnh-core' ),
			'bhn-1200' => __( 'Shared Width 1200', 'bnh-core' ),
		)
	);
}

/**
 * Enable WebP support for uploads.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
add_filter( 'mime_types', 'bnh_core_enable_webp_upload' );
function bnh_core_enable_webp_upload( $mimes ) {
	$mimes['webp'] = 'image/webp';
	return $mimes;
}

/**
 * Disable non-policy default image sizes.
 *
 * Keeps the core thumbnail size and the approved shared custom ladder while
 * avoiding extra near-duplicate core derivatives.
 *
 * @param array $sizes Generated intermediate image sizes.
 * @return array
 */
add_filter( 'intermediate_image_sizes_advanced', 'bnh_core_disable_default_image_sizes' );
function bnh_core_disable_default_image_sizes( $sizes ) {
	unset( $sizes['medium'] );
	unset( $sizes['medium_large'] );
	unset( $sizes['large'] );
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );

	return $sizes;
}
