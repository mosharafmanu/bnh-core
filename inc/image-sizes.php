<?php

/**
 * Purple Surgical – Responsive Image Sizes
 *
 * Strategy:
 * - Use a small shared width ladder across the theme instead of generating
 *   section-specific near-duplicate sizes.
 * - Keep the existing section tokens in the picture helper so templates do not
 *   need to change.
 * - Prefer flexible-height derivatives based on the uploaded image aspect
 *   ratio, in line with the latest client direction.
 *
 * Design Specs: 1920px screen
 *
 * Latest client direction:
 * - Reuse a short approved list of shared sizes.
 * - Avoid generating too many image files per upload.
 * - Focus on consolidated widths more than many layout-specific outputs.
 *
 * Shared size ladder:
 * - 100
 * - 150 (WordPress thumbnail is kept)
 * - 300
 * - 600
 * - 750
 * - 900
 * - 1200
 *
 * Total custom sizes: 6
 */

add_action( 'after_setup_theme', 'purple_surgical_register_image_sizes' );
function purple_surgical_register_image_sizes() {
	add_image_size( 'ps-100', 100, 9999, false );
	add_image_size( 'ps-300', 300, 9999, false );
	add_image_size( 'ps-600', 600, 9999, false );
	add_image_size( 'ps-750', 750, 9999, false );
	add_image_size( 'ps-900', 900, 9999, false );
	add_image_size( 'ps-1200', 1200, 9999, false );
}

/**
 * Enable responsive images with srcset
 * WordPress will automatically generate srcset for better performance
 */
add_filter( 'max_srcset_image_width', 'purple_surgical_max_srcset_image_width' );
function purple_surgical_max_srcset_image_width() {
    return 3840; // Max width for srcset (2x retina of 1920px)
}

/**
 * Add custom image sizes to the media library dropdown
 * Makes it easier for content editors to select the right size
 */
add_filter( 'image_size_names_choose', 'purple_surgical_custom_image_sizes_choose' );
function purple_surgical_custom_image_sizes_choose( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'ps-100'  => __( 'Shared Width 100', 'purple-surgical' ),
			'ps-300'  => __( 'Shared Width 300', 'purple-surgical' ),
			'ps-600'  => __( 'Shared Width 600', 'purple-surgical' ),
			'ps-750'  => __( 'Shared Width 750', 'purple-surgical' ),
			'ps-900'  => __( 'Shared Width 900', 'purple-surgical' ),
			'ps-1200' => __( 'Shared Width 1200', 'purple-surgical' ),
		)
	);
}

/**
 * Enable WebP support for uploads
 * WebP format significantly reduces file sizes while maintaining quality
 * WordPress 5.8+ automatically generates WebP versions if server supports it
 */
add_filter( 'mime_types', 'purple_surgical_enable_webp_upload' );
function purple_surgical_enable_webp_upload( $mimes ) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}

/**
 * Disable default WordPress image sizes to save server space
 * Comment out any sizes you want to keep
 */
add_filter( 'intermediate_image_sizes_advanced', 'purple_surgical_disable_default_image_sizes' );
function purple_surgical_disable_default_image_sizes( $sizes ) {
    // Keep the default 150x150 thumbnail; it is useful for the square family
    unset( $sizes['medium'] );       // 300x300 max
    unset( $sizes['medium_large'] ); // 768x0 max
    unset( $sizes['large'] );        // 1024x1024 max
    unset( $sizes['1536x1536'] );    // 1536x1536 max
    unset( $sizes['2048x2048'] );    // 2048x2048 max

    return $sizes;
}
