<?php
/**
 * Narrow REST endpoint for dynamic topic content swaps.
 *
 * @package BNH_Core
 */

/**
 * Register the topic content endpoint.
 *
 * @return void
 */
function bnh_core_register_health_topic_rest_routes() {
	register_rest_route(
		'bnh-core/v1',
		'/topic-content',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'bnh_core_get_health_topic_rest_response',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'bnh_core_register_health_topic_rest_routes' );

/**
 * Return dynamic topic content HTML.
 *
 * Validates the request, resolves the parent/child context, renders the shared
 * partials, and returns HTML fragments.
 *
 * @param WP_REST_Request $request REST request.
 * @return WP_REST_Response|WP_Error
 */
function bnh_core_get_health_topic_rest_response( WP_REST_Request $request ) {
	if ( ! function_exists( 'bnh_get_health_topic_parent_term_by_slug' ) || ! function_exists( 'bnh_get_health_topic_child_term_by_parent_and_slug' ) ) {
		return new WP_Error( 'bnh_core_missing_dependencies', __( 'Topic helpers are unavailable.', 'bnh-core' ), array( 'status' => 500 ) );
	}

	$parent_slug = sanitize_title( (string) $request->get_param( 'parent' ) );
	$child_slug  = sanitize_title( (string) $request->get_param( 'child' ) );
	$paged       = max( 1, absint( $request->get_param( 'paged' ) ) );
	$fragment    = sanitize_key( (string) $request->get_param( 'fragment' ) );

	if ( empty( $parent_slug ) || empty( $child_slug ) ) {
		return new WP_Error( 'bnh_core_invalid_request', __( 'Parent and child slugs are required.', 'bnh-core' ), array( 'status' => 400 ) );
	}

	$parent_term = bnh_get_health_topic_parent_term_by_slug( $parent_slug );
	$child_term  = $parent_term instanceof WP_Term ? bnh_get_health_topic_child_term_by_parent_and_slug( $parent_slug, $child_slug ) : null;

	if ( ! ( $parent_term instanceof WP_Term ) || ! ( $child_term instanceof WP_Term ) ) {
		return new WP_Error( 'bnh_core_topic_not_found', __( 'Requested topic was not found.', 'bnh-core' ), array( 'status' => 404 ) );
	}

	$context = bnh_core_build_topic_content_context(
		array(
			'active_parent'    => $parent_term,
			'active_child'     => $child_term,
			'parent_terms'     => function_exists( 'bnh_get_health_topic_parent_terms' ) ? bnh_get_health_topic_parent_terms() : array(),
			'child_terms'      => function_exists( 'bnh_get_health_topic_child_terms' ) ? bnh_get_health_topic_child_terms( $parent_term->term_id ) : array(),
			'is_parent_archive'=> false,
			'is_child_archive' => true,
			'is_topic_single'  => false,
		),
		$paged
	);

	$response_data = array();

	if ( 'latest' !== $fragment ) {
		$response_data['featured_html'] = bnh_core_get_template_part_html(
			'template-parts/sections/topic-featured-article',
			array(
				'context' => $context,
			)
		);
	}

	$response_data['latest_html'] = bnh_core_get_template_part_html(
		'template-parts/sections/topic-latest-articles',
		array(
			'context' => $context,
		)
	);

	return rest_ensure_response( $response_data );
}
