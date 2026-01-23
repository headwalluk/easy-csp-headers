<?php
/**
 * Private helper functions.
 *
 * Internal utility functions used throughout the plugin.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

/**
 * Get the global plugin instance.
 *
 * @since 0.1.0
 *
 * @return Plugin Plugin instance.
 */
function get_plugin_instance(): Plugin {
	global $ecsp_plugin_instance;
	return $ecsp_plugin_instance;
}

/**
 * Check if CSP is in Report-Only mode.
 *
 * @since 0.1.0
 *
 * @return bool True if report-only mode, false otherwise.
 */
function is_report_only_mode(): bool {
	$mode = get_option( OPT_MODE, DEF_MODE );
	return 'report-only' === $mode;
}

/**
 * Determine if CSP processing should be skipped for current request.
 *
 * @since 0.1.0
 *
 * @return bool True if should skip, false otherwise.
 */
function should_skip_csp(): bool {
	$result = null;

	// Skip if plugin is disabled.
	$enabled = (bool) filter_var( get_option( OPT_ENABLED, DEF_ENABLED ), FILTER_VALIDATE_BOOLEAN );
	if ( ! $enabled ) {
		$result = true;
	}

	// Skip admin area.
	if ( is_null( $result ) && is_admin() ) {
		$result = true;
	}

	// Skip if user is logged in and setting is disabled.
	$enable_for_logged_in = (bool) filter_var(
		get_option( OPT_ENABLE_FOR_LOGGED_IN, DEF_ENABLE_FOR_LOGGED_IN ),
		FILTER_VALIDATE_BOOLEAN
	);

	if ( is_null( $result ) && is_user_logged_in() && ! $enable_for_logged_in ) {
		$result = true;
	}

	// Skip if current path is excluded.
	if ( is_null( $result ) ) {
		$excluded_paths = get_option( OPT_EXCLUDED_PATHS, DEF_EXCLUDED_PATHS );
		if ( ! empty( $excluded_paths ) ) {
			$paths        = array_filter( array_map( 'trim', explode( "\n", $excluded_paths ) ) );
			$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$parsed_uri   = wp_parse_url( $request_uri );
			$current_path = isset( $parsed_uri['path'] ) ? $parsed_uri['path'] : '';

			foreach ( $paths as $pattern ) {
				// Exact match.
				if ( $pattern === $current_path ) {
					$result = true;
					break;
				}

				// Wildcard support: /checkout/* matches /checkout/anything.
				if ( str_ends_with( $pattern, '*' ) ) {
					$prefix = rtrim( $pattern, '*' );
					if ( str_starts_with( $current_path, $prefix ) ) {
						$result = true;
						break;
					}
				}
			}
		}
	}

	// Default: Don't skip.
	if ( is_null( $result ) ) {
		$result = false;
	}

	/**
	 * Filter whether CSP processing should be skipped for the current request.
	 *
	 * @since 0.3.0
	 *
	 * @param bool $result Whether to skip CSP processing.
	 */
	return (bool) apply_filters( 'ecsp_should_skip_csp', $result );
}
