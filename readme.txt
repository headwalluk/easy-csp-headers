=== Easy CSP Headers ===
Contributors: paulfaulkner
Tags: security, csp, content-security-policy, xss, headers
Requires at least: 6.4
Tested up to: 6.7
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically generates and injects Content Security Policy (CSP) headers with nonces for enhanced security.

== Description ==

Easy CSP Headers helps protect your WordPress site from cross-site scripting (XSS) attacks by automatically implementing Content Security Policy headers with nonce-based script validation.

= Features =

* **Automatic Nonce Generation** - Unique cryptographic nonce for every page load
* **Intelligent HTML Processing** - Uses WordPress's native WP_HTML_Tag_Processor for safe HTML manipulation
* **Strict-Dynamic Support** - Modern CSP with automatic script trust propagation
* **Report-Only Mode** - Test CSP without breaking your site
* **Flexible Configuration** - Granular control via intuitive settings page
* **Cache-Friendly Architecture** - Works seamlessly with popular caching plugins
* **Zero JavaScript Conflicts** - Compatible with WordPress core, themes, and plugins

= How It Works =

1. Captures HTML output before it's sent to the browser
2. Generates a cryptographically secure nonce
3. Automatically adds nonce attributes to all `<script>` tags
4. Sends CSP header with matching nonce
5. Browser only executes scripts with valid nonces

= Perfect For =

* Security-conscious site owners
* eCommerce sites handling sensitive data
* Membership and community sites
* Any WordPress site wanting enterprise-grade XSS protection

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/easy-csp-headers/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Navigate to Settings → CSP Headers
4. Enable the plugin and configure your preferences

== Frequently Asked Questions ==

= Will this break my site? =

Start with Report-Only mode to test without blocking anything. This logs violations in the browser console without breaking functionality. Once you've verified everything works, switch to Enforce mode.

= What about inline event handlers? =

Inline event handlers (onclick, onload, etc.) are blocked by strict CSP. You'll need to update legacy code to use addEventListener() instead. The plugin's Report-Only mode will show you exactly what needs fixing.

= Does this work with caching plugins? =

Yes! The nonce is generated when the cache is built. Both the HTML (with nonces) and CSP header are cached together, ensuring they always match.

= Can I whitelist external scripts? =

Yes. Add trusted domains in Settings → CSP Headers → CSP Rules → Whitelisted Domains.

= What is strict-dynamic? =

'strict-dynamic' is a modern CSP feature that automatically trusts scripts loaded by already-trusted scripts. This makes CSP much easier to maintain while still providing strong security.

= Do I need to modify my theme or plugins? =

Most modern themes and plugins work without modification. If you encounter issues in Report-Only mode, you can either fix the code or add it to the exclusions list.

= Can I programmatically skip CSP on certain pages? =

Yes! Use the `ecsp_should_skip_csp` filter:

`
add_filter( 'ecsp_should_skip_csp', function( $skip ) {
    if ( is_singular( 'my_custom_post_type' ) ) {
        return true;
    }
    return $skip;
} );
`

== Screenshots ==

1. General Settings - Enable/disable CSP and configure mode
2. CSP Rules - Configure security directives and whitelisting
3. Exclusions - Specify paths to skip CSP processing
4. Help Tab - Built-in documentation and troubleshooting

== Changelog ==

= 1.0.0 - 2026-01-23 =
* 🎉 Stable 1.0.0 release
* Added path exclusion system (exact match and wildcard support)
* Added developer filter ecsp_should_skip_csp for programmatic control
* Added translation .pot file
* Enhanced Help tab with path exclusions and developer hooks documentation
* Streamlined constants.php (removed redundant comments)
* All core features fully implemented and tested

= 0.3.0 - 2026-01-23 =
* Added Settings link on Plugins page for quick access
* Fixed settings page to preserve active tab after saving
* Tested with Autoptimize (asset aggregation/minification)
* Tested with page caching plugins
* Confirmed Grade A rating on securityheaders.com

= 0.2.0 - 2026-01-23 =
* Removed debug logging from production code
* Full WordPress Coding Standards compliance
* Added comprehensive documentation (README.md, readme.txt, CHANGELOG.md)
* Added phpcs.xml configuration

= 0.1.0 - 2026-01-23 =
* Initial release
* Automatic nonce generation and injection
* CSP header generation with strict-dynamic support
* Report-Only and Enforce modes
* Admin settings interface with tabbed navigation
* Whitelisted domains support
* Custom CSP directives
* Path exclusions
* Style tag processing (optional)
* Unsafe-hashes support for legacy code
* WordPress Coding Standards compliant

== Upgrade Notice ==

= 1.0.0 =
Stable release! Path exclusions now functional, developer filter added, Help tab enhanced.

= 0.3.0 =
Improved UX with Settings link and tab preservation. Tested with asset optimization plugins.

= 0.2.0 =
Cleaned production code and added documentation. No functional changes.

= 0.1.0 =
Initial release. Start with Report-Only mode to test without breaking your site.

== Technical Details ==

**Requirements:**
* WordPress 6.4+ (for WP_HTML_Tag_Processor)
* PHP 8.0+

**Architecture:**
* Zero database queries on frontend
* Uses output buffering for HTML capture
* Lazy-loaded class architecture
* Follows WordPress Coding Standards
* Full type safety with PHP 8.0+ type hints

**Security:**
* Cryptographically secure nonce generation (random_bytes)
* Nonce verification on every request
* No inline JavaScript or CSS in plugin code
* All user input sanitized and escaped

**Performance:**
* Minimal overhead (single output buffer callback)
* No external dependencies
* Cache-friendly design
* Compatible with object caching

== Support ==

For documentation, bug reports, and feature requests, visit [headwall-hosting.com](https://headwall-hosting.com/).

== Credits ==

Developed by [Paul Faulkner](https://headwall-hosting.com/)
