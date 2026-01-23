<?php
/**
 * Plugin constants.
 *
 * All magic strings and numbers must be defined here.
 * Exception: Translatable text strings use __() or _e() directly.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

// ============================================================================
// WordPress Options Keys - Prefix: OPT_
// ============================================================================

/**
 * Master enable/disable toggle.
 *
 * @since 0.1.0
 */
const OPT_ENABLED = 'ecsp_enabled';

/**
 * CSP mode: 'enforce' or 'report-only'.
 *
 * @since 0.1.0
 */
const OPT_MODE = 'ecsp_mode';

/**
 * Enable CSP processing for logged-in users.
 *
 * @since 0.1.0
 */
const OPT_ENABLE_FOR_LOGGED_IN = 'ecsp_enable_for_logged_in';

/**
 * Process <style> tags with nonces.
 *
 * @since 0.1.0
 */
const OPT_PROCESS_STYLES = 'ecsp_process_styles';

/**
 * Use 'strict-dynamic' in CSP header.
 *
 * @since 0.1.0
 */
const OPT_USE_STRICT_DYNAMIC = 'ecsp_use_strict_dynamic';

/**
 * Use 'unsafe-hashes' in CSP header.
 *
 * @since 0.1.0
 */
const OPT_USE_UNSAFE_HASHES = 'ecsp_use_unsafe_hashes';

/**
 * Custom CSP directives (textarea).
 *
 * @since 0.1.0
 */
const OPT_CUSTOM_DIRECTIVES = 'ecsp_custom_directives';

/**
 * CSP Report URI.
 *
 * @since 0.1.0
 */
const OPT_REPORT_URI = 'ecsp_report_uri';

/**
 * Excluded paths (textarea, one per line).
 *
 * @since 0.1.0
 */
const OPT_EXCLUDED_PATHS = 'ecsp_excluded_paths';

/**
 * Whitelisted domains (textarea, one per line).
 *
 * @since 0.1.0
 */
const OPT_WHITELISTED_DOMAINS = 'ecsp_whitelisted_domains';

// ============================================================================
// Default Values - Prefix: DEF_
// ============================================================================

/**
 * Default enabled state.
 *
 * @since 0.1.0
 */
const DEF_ENABLED = false;

/**
 * Default CSP mode.
 *
 * @since 0.1.0
 */
const DEF_MODE = 'report-only';

/**
 * Default: Enable for logged-in users.
 *
 * @since 0.1.0
 */
const DEF_ENABLE_FOR_LOGGED_IN = false;

/**
 * Default: Process styles.
 *
 * @since 0.1.0
 */
const DEF_PROCESS_STYLES = false;

/**
 * Default: Use strict-dynamic.
 *
 * @since 0.1.0
 */
const DEF_USE_STRICT_DYNAMIC = true;

/**
 * Default: Use unsafe-hashes.
 *
 * @since 0.1.0
 */
const DEF_USE_UNSAFE_HASHES = false;

/**
 * Default custom directives.
 *
 * @since 0.1.0
 */
const DEF_CUSTOM_DIRECTIVES = '';

/**
 * Default report URI.
 *
 * @since 0.1.0
 */
const DEF_REPORT_URI = '';

/**
 * Default excluded paths.
 *
 * @since 0.1.0
 */
const DEF_EXCLUDED_PATHS = '';

/**
 * Default whitelisted domains.
 *
 * @since 0.1.0
 */
const DEF_WHITELISTED_DOMAINS = '';

// ============================================================================
// CSP Configuration Constants
// ============================================================================

/**
 * Allowed CSP modes.
 *
 * @since 0.1.0
 */
const ALLOWED_MODES = array( 'enforce', 'report-only' );

/**
 * Executable script types that need nonces.
 *
 * @since 0.1.0
 */
const EXECUTABLE_SCRIPT_TYPES = array(
	'text/javascript',
	'module',
	'application/javascript',
);

/**
 * Nonce byte length.
 *
 * @since 0.1.0
 */
const NONCE_BYTE_LENGTH = 20;
