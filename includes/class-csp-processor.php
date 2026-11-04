<?php
/**
 * CSP Processor class.
 *
 * Handles HTML processing, nonce generation, and CSP header injection.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

/**
 * CSP processing engine.
 *
 * @since 0.1.0
 */
class CSP_Processor {

	/**
	 * Nonce for the current request, generated on first use.
	 *
	 * @var string|null
	 */
	private ?string $nonce = null;

	/**
	 * Whether the CSP header has already been sent for this request.
	 *
	 * @var bool
	 */
	private bool $header_sent = false;

	/**
	 * Process HTML output and inject CSP.
	 *
	 * Main entry point for CSP processing. Generates nonce, modifies HTML,
	 * and sends CSP header.
	 *
	 * @since 0.1.0
	 *
	 * @param string $html HTML content to process.
	 *
	 * @return string Modified HTML with nonces added.
	 */
	public function process_output( string $html ): string {
		$result = null;

		// Bail early if HTML is empty or Tag Processor unavailable.
		if ( empty( $html ) || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
			$result = $html;
		}

		// Check if we should skip CSP processing.
		if ( is_null( $result ) && should_skip_csp() ) {
			$result = $html;
		}

		// Process HTML and inject CSP.
		if ( is_null( $result ) ) {
			$nonce = $this->get_nonce();
			$tags  = new \WP_HTML_Tag_Processor( $html );

			$this->add_nonce_to_scripts( $tags, $nonce );

			$process_styles = (bool) filter_var(
				get_option( OPTION_PROCESS_STYLES, DEFAULT_PROCESS_STYLES ),
				FILTER_VALIDATE_BOOLEAN
			);

			if ( $process_styles ) {
				$this->add_nonce_to_styles( $tags, $nonce );
			}

			$this->inject_csp_header( $nonce );

			$result = $tags->get_updated_html();
		}

		return $result;
	}

	/**
	 * Return the nonce for the current request, generating it on first call.
	 *
	 * @since 1.1.1
	 *
	 * @return string Base64-encoded nonce.
	 */
	public function get_nonce(): string {
		if ( is_null( $this->nonce ) ) {
			$this->nonce = $this->generate_nonce();
		}

		return $this->nonce;
	}

	/**
	 * Generate a cryptographically secure nonce.
	 *
	 * @since 0.1.0
	 *
	 * @return string Base64-encoded nonce.
	 */
	public function generate_nonce(): string {
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Used for CSP nonce encoding, not obfuscation.
		return base64_encode( random_bytes( NONCE_BYTE_LENGTH ) );
	}

	/**
	 * Add nonce attribute to all executable script tags.
	 *
	 * @since 0.1.0
	 *
	 * @param \WP_HTML_Tag_Processor $tags  Tag processor instance.
	 * @param string                 $nonce Nonce value to inject.
	 *
	 * @return void
	 */
	public function add_nonce_to_scripts( \WP_HTML_Tag_Processor $tags, string $nonce ): void {
		while ( $tags->next_tag( array( 'tag_name' => 'script' ) ) ) {
			$type = $tags->get_attribute( 'type' );

			if ( $this->should_process_script( $type ) ) {
				$tags->set_attribute( 'nonce', $nonce );
			}
		}
	}

	/**
	 * Add nonce attribute to all style tags.
	 *
	 * @since 0.1.0
	 *
	 * @param \WP_HTML_Tag_Processor $tags  Tag processor instance.
	 * @param string                 $nonce Nonce value to inject.
	 *
	 * @return void
	 */
	public function add_nonce_to_styles( \WP_HTML_Tag_Processor $tags, string $nonce ): void {
		while ( $tags->next_tag( array( 'tag_name' => 'style' ) ) ) {
			$tags->set_attribute( 'nonce', $nonce );
		}
	}

	/**
	 * Determine if a script tag should be processed.
	 *
	 * Only executable script types need nonces. Non-executable types like
	 * JSON-LD, templates, etc. are skipped.
	 *
	 * @since 0.1.0
	 *
	 * @param string|null $type Script type attribute value.
	 *
	 * @return bool True if script should be processed, false otherwise.
	 */
	public function should_process_script( ?string $type ): bool {
		$result = null;

		// No type attribute means executable JavaScript.
		if ( is_null( $type ) || '' === $type ) {
			$result = true;
		}

		// Check against whitelist of executable types.
		if ( is_null( $result ) && in_array( $type, EXECUTABLE_SCRIPT_TYPES, true ) ) {
			$result = true;
		}

		// Default: Don't process.
		if ( is_null( $result ) ) {
			$result = false;
		}

		return $result;
	}

	/**
	 * Send CSP header to browser.
	 *
	 * @since 0.1.0
	 *
	 * @param string $nonce Nonce value for header.
	 *
	 * @return void
	 */
	public function inject_csp_header( string $nonce ): void {
		// Output-buffer callbacks run once per chunk when ob_flush() is called; send only on the first.
		if ( $this->header_sent || headers_sent() ) {
			return;
		}

		$header_name  = is_report_only_mode() ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
		$header_value = $this->build_csp_header_value( $nonce );

		header( "{$header_name}: {$header_value}" );
		$this->header_sent = true;
	}

	/**
	 * Build CSP header value from settings and nonce.
	 *
	 * @since 0.1.0
	 *
	 * @param string $nonce Nonce value to include in policy.
	 *
	 * @return string Complete CSP header value.
	 */
	public function build_csp_header_value( string $nonce ): string {
		$directives = array();

		// Build script-src directive.
		$script_src = $this->get_script_src_directive( $nonce );
		if ( ! empty( $script_src ) ) {
			$directives[] = "script-src {$script_src}";
		}

		// Build style-src directive if processing styles.
		$process_styles = (bool) filter_var(
			get_option( OPTION_PROCESS_STYLES, DEFAULT_PROCESS_STYLES ),
			FILTER_VALIDATE_BOOLEAN
		);

		if ( $process_styles ) {
			$style_src = $this->get_style_src_directive( $nonce );
			if ( ! empty( $style_src ) ) {
				$directives[] = "style-src {$style_src}";
			}
		}

		// Add base directives.
		$directives[] = "object-src 'none'";
		$directives[] = "base-uri 'none'";

		// Merge custom directives from settings.
		$custom_directives = get_option( OPTION_CUSTOM_DIRECTIVES, DEFAULT_CUSTOM_DIRECTIVES );
		if ( ! empty( $custom_directives ) ) {
			$custom_parts = $this->parse_custom_directives( $custom_directives );
			$directives   = array_merge( $directives, $custom_parts );
		}

		// Add report-uri if configured.
		$report_uri = get_option( OPTION_REPORT_URI, DEFAULT_REPORT_URI );
		if ( ! empty( $report_uri ) ) {
			$directives[] = 'report-uri ' . esc_url_raw( $report_uri );
		}

		return implode( '; ', $directives );
	}

	/**
	 * Build script-src directive.
	 *
	 * @since 0.1.0
	 *
	 * @param string $nonce Nonce value.
	 *
	 * @return string Script-src directive value.
	 */
	private function get_script_src_directive( string $nonce ): string {
		$sources = array();

		$use_strict_dynamic = (bool) filter_var(
			get_option( OPTION_USE_STRICT_DYNAMIC, DEFAULT_USE_STRICT_DYNAMIC ),
			FILTER_VALIDATE_BOOLEAN
		);

		$omit_unsafe_inline = (bool) filter_var(
			get_option( OPTION_OMIT_UNSAFE_INLINE, DEFAULT_OMIT_UNSAFE_INLINE ),
			FILTER_VALIDATE_BOOLEAN
		);

		$omit_scheme_fallback = (bool) filter_var(
			get_option( OPTION_OMIT_SCHEME_FALLBACK, DEFAULT_OMIT_SCHEME_FALLBACK ),
			FILTER_VALIDATE_BOOLEAN
		);

		$sources[] = "'nonce-{$nonce}'";

		if ( $use_strict_dynamic ) {
			$sources[] = "'strict-dynamic'";
		}

		// Only browsers without nonce support honour this; nonce-aware browsers ignore it.
		if ( ! $omit_unsafe_inline ) {
			$sources[] = "'unsafe-inline'";
		}

		// Add whitelisted domains.
		$whitelisted = $this->get_whitelisted_domains();
		if ( ! empty( $whitelisted ) ) {
			$sources = array_merge( $sources, $whitelisted );
		}

		// Fallback for browsers without strict-dynamic support; browsers with it ignore host sources.
		if ( $use_strict_dynamic && ! $omit_scheme_fallback ) {
			$sources[] = 'http:';
			$sources[] = 'https:';
		}

		return implode( ' ', $sources );
	}

	/**
	 * Build style-src directive.
	 *
	 * @since 0.1.0
	 *
	 * @param string $nonce Nonce value.
	 *
	 * @return string Style-src directive value.
	 */
	private function get_style_src_directive( string $nonce ): string {
		$sources = array();

		$sources[] = "'nonce-{$nonce}'";
		$sources[] = "'unsafe-inline'";

		// Add whitelisted domains.
		$whitelisted = $this->get_whitelisted_domains();
		if ( ! empty( $whitelisted ) ) {
			$sources = array_merge( $sources, $whitelisted );
		}

		return implode( ' ', $sources );
	}

	/**
	 * Parse custom directives from settings.
	 *
	 * @since 0.1.0
	 *
	 * @param string $custom Custom directives string.
	 *
	 * @return array<string> Array of directive strings.
	 */
	private function parse_custom_directives( string $custom ): array {
		$result = array();

		$lines = explode( "\n", $custom );

		foreach ( $lines as $line ) {
			$trimmed = trim( $line );

			if ( ! empty( $trimmed ) ) {
				$result[] = $trimmed;
			}
		}

		return $result;
	}

	/**
	 * Get whitelisted domains from settings.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string> Array of domain strings.
	 */
	private function get_whitelisted_domains(): array {
		$result  = array();
		$setting = get_option( OPTION_WHITELISTED_DOMAINS, DEFAULT_WHITELISTED_DOMAINS );

		if ( empty( $setting ) ) {
			return $result;
		}

		$lines = explode( "\n", $setting );

		foreach ( $lines as $line ) {
			$trimmed = trim( $line );

			if ( ! empty( $trimmed ) ) {
				$result[] = $trimmed;
			}
		}

		return $result;
	}
}
