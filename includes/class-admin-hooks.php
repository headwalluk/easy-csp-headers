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
	 * Add plugin action links.
	 *
	 * @since 0.2.0
	 *
	 * @param array $links Existing plugin action links.
	 *
	 * @return array Modified plugin action links.
	 */
	public function add_plugin_action_links( array $links ): array {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-general.php?page=easy-csp-headers' ) ),
			esc_html__( 'Settings', 'easy-csp-headers' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

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

		// Localize script with AJAX URL and nonce.
		wp_localize_script(
			'ecsp-admin-scripts',
			'ecspAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ecsp_test_headers' ),
			)
		);
	}

	/**
	 * Show admin notice after settings save.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function show_settings_saved_notice(): void {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Reading WordPress-set query parameters.
		// Only show on our settings page after update.
		if ( ! isset( $_GET['page'] ) || 'easy-csp-headers' !== $_GET['page'] ) {
			return;
		}

		if ( ! isset( $_GET['settings-updated'] ) || 'true' !== $_GET['settings-updated'] ) {
			return;
		}
		// phpcs:enable

		printf(
			'<div class="notice notice-warning is-dismissible"><p><strong>%s</strong> %s</p></div>',
			esc_html__( 'Important:', 'easy-csp-headers' ),
			esc_html__( 'If you use a page caching plugin, clear your cache now for the new CSP settings to take effect.', 'easy-csp-headers' )
		);
	}

	/**
	 * Show admin notices for CSP status.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function show_csp_status_notices(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$enabled = (bool) filter_var( get_option( OPT_ENABLED, DEF_ENABLED ), FILTER_VALIDATE_BOOLEAN );
		$mode    = get_option( OPT_MODE, DEF_MODE );

		// Show notice if CSP is disabled.
		if ( ! $enabled ) {
			printf(
				'<div class="notice notice-error"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
				esc_html__( 'CSP Headers:', 'easy-csp-headers' ),
				esc_html__( 'Content Security Policy headers are currently disabled.', 'easy-csp-headers' ),
				esc_url( admin_url( 'options-general.php?page=easy-csp-headers' ) ),
				esc_html__( 'Enable CSP Headers', 'easy-csp-headers' )
			);
			return;
		}

		// Show notice if in Report-Only mode.
		if ( 'report-only' === $mode ) {
			printf(
				'<div class="notice notice-warning"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
				esc_html__( 'CSP Headers:', 'easy-csp-headers' ),
				esc_html__( 'CSP is in Report-Only mode and NOT providing security protection. Violations are logged but not blocked.', 'easy-csp-headers' ),
				esc_url( admin_url( 'options-general.php?page=easy-csp-headers#general' ) ),
				esc_html__( 'Switch to Enforce Mode', 'easy-csp-headers' )
			);
		}
	}

	/**
	 * Add admin bar notifications for CSP status.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar WordPress admin bar object.
	 *
	 * @return void
	 */
	public function add_admin_bar_notices( \WP_Admin_Bar $wp_admin_bar ): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$enabled = (bool) filter_var( get_option( OPT_ENABLED, DEF_ENABLED ), FILTER_VALIDATE_BOOLEAN );
		$mode    = get_option( OPT_MODE, DEF_MODE );

		// Show notice if CSP is disabled.
		if ( ! $enabled ) {
			$wp_admin_bar->add_node(
				array(
					'id'    => 'ecsp-disabled-notice',
					'title' => sprintf(
						'<span style="color:#f0ad4e;">⚠ %s</span>',
						esc_html__( 'CSP Headers Disabled', 'easy-csp-headers' )
					),
					'href'  => admin_url( 'options-general.php?page=easy-csp-headers' ),
					'meta'  => array(
						'title' => __( 'Click to enable CSP Headers', 'easy-csp-headers' ),
					),
				)
			);
			return;
		}

		// Show notice if in Report-Only mode.
		if ( 'report-only' === $mode ) {
			$wp_admin_bar->add_node(
				array(
					'id'    => 'ecsp-report-only-notice',
					'title' => sprintf(
						'<span style="color:#00a0d2;">ℹ %s</span>',
						esc_html__( 'CSP: Report-Only Mode', 'easy-csp-headers' )
					),
					'href'  => admin_url( 'options-general.php?page=easy-csp-headers#general' ),
					'meta'  => array(
						'title' => __( 'Switch to Enforce mode for full protection', 'easy-csp-headers' ),
					),
				)
			);
		}
	}

	/**
	 * AJAX handler to test CSP headers.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function ajax_test_csp_headers(): void {
		check_ajax_referer( 'ecsp_test_headers', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'easy-csp-headers' ) ) );
		}

		// Get site URL.
		$site_url = home_url( '/' );

		// Make request to homepage.
		$response = wp_remote_get(
			$site_url,
			array(
				'timeout'     => 10,
				'sslverify'   => false,
				'redirection' => 0,
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error(
				array(
					'message' => sprintf(
						/* translators: %s: Error message */
						__( 'Request failed: %s', 'easy-csp-headers' ),
						$response->get_error_message()
					),
				)
			);
		}

		// Get headers.
		$headers       = wp_remote_retrieve_headers( $response );
		$csp_header    = isset( $headers['content-security-policy'] ) ? $headers['content-security-policy'] : null;
		$csp_ro_header = isset( $headers['content-security-policy-report-only'] ) ? $headers['content-security-policy-report-only'] : null;
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_msg  = wp_remote_retrieve_response_message( $response );

		// Prepare result.
		$result = array(
			'url'           => $site_url,
			'status'        => sprintf( '%d %s', $response_code, $response_msg ),
			'csp_found'     => ( ! is_null( $csp_header ) || ! is_null( $csp_ro_header ) ),
			'csp_header'    => $csp_header,
			'csp_ro_header' => $csp_ro_header,
		);

		wp_send_json_success( $result );
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
