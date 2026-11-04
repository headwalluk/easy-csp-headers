<?php
/**
 * Plugin Name: Easy CSP Headers
 * Plugin URI: https://headwall-hosting.com/plugins/easy-csp-headers/
 * Description: Automatically generate and inject Content Security Policy (CSP) headers with nonces for enhanced security.
 * Version: 1.1.1
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author: Paul Faulkner
 * Author URI: https://headwall-hosting.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: easy-csp-headers
 * Domain Path: /languages
 *
 * @package Easy_CSP_Headers
 */

defined( 'ABSPATH' ) || die();

define( 'ECSP_VERSION', '1.1.1' );
define( 'ECSP_FILE', __FILE__ );
define( 'ECSP_DIR', plugin_dir_path( __FILE__ ) );
define( 'ECSP_URL', plugin_dir_url( __FILE__ ) );
define( 'ECSP_BASENAME', plugin_basename( __FILE__ ) );

require_once ECSP_DIR . 'constants.php';
require_once ECSP_DIR . 'functions-private.php';
require_once ECSP_DIR . 'includes/class-csp-processor.php';
require_once ECSP_DIR . 'includes/class-output-buffer.php';
require_once ECSP_DIR . 'includes/class-settings.php';
require_once ECSP_DIR . 'includes/class-admin-hooks.php';
require_once ECSP_DIR . 'includes/class-plugin.php';

// GitHub auto-updates (admin, cron and WP-CLI only — no need to load on front-end requests).
if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once ECSP_DIR . 'includes/class-headwall-github-plugin-updater.php';
	new Headwall_GitHub_Plugin_Updater( ECSP_FILE, 'headwalluk/easy-csp-headers' );
}

/**
 * Initialize the plugin.
 *
 * @since 1.1.1
 */
function easy_csp_headers_run(): void {
	$plugin = Easy_CSP_Headers\Plugin::instance();
	$plugin->run();
}
easy_csp_headers_run();
