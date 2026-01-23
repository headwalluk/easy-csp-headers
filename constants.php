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

// WordPress Options Keys - Prefix: OPT_.
const OPT_ENABLED              = 'ecsp_enabled';
const OPT_MODE                 = 'ecsp_mode';
const OPT_ENABLE_FOR_LOGGED_IN = 'ecsp_enable_for_logged_in';
const OPT_PROCESS_STYLES       = 'ecsp_process_styles';
const OPT_USE_STRICT_DYNAMIC   = 'ecsp_use_strict_dynamic';
const OPT_USE_UNSAFE_HASHES    = 'ecsp_use_unsafe_hashes';
const OPT_CUSTOM_DIRECTIVES    = 'ecsp_custom_directives';
const OPT_REPORT_URI           = 'ecsp_report_uri';
const OPT_EXCLUDED_PATHS       = 'ecsp_excluded_paths';
const OPT_WHITELISTED_DOMAINS  = 'ecsp_whitelisted_domains';

// Default Values - Prefix: DEF_.
const DEF_ENABLED              = false;
const DEF_MODE                 = 'report-only';
const DEF_ENABLE_FOR_LOGGED_IN = false;
const DEF_PROCESS_STYLES       = false;
const DEF_USE_STRICT_DYNAMIC   = true;
const DEF_USE_UNSAFE_HASHES    = false;
const DEF_CUSTOM_DIRECTIVES    = '';
const DEF_REPORT_URI           = '';
const DEF_EXCLUDED_PATHS       = '';
const DEF_WHITELISTED_DOMAINS  = '';

// CSP Configuration Constants.
const ALLOWED_MODES = array( 'enforce', 'report-only' );

const EXECUTABLE_SCRIPT_TYPES = array(
	'text/javascript',
	'module',
	'application/javascript',
);

const NONCE_BYTE_LENGTH = 20;
