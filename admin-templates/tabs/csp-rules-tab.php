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
	get_option( OPT_USE_STRICT_DYNAMIC, DEF_USE_STRICT_DYNAMIC ),
	FILTER_VALIDATE_BOOLEAN
);
printf(
	'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
	esc_attr( OPT_USE_STRICT_DYNAMIC ),
	checked( $strict_dynamic, true, false ),
	esc_html__( "Use 'strict-dynamic' in script-src directive", 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Recommended for best security. Allows scripts loaded by trusted scripts.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Use Unsafe-Hashes row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Use Unsafe-Hashes', 'easy-csp-headers' ) );
echo '<td>';
$unsafe_hashes = (bool) filter_var(
	get_option( OPT_USE_UNSAFE_HASHES, DEF_USE_UNSAFE_HASHES ),
	FILTER_VALIDATE_BOOLEAN
);
printf(
	'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
	esc_attr( OPT_USE_UNSAFE_HASHES ),
	checked( $unsafe_hashes, true, false ),
	esc_html__( "Allow inline event handlers with 'unsafe-hashes'", 'easy-csp-headers' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Less secure. Allows onclick, onload, etc. Use only if needed for legacy code.', 'easy-csp-headers' )
);
echo '</td>';
echo '</tr>';

// Process Styles row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Process Styles', 'easy-csp-headers' ) );
echo '<td>';
$process_styles = (bool) filter_var(
	get_option( OPT_PROCESS_STYLES, DEF_PROCESS_STYLES ),
	FILTER_VALIDATE_BOOLEAN
);
printf(
	'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
	esc_attr( OPT_PROCESS_STYLES ),
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
$whitelisted = get_option( OPT_WHITELISTED_DOMAINS, DEF_WHITELISTED_DOMAINS );
printf(
	'<textarea name="%s" rows="5" cols="50" class="large-text code">%s</textarea>',
	esc_attr( OPT_WHITELISTED_DOMAINS ),
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
$custom = get_option( OPT_CUSTOM_DIRECTIVES, DEF_CUSTOM_DIRECTIVES );
printf(
	'<textarea name="%s" rows="5" cols="50" class="large-text code">%s</textarea>',
	esc_attr( OPT_CUSTOM_DIRECTIVES ),
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
$report_uri = get_option( OPT_REPORT_URI, DEF_REPORT_URI );
printf(
	'<input type="url" name="%s" value="%s" class="regular-text code">',
	esc_attr( OPT_REPORT_URI ),
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
