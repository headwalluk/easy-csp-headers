# Easy CSP Headers

![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4.svg?logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)
![Version](https://img.shields.io/badge/Version-1.1.0-orange.svg)
![Code Standards](https://img.shields.io/badge/Code%20Standards-WordPress-brightgreen.svg)

**Version:** 1.1.0  
**Author:** Paul Faulkner  
**License:** GPL v2 or later

A WordPress plugin that automatically generates and injects Content Security Policy (CSP) headers with nonces for enhanced security.

## Features

- ✅ **Automatic Nonce Generation** - Unique nonce for every page load
- ✅ **HTML Processing** - Uses WordPress `WP_HTML_Tag_Processor` for safe, fast HTML manipulation
- ✅ **Strict-Dynamic Support** - Modern CSP with automatic script trust propagation
- ✅ **Report-Only Mode** - Test CSP without breaking your site
- ✅ **Flexible Configuration** - Granular control via settings page
- ✅ **Cache-Friendly** - Works seamlessly with page caching plugins
- ✅ **WordPress Standards** - Follows WordPress Coding Standards

## How It Works

1. **Captures Output** - Hooks into `template_redirect` and starts output buffering
2. **Generates Nonce** - Creates a cryptographically secure nonce
3. **Processes HTML** - Adds nonce attributes to `<script>` and `<style>` tags
4. **Injects Header** - Sends CSP header with matching nonce
5. **Returns HTML** - Modified HTML with nonces is served to the browser

## Requirements

- **WordPress:** 6.4 or higher
- **PHP:** 8.0 or higher
- **WP_HTML_Tag_Processor:** Built into WordPress 6.4+

## Installation

1. Upload the `easy-csp-headers` directory to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure settings at **Settings → CSP Headers**

## Configuration

### General Settings

- **Enable CSP Headers** - Master on/off switch
- **CSP Mode** - Report-Only (log violations) or Enforce (block violations)
- **Enable for Logged-in Users** - Apply CSP when users are authenticated

### CSP Rules

- **Use Strict-Dynamic** - Enable modern CSP with automatic trust propagation
- **Use Unsafe-Hashes** - Allow inline event handlers (less secure, for legacy code)
- **Process Styles** - Add nonces to `<style>` tags and include style-src directive
- **Whitelisted Domains** - Add trusted external domains
- **Custom CSP Directives** - Add additional CSP rules
- **Report URI** - URL to receive CSP violation reports

### Exclusions

- **Excluded Paths** - Paths where CSP should not be applied

## Getting Started

1. **Start with Report-Only mode** to test without breaking your site
2. Open browser console and check for CSP violation reports
3. Add trusted domains to the whitelist if needed
4. Fix or exclude problematic code (e.g., inline event handlers)
5. Once satisfied, switch to Enforce mode

## Common Issues

### Inline Event Handlers

Inline event handlers like `onclick="..."` will be blocked by strict CSP. Update code to use `addEventListener()` instead:

```javascript
// ❌ Bad
<button onclick="handleClick()">Click Me</button>

// ✅ Good
<button class="my-button">Click Me</button>
<script>
  document.querySelector('.my-button').addEventListener('click', handleClick);
</script>
```

### External Scripts

With `'strict-dynamic'` enabled, external scripts loaded by trusted scripts are automatically allowed. You can also add specific domains to the whitelist.

### Caching

This plugin works with page caching. The nonce is generated when the cache is built and remains consistent until the cache is cleared. Both the HTML (with nonces) and CSP header are cached together.

## Development

### File Structure

```
easy-csp-headers/
├── easy-csp-headers.php      # Main plugin file
├── constants.php              # Plugin constants
├── functions-private.php      # Helper functions
├── phpcs.xml                  # Code standards config
├── includes/
│   ├── class-plugin.php       # Main orchestrator
│   ├── class-csp-processor.php
│   ├── class-output-buffer.php
│   ├── class-settings.php
│   └── class-admin-hooks.php
├── admin-templates/
│   └── settings-page.php      # Settings UI
├── assets/admin/
│   ├── admin-styles.css
│   └── admin-scripts.js
└── dev-notes/                 # Development documentation
```

### Developer Hooks

**Filter: `ecsp_should_skip_csp`**

Allows developers to programmatically control when CSP processing is skipped.

```php
/**
 * Skip CSP on custom post type single pages.
 *
 * @param bool $skip Whether to skip CSP processing.
 * @return bool
 */
add_filter( 'ecsp_should_skip_csp', function( $skip ) {
    if ( is_singular( 'my_custom_post_type' ) ) {
        return true;
    }
    return $skip;
} );
```

### Code Standards

```bash
# Check for violations
phpcs

# Auto-fix issues
phpcbf
```

## Support

For issues, questions, or contributions, visit [headwall-hosting.com](https://headwall-hosting.com/).

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.

## License

GPL v2 or later. See [LICENSE](LICENSE) for full text.
