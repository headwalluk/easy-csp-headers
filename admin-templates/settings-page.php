<?php
/**
 * Settings page template.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped.
// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited -- $mode is locally scoped variable, not overriding WP global.

// Wrapper and page title.
printf( '<div class="wrap"><h1>%s</h1>', esc_html( get_admin_page_title() ) );

// Tab navigation.
echo '<nav class="nav-tab-wrapper wp-clearfix">';
printf(
	'<a href="#general" class="nav-tab nav-tab-active" data-tab="general">%s</a>',
	esc_html__( 'General', 'easy-csp-headers' )
);
printf(
	'<a href="#csp-rules" class="nav-tab" data-tab="csp-rules">%s</a>',
	esc_html__( 'CSP Rules', 'easy-csp-headers' )
);
printf(
	'<a href="#exclusions" class="nav-tab" data-tab="exclusions">%s</a>',
	esc_html__( 'Exclusions', 'easy-csp-headers' )
);
printf(
	'<a href="#help" class="nav-tab" data-tab="help">%s</a>',
	esc_html__( 'Help', 'easy-csp-headers' )
);
echo '</nav>';

// Form wrapper.
printf( '<form method="post" action="%s">', esc_url( admin_url( 'options.php' ) ) );
settings_fields( 'ecsp_settings' );
echo '<input type="hidden" name="ecsp_active_tab" class="ecsp-active-tab" value="general">';
echo '<div class="tab-content">';

// Include tab content files.
require __DIR__ . '/tabs/general-tab.php';
require __DIR__ . '/tabs/csp-rules-tab.php';
require __DIR__ . '/tabs/exclusions-tab.php';
require __DIR__ . '/tabs/help-tab.php';

// Close wrappers.
echo '</div>'; // .tab-content
echo '</form>';
echo '</div>'; // .wrap

// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
// phpcs:enable WordPress.WP.GlobalVariablesOverride.Prohibited
