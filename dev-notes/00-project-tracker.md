# Easy CSP Headers - Project Tracker

**Version:** 1.1.0
**Last Updated:** 29 January 2026
**Current Phase:** Stable Release
**Overall Progress:** 100% (Core Features Complete)

---

## Overview

WordPress plugin that automatically generates and injects Content Security Policy (CSP) headers with nonces for enhanced security. The plugin captures HTML output, adds nonces to script and style tags using WP_HTML_Tag_Processor, and sends appropriate CSP headers.

**Core Features:**
- Automatic nonce generation and injection
- CSP header generation (Enforce & Report-Only modes)
- Script and style tag processing
- Granular control via settings page
- Path and user-based exclusions
- Report-Only mode for safe testing

---

## Active TODO Items

**Version 1.0.0 Released! 🎉**

All core features complete and production-ready.

**Completed:**
- [x] Core plugin foundation and structure
- [x] CSP processing engine with nonce generation
- [x] Settings infrastructure and admin interface
- [x] WordPress Coding Standards compliance
- [x] Documentation (README, readme.txt, CHANGELOG)
- [x] Plugin action links and tab preservation UX improvements
- [x] Tested with Autoptimize and page caching (Grade A on securityheaders.com)
- [x] Basic path exclusion with exact match and wildcard support
- [x] Translation .pot file generated
- [x] .gitignore created
- [x] Developer filter ecsp_should_skip_csp
- [x] Help tab enhanced with examples
- [x] Clean WordPress install testing
- [x] Version 1.0.0 release

**Deferred to Phase 2:**
- [ ] Dedicated Exclusion_Manager class with advanced pattern matching
- [ ] Regex pattern support for path exclusions
- [ ] Extensive theme/plugin compatibility testing
- [ ] CSP violation reporting endpoint and storage
- [ ] Dashboard widget for violation monitoring
- [ ] WP-CLI commands
- [ ] Inline event handler migration tools

---

## Milestones

### Milestone 1: Plugin Foundation ✅ COMPLETE
**Goal:** Basic plugin structure, constants, and file organization

- [x] Create main plugin file (`easy-csp-headers.php`)
  - [x] Plugin header with metadata
  - [x] ABSPATH check
  - [x] Define plugin constants (VERSION, DIR, URL)
  - [x] HPOS compatibility declaration (if needed)
  - [x] Initialize plugin instance
- [x] Create `constants.php`
  - [x] Option keys (prefixed with `OPT_`)
  - [x] Default values (prefixed with `DEF_`)
  - [x] Script type whitelist constants
  - [x] CSP directive defaults
- [x] Create `functions-private.php`
  - [x] Helper function: `should_skip_csp()`
  - [x] Helper function: `get_plugin_instance()`
  - [x] Helper function: `is_report_only_mode()`
- [x] Create `phpcs.xml`
  - [x] Configure WordPress Coding Standards
  - [x] Set plugin-specific prefixes
  - [x] Exclude patterns (vendor, node_modules, assets)
- [x] Create `.gitignore`
  - [x] Common WordPress plugin exclusions

### Milestone 2: Core CSP Processing Engine ✅ COMPLETE
**Goal:** HTML processing, nonce generation, and CSP header injection

- [x] Create `includes/class-csp-processor.php`
  - [x] Method: `process_output( string $html ): string`
  - [x] Method: `generate_nonce(): string`
  - [x] Method: `add_nonce_to_scripts( WP_HTML_Tag_Processor $tags, string $nonce ): void`
  - [x] Method: `add_nonce_to_styles( WP_HTML_Tag_Processor $tags, string $nonce ): void`
  - [x] Method: `should_process_script( ?string $type ): bool`
  - [x] Method: `inject_csp_header( string $nonce ): void`
  - [x] Method: `build_csp_header_value( string $nonce ): string`
- [x] Create `includes/class-output-buffer.php`
  - [x] Method: `start_buffer(): void` (hooks template_redirect)
  - [x] Method: `process_buffer_callback( string $html ): string`
  - [x] Integration with CSP_Processor class
- [x] Test basic nonce injection on simple HTML
- [x] Test script type filtering (skip JSON-LD, templates, etc.)
- [x] Verify headers are sent before output

### Milestone 3: Settings Infrastructure ✅ COMPLETE
**Goal:** WordPress Settings API integration and option storage

- [x] Create `includes/class-settings.php`
  - [x] Method: `register_settings(): void`
  - [x] Method: `add_settings_sections(): void` (not needed - inline)
  - [x] Method: `add_settings_fields(): void` (not needed - inline)
  - [x] Method: `sanitize_checkbox( mixed $value ): bool`
  - [x] Method: `sanitize_text_field_option( mixed $value ): string` (sanitize_mode)
  - [x] Method: `sanitize_textarea_option( mixed $value ): string`
  - [x] Method: `sanitize_mode_option( mixed $value ): string`
- [x] Register all settings with defaults
  - [x] Master enable/disable toggle
  - [x] CSP mode (enforce vs report-only)
  - [x] Enable for logged-in users toggle
  - [x] Process styles toggle
  - [x] Use strict-dynamic toggle
  - [x] Use unsafe-hashes toggle (added)
  - [x] Custom CSP directives (textarea)
  - [x] Report URI (text input)
  - [x] Excluded paths (textarea, one per line)
  - [x] Whitelisted domains (textarea)
- [x] Create setting field render callbacks (inline in template)
- [x] Test option saving and retrieval

### Milestone 4: Admin Interface ✅ COMPLETE
**Goal:** User-friendly settings page with tabbed navigation

- [x] Create `includes/class-admin-hooks.php`
  - [x] Method: `add_menu_items(): void`
  - [x] Method: `enqueue_admin_assets( string $hook_suffix ): void`
  - [x] Method: `render_settings_page(): void`
  - [x] Conditional asset loading (only on plugin pages)
- [x] Create `admin-templates/settings-page.php`
  - [x] Tab navigation markup (General, CSP Rules, Exclusions, Help)
  - [x] Tab panels with settings sections
  - [x] Use printf/echo pattern (no inline HTML)
  - [x] Include nonce field for settings form
- [x] Create `assets/admin/admin-styles.css`
  - [x] Tab panel styling
  - [x] Settings field layouts
  - [x] Help text formatting
- [x] Create `assets/admin/admin-scripts.js`
  - [x] Hash-based tab navigation
  - [x] Tab state preservation on page reload
  - [x] Browser back/forward support
- [x] Design settings page layout
  - [x] **General Tab:** Enable/disable, mode, logged-in users
  - [x] **CSP Rules Tab:** Strict-dynamic, unsafe-hashes, custom directives, report URI
  - [x] **Exclusions Tab:** Paths, whitelisted domains
  - [xCreate `assets/admin/admin-scripts.js`
  - [ ] Hash-based tab navigation
  - [ ] Tab state preservation on page reload
  - [ ] Browser back/forward support
- [ ] Design settings page layout
  - [ ] **General Tab:** Enable/disable, mode, logged-in users
  - [ ] **CSP Rules Tab:** Strict-dynamic, custom directives, report URI
  - [ ] **Exclusions Tab:** Paths, whitelisted domains
  - [ ] **Help Tab:** Documentation, examples, troubleshooting

### Milestone 5: Exclusion System ⚠️ PARTIAL (Basic Matching Complete)
**Goal:** Path-based and user-based filtering

- [ ] Create `includes/class-exclusion-manager.php` (deferred to Phase 2)
  - [ ] Method: `should_skip_request(): bool`
  - [ ] Method: `is_excluded_path( string $request_uri ): bool`
  - [ ] Method: `is_excluded_user(): bool`
  - [ ] Method: `parse_excluded_paths(): array`
  - [ ] Method: `normalize_path_pattern( string $pattern ): string`
- [x] Implement path pattern matching
  - [x] Support exact matches (/checkout/)
  - [x] Support wildcard patterns (/checkout/*)
  - [ ] Support regex patterns (deferred to Phase 2)
- [x] Implement user role checks
  - [x] Skip if user is logged in (when setting disabled)
  - [x] Skip admin area (is_admin() check)
- [x] Basic exclusion logic in should_skip_csp() function
✅ COMPLETE
**Goal:** Flexible CSP header generation from settings

**Note:** Integrated into class-csp-processor.php instead of separate class

- [x] Build complete CSP header from settings
  - [x] Method: `build_csp_header_value( string $nonce ): string`
  - [x] Method: `get_script_src_directive( string $nonce ): string`
  - [x] Method: `get_style_src_directive( string $nonce ): string`
  - [x] Method: `parse_custom_directives( string $custom ): array`
  - [x] Method: `get_whitelisted_domains(): array`
- [x] Implement strict-dynamic CSP generation
  - [x] Include 'strict-dynamic' when enabled
  - [x] Add 'unsafe-inline' fallback for older browsers
  - [x] Add 'unsafe-hashes' when enabled
  - [x] Add http: https: fallback
- [x] Support custom directive merging
- [x] Add Report-Only mode header support

### Milestone 7: Plugin Integration & Hooks ✅ COMPLETE
**Goal:** Main plugin class and WordPress integration

- [x] Create `includes/class-plugin.php`
  - [x] Property: `private ?CSP_Processor $processor`
  - [x] Property: `private ?Settings $settings`
  - [x] Property: `private ?Admin_Hooks $admin_hooks`
  - [x] Property: `private ?Output_Buffer $output_buffer`
  - [x] Method: `run(): void` (register all hooks)
  - [x] Method: `get_settings(): Settings` (lazy load)
  - [x] Method: `get_processor(): CSP_Processor` (lazy load)
  - [x] Method: `get_admin_hooks(): Admin_Hooks` (lazy load)
  - [x] Method: `get_output_buffer(): Output_Buffer` (lazy load)
  - [x] Getter methods for all dependencies
- [x] Hook registration in `run()` method
  - [x] Settings instantiate early (before admin_init)
  - [x] Admin hooks for menu and assets
  - [x] Output buffer hooks for CSP processing
- [x] Global instance pattern in main plugin file
- [x] Global instance pattern in main plugin file
- [ ] Test plugin activation/deactivation
⏳ IN PROGRESS
**Goal:** Comprehensive testing and debug tools

- [x] Manual testing checklist
  - [x] Test with default settings
  - [x] Test with custom CSP directives
  - [ ] Test path exclusions work correctly (needs implementation)
  - [x] Test logged-in user filtering
  - [x] Test Report-Only mode
  - [ ] Test with caching plugins (WP Super Cache, W3TC)
  - [x] Test inline scripts get nonces
  - [x] Test external scripts get nonces
  - [x] Test JSON-LD scripts are skipped
  - [x] Test inline styles (when enabled)
- [x] Browser console CSP violation testing
- [ ] Test with common themes (Twenty Twenty-Four, etc.)
- [ ] Test with common plugins (WooCommerce, Contact Form 7)
- [ ] Add error logging for debug mode (optional future enhancement)
- [ ] Create test pages with various script types

### Milestone 9: Documentation ✅ COMPLETE
**Goal:** User and developer documentation

- [x] Create `README.md`
  - [x] Plugin description and features
  - [x] Installation instructions
  - [x] Basic usage guide
  - [x] FAQ section
  - [x] Troubleshooting guide
- [x] Create `readme.txt`
  - [x] WordPress.org format
  - [x] Description and features
  - [x] Installation and FAQ
  - [x] Changelog
- [x] Create `CHANGELOG.md`
  - [x] Version history
  - [x] Semantic versioning
- [ ] Create `dev-notes/.instructions.md` (optional)
  - [ ] Project-specific architecture notes
  - [ ] Class relationship diagram
  - [ ] Hook execution order
  - [ ] Caching considerations
  - [ ] Known limitations
- [x] Add inline documentation
  - [x] DocBlocks for all public methods
  - [x] Complex logic explanations
  - [x] Security considerations
- [x] Create Help tab content in admin
  - [x] What is CSP?
  - [x] How nonces work
  - [x] Common issues and solutions
  - [x] Example CSP configurations

### Milestone 10: Polish & Release Prep ✅ COMPLETE
**Goal:** Code quality, security review, and release preparation

- [x] Run phpcs on all files
- [x] Run phpcbf to auto-fix issues
- [x] Manual code review
  - [x] All inputs sanitized
  - [x] All outputs escaped
  - [x] Nonces verified (Settings API handles this)
  - [x] Capabilities checked
  - [x] No SQL injection vulnerabilities
  - [x] No XSS vulnerabilities
- [x] Security hardening
  - [x] Verify ABSPATH checks in all files
  - [x] Check file upload handling (none)
  - [x] Review AJAX endpoint security (none currently)
- [x] Performance optimization
  - [x] Minimize database queries (zero on frontend)
  - [x] Cache parsed exclusion patterns (handled per request)
  - [x] Optimize HTML processing (WP_HTML_Tag_Processor)
- [x] Translation preparation
  - [x] All text strings use __() or _e()
  - [x] Generate .pot file
  - [ ] Test with different locales (Phase 2)
- [x] Create `languages/` directory
- [ ] Version bump and changelog
- [ ] Final testing on clean WordPress install

---

## Milestone 11: Pre-Release Polish 🎯 IN PROGRESS
**Goal:** Final polish and version consistency for 1.0.0 release

### Version Consistency & Release Prep
- [x] Verify version numbers match across all files
  - [x] `easy-csp-headers.php` header (Version: 1.0.0)
  - [x] `easy-csp-headers.php` ECSP_VERSION constant (1.0.0)
  - [x] `readme.txt` Stable tag (1.0.0)
  - [x] `README.md` version badge (1.0.0)
  - [x] `CHANGELOG.md` latest entry (1.0.0 - 2026-01-23)
- [x] Update CHANGELOG.md with all recent changes
- [ ] Test GitHub Actions release workflow

### Admin UI Enhancements
- [x] Add visual status indicator on General tab
  - [x] Green checkmark icon when Enforce mode active
  - [x] Blue info icon when Report-Only mode active
  - [x] Red warning when disabled
- [x] Add "Test CSP Headers" button
  - [x] Makes wp_remote_get() request to homepage
  - [x] Displays detected CSP headers with formatting
  - [x] Shows helpful error messages if no headers found
- [ ] Add client-side field validation
  - [ ] Validate URL format for Report URI
  - [ ] Validate path patterns in exclusions
  - [ ] Show inline error messages before submit

### Documentation Improvements
- [x] Expand Help tab with common scenarios
  - [ ] WooCommerce compatibility notes (deferred to Phase 2)
  - [ ] Contact Form 7 considerations (deferred to Phase 2)
  - [ ] Page builder compatibility (Elementor, etc.) (deferred to Phase 2)
- [x] Add troubleshooting section
  - [x] "My site is broken" emergency steps
  - [x] How to quickly disable CSP
  - [x] Browser console interpretation guide
- [x] Add developer examples to Help tab
  - [x] Using `ecsp_should_skip_csp` filter
  - [x] Programmatically enabling/disabling CSP (via settings)
  - [x] Path exclusion examples with wildcards

### Performance Audit
- [x] Review settings retrieval pattern
  - [x] Cache settings in class properties if called multiple times
  - [x] Minimize get_option() calls on frontend (only called once per page load)
- [x] Profile HTML processing performance
  - [x] Test with large HTML documents (WP_HTML_Tag_Processor is efficient)
  - [x] Verify no N+1 issues in tag processing (single pass through HTML)
- [x] Check admin page performance
  - [x] Minimize asset size
  - [x] Ensure conditional loading works (only loads on plugin settings page)

### Code Quality Final Check
- [x] Run full phpcs check (all files pass)
- [x] Review all @since tags for accuracy (0.x versions retained for historical accuracy)
- [x] Verify all translatable strings use text domain (all use 'easy-csp-headers')
- [x] Check for any TODO/FIXME comments (none found)
- [x] Remove any debug/development code (none found)

### Template Refactoring
- [x] Refactor admin templates from HTML-first to code-first
  - [x] Extract tabs into separate files (general-tab.php, csp-rules-tab.php, exclusions-tab.php, help-tab.php)
  - [x] Refactor settings-page.php to lean structure (406 lines → 57 lines)
  - [x] Convert all tab files to printf/echo pattern
  - [x] Add helpful closing comments to all wrappers
  - [x] Verify all templates pass phpcs standards

---

- WP-CLI commands for testing CSP rules

**Known Limitations:**
- Inline event handlers (onclick, onload, etc.) will break with strict CSP
- Requires PHP 7.4+ for WP_HTML_Tag_Processor
- Won't work with already-sent headers (rare edge case)

---

## Notes for Development

**Critical Decisions:**
- ✅ No admin area CSP (too complex, low value)
- ✅ Use WP_HTML_Tag_Processor (native, fast, safe)
- ✅ Store nonce in HTML attributes (cache-friendly)
- ✅ Default to strict-dynamic for best security

**Testing Environments Needed:**
- WordPress 6.4+ (for WP_HTML_Tag_Processor)
- PHP 8.0+
- Common caching plugins
- Common page builders

**Code Standards:**
- Follow `.github/copilot-instructions.md`
- No declare(strict_types=1)
- SESE pattern for functions
- Magic values in constants.php
- Type hints on all methods

