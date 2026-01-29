<?php
/**
 * Help & Documentation Tab.
 *
 * @package Easy_CSP_Headers
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

// Panel wrapper.
echo '<div id="help-panel" class="tab-panel" style="display:none;">';

// What is CSP section.
printf( '<h2>%s</h2>', esc_html__( 'What is Content Security Policy (CSP)?', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'Content Security Policy is a security feature that helps prevent cross-site scripting (XSS) attacks by controlling which resources can be loaded and executed on your website.', 'easy-csp-headers' )
);

// How Nonces Work section.
printf( '<h2>%s</h2>', esc_html__( 'How Nonces Work', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'This plugin generates a unique nonce (random value) for each page load and adds it to all script and style tags. The same nonce is included in the CSP header, allowing only scripts with matching nonces to execute.', 'easy-csp-headers' )
);

// Understanding CSP Modes section.
printf( '<h2>%s</h2>', esc_html__( 'Understanding CSP Modes', 'easy-csp-headers' ) );
printf( '<h3>%s</h3>', esc_html__( 'Report-Only Mode', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'In Report-Only mode, CSP violations are logged to the browser console but NOT blocked. This is the safe way to test CSP without breaking your site. Use this mode to identify issues before enforcing the policy.', 'easy-csp-headers' )
);
printf(
	'<p><strong>%s</strong> %s</p>',
	esc_html__( 'Important:', 'easy-csp-headers' ),
	esc_html__( 'Report-Only mode does NOT provide security protection. It only logs violations. You must switch to Enforce mode to actually block malicious scripts.', 'easy-csp-headers' )
);

printf( '<h3>%s</h3>', esc_html__( 'Enforce Mode', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'In Enforce mode, CSP violations are actively blocked by the browser. This provides full security protection but may break functionality if CSP rules are too strict. Always test in Report-Only mode first.', 'easy-csp-headers' )
);

// Getting Started section.
printf( '<h2>%s</h2>', esc_html__( 'Getting Started - Step by Step', 'easy-csp-headers' ) );
echo '<ol>';
printf(
	'<li><strong>%s</strong> - %s</li>',
	esc_html__( 'Enable CSP Headers', 'easy-csp-headers' ),
	esc_html__( 'Turn on the master toggle in the General tab', 'easy-csp-headers' )
);
printf(
	'<li><strong>%s</strong> - %s</li>',
	esc_html__( 'Start with Report-Only mode', 'easy-csp-headers' ),
	esc_html__( 'This will log violations without breaking your site', 'easy-csp-headers' )
);
printf(
	'<li><strong>%s</strong> - %s</li>',
	esc_html__( 'Clear your cache', 'easy-csp-headers' ),
	esc_html__( 'If using a caching plugin, clear it now', 'easy-csp-headers' )
);
printf(
	'<li><strong>%s</strong> - %s</li>',
	esc_html__( 'Check browser console', 'easy-csp-headers' ),
	esc_html__( 'Visit your site and open Developer Tools (F12) to see CSP violation reports', 'easy-csp-headers' )
);
printf(
	'<li><strong>%s</strong> - %s</li>',
	esc_html__( 'Fix violations', 'easy-csp-headers' ),
	esc_html__( 'Add trusted domains to the whitelist or exclude problematic pages', 'easy-csp-headers' )
);
printf(
	'<li><strong>%s</strong> - %s</li>',
	esc_html__( 'Switch to Enforce mode', 'easy-csp-headers' ),
	esc_html__( 'Once no violations appear, change CSP Mode to "Enforce" for full protection', 'easy-csp-headers' )
);
printf(
	'<li><strong>%s</strong> - %s</li>',
	esc_html__( 'Clear cache again', 'easy-csp-headers' ),
	esc_html__( 'Always clear your cache after changing settings', 'easy-csp-headers' )
);
echo '</ol>';

// Common Issues section.
printf( '<h2>%s</h2>', esc_html__( 'Common Issues', 'easy-csp-headers' ) );
printf( '<h3>%s</h3>', esc_html__( 'Inline Event Handlers', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'Inline event handlers like onclick="..." will be blocked by strict CSP. These should be updated to use addEventListener() instead.', 'easy-csp-headers' )
);

printf( '<h3>%s</h3>', esc_html__( 'External Scripts', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'With strict-dynamic enabled, external scripts loaded by trusted scripts will automatically be allowed. You can also add specific domains to the whitelist.', 'easy-csp-headers' )
);

printf( '<h3>%s</h3>', esc_html__( 'Caching', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'This plugin works with page caching plugins. The nonce is generated when the cache is built and remains consistent until the cache is cleared.', 'easy-csp-headers' )
);
printf(
	'<p><strong>%s</strong> %s</p>',
	esc_html__( 'Critical:', 'easy-csp-headers' ),
	esc_html__( 'After changing ANY setting on this page, you MUST clear your page cache for the changes to take effect. If you do not see changes, check your admin bar for CSP status notifications.', 'easy-csp-headers' )
);

// Path Exclusions section.
printf( '<h2>%s</h2>', esc_html__( 'Path Exclusions', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'You can exclude specific paths from CSP processing using exact matches or wildcard patterns:', 'easy-csp-headers' )
);
echo '<ul>';
printf(
	'<li><strong>%s</strong> <code>/checkout/</code> %s</li>',
	esc_html__( 'Exact match:', 'easy-csp-headers' ),
	esc_html__( '- Only matches exactly /checkout/', 'easy-csp-headers' )
);
printf(
	'<li><strong>%s</strong> <code>/checkout/*</code> %s</li>',
	esc_html__( 'Wildcard:', 'easy-csp-headers' ),
	esc_html__( '- Matches /checkout/ and all subpages', 'easy-csp-headers' )
);
printf(
	'<li><strong>%s</strong> <code>/wp-admin/*</code> %s</li>',
	esc_html__( 'Example:', 'easy-csp-headers' ),
	esc_html__( '- Exclude entire admin area', 'easy-csp-headers' )
);
echo '</ul>';

// Developer Hooks section.
printf( '<h2>%s</h2>', esc_html__( 'Developer Hooks', 'easy-csp-headers' ) );
printf(
	'<p>%s</p>',
	esc_html__( 'Developers can programmatically control CSP processing using the ecsp_should_skip_csp filter:', 'easy-csp-headers' )
);

echo '<pre style="background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto;">';
echo "add_filter( 'ecsp_should_skip_csp', function( \$skip ) {\n";
echo "\tif ( is_singular( 'my_custom_post_type' ) ) {\n";
echo "\t\treturn true;\n";
echo "\t}\n";
echo "\treturn \$skip;\n";
echo '} );';
echo '</pre>';

echo '</div>'; // #help-panel
