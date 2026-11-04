<?php
/**
 * CSP Rules Settings Tab.
 *
 * @package Easy_CSP_Headers
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped.

// Panel wrapper.
echo '<div id="csp-rules-panel" class="tab-panel" style="display:none;">';

// Form table.
echo '<table class="form-table">';

// Use Strict-Dynamic row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Use Strict-Dynamic', 'easy-csp-headers' ) );
echo '<td>';
$strict_dynamic = (bool) filter_var(
	get_option( OPTION_USE_STRICT_DYNAMIC, DEFAULT_USE_STRICT_DYNAMIC ),
	FILTER_VALIDATE_BOOLEAN
);
printf(
	'<label><input type="checkbox" id="ecsp-use-strict-dynamic" name="%s" value="1" %s> %s</label>',
	esc_attr( OPTION_USE_STRICT_DYNAMIC ),
	checked( $strict_dynamic, true, false ),
	esc_html__( "Use 'strict-dynamic' in script-src directive", 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Recommended for best security. Allows scripts loaded by trusted scripts.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Omit Unsafe-Inline row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Omit Unsafe-Inline Fallback', 'easy-csp-headers' ) );
echo '<td>';
$omit_unsafe_inline = (bool) filter_var(
	get_option( OPTION_OMIT_UNSAFE_INLINE, DEFAULT_OMIT_UNSAFE_INLINE ),
	FILTER_VALIDATE_BOOLEAN
);
printf(
	'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
	esc_attr( OPTION_OMIT_UNSAFE_INLINE ),
	checked( $omit_unsafe_inline, true, false ),
	esc_html__( "Remove 'unsafe-inline' from the script-src directive", 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( "Current browsers already ignore 'unsafe-inline' when a nonce is present. It only matters to very old browsers without nonce support, where removing it blocks every inline script.", 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Omit Scheme Fallback row. Only has an effect while strict-dynamic is on.
echo '<tr class="ecsp-depends-on-strict-dynamic">';
printf( '<th scope="row">%s</th>', esc_html__( 'Omit http:/https: Fallback', 'easy-csp-headers' ) );
echo '<td>';
$omit_scheme_fallback = (bool) filter_var(
	get_option( OPTION_OMIT_SCHEME_FALLBACK, DEFAULT_OMIT_SCHEME_FALLBACK ),
	FILTER_VALIDATE_BOOLEAN
);
printf(
	'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
	esc_attr( OPTION_OMIT_SCHEME_FALLBACK ),
	checked( $omit_scheme_fallback, true, false ),
	esc_html__( 'Remove the http: and https: sources added alongside strict-dynamic', 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Browsers that support strict-dynamic ignore these. On older browsers without it (such as Safari before 15.4), removing them blocks scripts that trusted scripts load, such as analytics and chat widgets. Only applies when Use Strict-Dynamic is on.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Process Styles row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Process Styles', 'easy-csp-headers' ) );
echo '<td>';
$process_styles = (bool) filter_var(
	get_option( OPTION_PROCESS_STYLES, DEFAULT_PROCESS_STYLES ),
	FILTER_VALIDATE_BOOLEAN
);
printf(
	'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
	esc_attr( OPTION_PROCESS_STYLES ),
	checked( $process_styles, true, false ),
	esc_html__( 'Add nonces to <style> tags and include style-src directive', 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Enable if you want to restrict inline styles as well as scripts.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Whitelisted Domains row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Whitelisted Domains', 'easy-csp-headers' ) );
echo '<td>';
$whitelisted = get_option( OPTION_WHITELISTED_DOMAINS, DEFAULT_WHITELISTED_DOMAINS );
printf(
	'<textarea name="%s" rows="5" cols="50" class="large-text code">%s</textarea>',
	esc_attr( OPTION_WHITELISTED_DOMAINS ),
	esc_textarea( $whitelisted )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Add trusted domains (one per line). Example: https://cdn.example.com', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Custom CSP Directives row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Custom CSP Directives', 'easy-csp-headers' ) );
echo '<td>';
$custom = get_option( OPTION_CUSTOM_DIRECTIVES, DEFAULT_CUSTOM_DIRECTIVES );
printf(
	'<textarea name="%s" rows="5" cols="50" class="large-text code">%s</textarea>',
	esc_attr( OPTION_CUSTOM_DIRECTIVES ),
	esc_textarea( $custom )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Add custom CSP directives (one per line). Example: img-src * data:', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Report URI row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Report URI', 'easy-csp-headers' ) );
echo '<td>';
$report_uri = get_option( OPTION_REPORT_URI, DEFAULT_REPORT_URI );
printf(
	'<input type="url" name="%s" value="%s" class="regular-text code">',
	esc_attr( OPTION_REPORT_URI ),
	esc_attr( $report_uri )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'URL where CSP violation reports should be sent.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

echo '</table>'; // .form-table

submit_button();

echo '</div>'; // #csp-rules-panel

// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
