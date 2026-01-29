<?php
/**
 * Exclusions Settings Tab.
 *
 * @package Easy_CSP_Headers
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped.

// Panel wrapper.
echo '<div id="exclusions-panel" class="tab-panel" style="display:none;">';

// Form table.
echo '<table class="form-table">';

// Excluded Paths row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Excluded Paths', 'easy-csp-headers' ) );
echo '<td>';
$excluded = get_option( OPT_EXCLUDED_PATHS, DEF_EXCLUDED_PATHS );
printf(
	'<textarea name="%s" rows="8" cols="50" class="large-text code">%s</textarea>',
	esc_attr( OPT_EXCLUDED_PATHS ),
	esc_textarea( $excluded )
);
printf(
	'<p class="description">%s<br>%s<br>%s<br>%s</p>',
	esc_html__( 'Paths to exclude from CSP processing (one per line). Examples:', 'easy-csp-headers' ),
	esc_html( '/checkout/' ),
	esc_html( '/wp-admin/*' ),
	esc_html( '/my-account/*' )
);
echo '</td>';
echo '</tr>';

echo '</table>'; // .form-table

submit_button();

echo '</div>'; // #exclusions-panel

// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
