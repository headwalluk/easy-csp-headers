<?php
/**
 * General Settings Tab.
 *
 * @package Easy_CSP_Headers
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped.
// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited -- $mode is locally scoped variable, not overriding WP global.

// Panel wrapper.
echo '<div id="general-panel" class="tab-panel active">';

// Form table.
echo '<table class="form-table">';

// Enable CSP Headers row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Enable CSP Headers', 'easy-csp-headers' ) );
echo '<td>';
$enabled = (bool) filter_var( get_option( OPT_ENABLED, DEF_ENABLED ), FILTER_VALIDATE_BOOLEAN );
printf(
	'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
	esc_attr( OPT_ENABLED ),
	checked( $enabled, true, false ),
	esc_html__( 'Enable Content Security Policy processing', 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Master switch to enable or disable CSP header injection.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// CSP Mode row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'CSP Mode', 'easy-csp-headers' ) );
echo '<td>';
$mode = get_option( OPT_MODE, DEF_MODE );
printf(
	'<label><input type="radio" name="%s" value="report-only" %s> %s</label><br>',
	esc_attr( OPT_MODE ),
	checked( $mode, 'report-only', false ),
	esc_html__( 'Report-Only (violations logged, not blocked)', 'easy-csp-headers' )
);
printf(
	'<label><input type="radio" name="%s" value="enforce" %s> %s</label>',
	esc_attr( OPT_MODE ),
	checked( $mode, 'enforce', false ),
	esc_html__( 'Enforce (violations blocked)', 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Start with Report-Only mode to test without breaking your site.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Enable for Logged-in Users row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Enable for Logged-in Users', 'easy-csp-headers' ) );
echo '<td>';
$enable_logged_in = (bool) filter_var(
	get_option( OPT_ENABLE_FOR_LOGGED_IN, DEF_ENABLE_FOR_LOGGED_IN ),
	FILTER_VALIDATE_BOOLEAN
);
printf(
	'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
	esc_attr( OPT_ENABLE_FOR_LOGGED_IN ),
	checked( $enable_logged_in, true, false ),
	esc_html__( 'Apply CSP headers when users are logged in', 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'By default, CSP is disabled for logged-in users. Enable this to test while authenticated.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

echo '</table>'; // .form-table

// Test CSP Headers section.
printf( '<h2>%s</h2>', esc_html__( 'Test CSP Headers', 'easy-csp-headers' ) );
printf( '<p>%s</p>', esc_html__( 'Verify that CSP headers are being sent correctly by your server.', 'easy-csp-headers' ) );
printf(
	'<p><button type="button" id="ecsp-test-headers" class="button button-secondary">%s</button><span class="spinner" style="float:none;margin:0 0 0 10px;"></span></p>',
	esc_html__( 'Test CSP Headers', 'easy-csp-headers' )
);
echo '<div id="ecsp-test-results" style="display:none;margin-top:15px;"></div>';

submit_button();

echo '</div>'; // #general-panel

// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
// phpcs:enable WordPress.WP.GlobalVariablesOverride.Prohibited
