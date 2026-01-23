# Changelog

All notable changes to Easy CSP Headers will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.0] - 2026-01-23

### Changed
- Removed debug error_log() calls from production code
- Code now passes WordPress Coding Standards (phpcs) with zero violations

### Added
- README.md for Git/GitHub documentation
- readme.txt for WordPress.org format
- CHANGELOG.md for version tracking
- phpcs.xml configuration file

## [0.1.0] - 2026-01-23

### Added
- Initial release of Easy CSP Headers
- Automatic nonce generation using cryptographically secure random_bytes()
- HTML processing with WordPress WP_HTML_Tag_Processor
- CSP header injection with configurable directives
- Admin settings page with tabbed interface (General, CSP Rules, Exclusions, Help)
- Report-Only mode for safe testing
- Enforce mode for production security
- Strict-dynamic support for modern CSP
- Unsafe-hashes support for legacy inline event handlers
- Style tag processing (optional)
- Whitelisted domains configuration
- Custom CSP directives support
- Report URI configuration for violation reporting
- Path exclusions for selective CSP application
- Enable/disable toggle for logged-in users
- Cache-friendly architecture
- WordPress Coding Standards compliance
- Full PHP 8.0+ type safety
- Comprehensive inline documentation

### Technical
- WordPress 6.4+ requirement (WP_HTML_Tag_Processor)
- PHP 8.0+ requirement
- Namespace: `Easy_CSP_Headers`
- Plugin prefix: `ecsp_` and `ECSP_`
- SESE (Single-Entry Single-Exit) function pattern
- Lazy-loaded class architecture
- No database queries on frontend
- Output buffering for HTML capture

### Security
- Nonce-based script validation
- Input sanitization on all settings
- Output escaping in templates
- Capability checks on all admin functions
- No inline JavaScript or CSS
- Base64 encoding for nonce (CSP standard)

### Documentation
- README.md for developers
- readme.txt for WordPress.org format
- CHANGELOG.md for version tracking
- Inline PHPDoc comments throughout
- Built-in help tab in admin interface
- Development notes in dev-notes/ directory

## [Unreleased]

### Planned Features
- CSP violation reporting endpoint (store in custom table)
- Dashboard widget showing recent violations
- WP-CLI commands for CSP testing
- Support for multiple CSP policies per post type
- Automatic inline event handler migration tool
- Integration with popular security plugins
- Performance metrics and statistics
- Export/import settings functionality

### Under Consideration
- Support for hash-based CSP (alternative to nonces)
- Automatic hash generation for inline scripts
- Integration with WordPress Site Health
- REST API endpoint for programmatic access
- Multisite support with network-wide settings

---

## Version History

- **[0.2.0]** - 2026-01-23 - Code standards compliance and documentation
- **[0.1.0]** - 2026-01-23 - Initial Release

---

## Upgrade Guide

### To 0.1.0 (Initial Release)
- Fresh installation
- Configure settings at Settings → CSP Headers
- Start with Report-Only mode
- Review browser console for violations
- Switch to Enforce mode when ready

---

## Notes

- All dates in YYYY-MM-DD format
- Version numbers follow Semantic Versioning
- Changes grouped by type: Added, Changed, Deprecated, Removed, Fixed, Security
