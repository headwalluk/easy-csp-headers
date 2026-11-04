<?php
/**
 * Plugin constants.
 *
 * All magic strings and numbers are defined here. Translatable strings use
 * the __() family directly.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

/**
 * Option names. Each value is stored data and never changes; see CLAUDE.md, Public Contracts.
 *
 * @since 0.1.0
 */
const OPTION_PREFIX                   = 'ecsp_';
const OPTION_ENABLED                  = 'ecsp_enabled';
const OPTION_MODE                     = 'ecsp_mode';
const OPTION_ENABLE_FOR_LOGGED_IN     = 'ecsp_enable_for_logged_in';
const OPTION_PROCESS_STYLES           = 'ecsp_process_styles';
const OPTION_USE_STRICT_DYNAMIC       = 'ecsp_use_strict_dynamic';
const OPTION_CUSTOM_DIRECTIVES        = 'ecsp_custom_directives';
const OPTION_REPORT_URI               = 'ecsp_report_uri';
const OPTION_EXCLUDED_PATHS           = 'ecsp_excluded_paths';
const OPTION_WHITELISTED_DOMAINS      = 'ecsp_whitelisted_domains';
const OPTION_DELETE_DATA_ON_UNINSTALL = 'ecsp_delete_data_on_uninstall';
const OPTION_OMIT_UNSAFE_INLINE       = 'ecsp_omit_unsafe_inline';
const OPTION_OMIT_SCHEME_FALLBACK     = 'ecsp_omit_scheme_fallback';

// Retired option names: may still be stored on existing sites, so never reuse them.
// ecsp_use_unsafe_hashes (removed after 1.1.1).

/**
 * Option defaults.
 *
 * @since 0.1.0
 */
const DEFAULT_ENABLED                  = false;
const DEFAULT_MODE                     = 'report-only';
const DEFAULT_ENABLE_FOR_LOGGED_IN     = false;
const DEFAULT_PROCESS_STYLES           = false;
const DEFAULT_USE_STRICT_DYNAMIC       = true;
const DEFAULT_CUSTOM_DIRECTIVES        = '';
const DEFAULT_REPORT_URI               = '';
const DEFAULT_EXCLUDED_PATHS           = '';
const DEFAULT_WHITELISTED_DOMAINS      = '';
const DEFAULT_DELETE_DATA_ON_UNINSTALL = false;
const DEFAULT_OMIT_UNSAFE_INLINE       = false;
const DEFAULT_OMIT_SCHEME_FALLBACK     = false;

/**
 * CSP configuration.
 *
 * @since 0.1.0
 */
const ALLOWED_MODES = array( 'enforce', 'report-only' );

const EXECUTABLE_SCRIPT_TYPES = array(
	'text/javascript',
	'module',
	'application/javascript',
);

const NONCE_BYTE_LENGTH = 20;
