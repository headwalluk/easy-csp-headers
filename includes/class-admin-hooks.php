<?php
/**
 * Admin Hooks class.
 *
 * Handles admin menu registration and asset loading.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

/**
 * Admin area integration class.
 *
 * @since 0.1.0
 */
class Admin_Hooks {

	/**
	 * Add admin menu items.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_menu_items(): void {
		add_options_page(
			__( 'Easy CSP Headers', 'easy-csp-headers' ),
			__( 'CSP Headers', 'easy-csp-headers' ),
			'manage_options',
			'easy-csp-headers',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 0.1.0
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 *
	 * @return void
	 */
	public function enqueue_admin_assets( string $hook_suffix ): void {
		// Only load on our settings page.
		if ( 'settings_page_easy-csp-headers' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'ecsp-admin-styles',
			ECSP_URL . 'assets/admin/admin-styles.css',
			array(),
			ECSP_VERSION
		);

		wp_enqueue_script(
			'ecsp-admin-scripts',
			ECSP_URL . 'assets/admin/admin-scripts.js',
			array(),
			ECSP_VERSION,
			true
		);
	}

	/**
	 * Render settings page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'easy-csp-headers' ) );
		}

		require_once ECSP_DIR . 'admin-templates/settings-page.php';
	}
}
