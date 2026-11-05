<?php
/**
 * Easy CSP Headers — Uninstaller
 *
 * Runs when the plugin is deleted from the Plugins screen, not on deactivation.
 * Deletes nothing unless the "Delete all plugin data when uninstalled" setting
 * is on.
 *
 * @package Easy_CSP_Headers
 */

namespace Easy_CSP_Headers;

// Bail out if not invoked by WordPress's uninstall handler.
defined( 'WP_UNINSTALL_PLUGIN' ) || die();

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/includes/class-github-updater.php';

$easy_csp_headers_delete_data = (bool) filter_var(
	get_option( OPTION_DELETE_DATA_ON_UNINSTALL, DEFAULT_DELETE_DATA_ON_UNINSTALL ),
	FILTER_VALIDATE_BOOLEAN
);

if ( $easy_csp_headers_delete_data ) {
	global $wpdb;

	// Options are found by prefix so keys from older versions go too; each is then
	// deleted through the WordPress API so object caches are cleared with it.
	// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-off prefix lookup at uninstall; there is no API for it.
	$easy_csp_headers_option_names = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
			$wpdb->esc_like( OPTION_PREFIX ) . '%'
		)
	);
	// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

	foreach ( $easy_csp_headers_option_names as $easy_csp_headers_option_name ) {
		delete_option( $easy_csp_headers_option_name );
	}

	Github_Updater::delete_cache( basename( __DIR__ ) );
}
