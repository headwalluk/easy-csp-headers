<?php
/**
 * Settings class.
 *
 * Handles WordPress Settings API registration and sanitization.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

/**
 * Settings management class.
 *
 * @since 0.1.0
 */
class Settings {

	/**
	 * Preserve active tab on settings page redirect.
	 *
	 * @since 0.2.0
	 *
	 * @param string $location Redirect location.
	 *
	 * @return string Modified redirect location with tab hash.
	 */
	public function preserve_tab_on_redirect( string $location ): string {
		// Only modify redirect for our settings page.
		if ( strpos( $location, 'page=easy-csp-headers' ) === false ) {
			return $location;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Settings API handles nonce verification.
		$active_tab = isset( $_POST['ecsp_active_tab'] ) ? sanitize_text_field( wp_unslash( $_POST['ecsp_active_tab'] ) ) : '';
		// phpcs:enable

		if ( ! empty( $active_tab ) ) {
			$location .= '#' . $active_tab;
		}

		return $location;
	}

	/**
	 * Register all settings with WordPress.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_settings(): void {
		// General settings.
		register_setting(
			'ecsp_general',
			OPT_ENABLED,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				'default'           => DEF_ENABLED,
			)
		);

		register_setting(
			'ecsp_general',
			OPT_MODE,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_mode' ),
				'default'           => DEF_MODE,
			)
		);

		register_setting(
			'ecsp_general',
			OPT_ENABLE_FOR_LOGGED_IN,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				'default'           => DEF_ENABLE_FOR_LOGGED_IN,
			)
		);

		// CSP Rules settings.
		register_setting(
			'ecsp_rules',
			OPT_USE_STRICT_DYNAMIC,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				'default'           => DEF_USE_STRICT_DYNAMIC,
			)
		);

		register_setting(
			'ecsp_rules',
			OPT_USE_UNSAFE_HASHES,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				'default'           => DEF_USE_UNSAFE_HASHES,
			)
		);

		register_setting(
			'ecsp_rules',
			OPT_PROCESS_STYLES,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				'default'           => DEF_PROCESS_STYLES,
			)
		);

		register_setting(
			'ecsp_rules',
			OPT_CUSTOM_DIRECTIVES,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_textarea' ),
				'default'           => DEF_CUSTOM_DIRECTIVES,
			)
		);

		register_setting(
			'ecsp_rules',
			OPT_REPORT_URI,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_url' ),
				'default'           => DEF_REPORT_URI,
			)
		);

		register_setting(
			'ecsp_rules',
			OPT_WHITELISTED_DOMAINS,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_textarea' ),
				'default'           => DEF_WHITELISTED_DOMAINS,
			)
		);

		// Exclusions settings.
		register_setting(
			'ecsp_exclusions',
			OPT_EXCLUDED_PATHS,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_textarea' ),
				'default'           => DEF_EXCLUDED_PATHS,
			)
		);
	}

	/**
	 * Sanitize checkbox input.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool Sanitized boolean value.
	 */
	public function sanitize_checkbox( $value ): bool {
		return (bool) filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Sanitize CSP mode input.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return string Sanitized mode value.
	 */
	public function sanitize_mode( $value ): string {
		$result    = null;
		$sanitized = sanitize_text_field( $value );

		if ( in_array( $sanitized, ALLOWED_MODES, true ) ) {
			$result = $sanitized;
		}

		if ( is_null( $result ) ) {
			$result = DEF_MODE;
		}

		return $result;
	}

	/**
	 * Sanitize textarea input.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return string Sanitized textarea content.
	 */
	public function sanitize_textarea( $value ): string {
		return sanitize_textarea_field( $value );
	}

	/**
	 * Sanitize URL input.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return string Sanitized URL.
	 */
	public function sanitize_url( $value ): string {
		return esc_url_raw( $value );
	}
}
