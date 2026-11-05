# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Easy CSP Headers is a WordPress plugin that adds a nonce-based Content Security Policy to
front-end pages. It buffers the page output from `template_redirect`, adds a `nonce` attribute to
every executable `<script>` (and optionally every `<style>`) with `WP_HTML_Tag_Processor`, and sends
a matching `Content-Security-Policy` or `Content-Security-Policy-Report-Only` header.

- **Namespace:** `Easy_CSP_Headers` for all classes
- **Text Domain:** `easy-csp-headers`
- **PHP:** 8.0+ (do NOT use `declare(strict_types=1)` — breaks WordPress interop)
- **WordPress:** 6.4+
- **No build system** — no npm, no Composer, no bundler. Assets are plain CSS/JS.

The structure and conventions follow the maintainer's reference plugin, Quick 2FA
(`../quick-2fa/`). Where this file is silent, its `CLAUDE.md` is the standard.

This plugin is published publicly on GitHub. Tracked files must contain no client names,
client URLs, fleet measurements or client data of any kind — in code, comments, docs,
fixtures or commit messages.

`dev-notes/` is **private and untracked** (`.gitignore`), backed up with the dev site, and
blocked from the web by `dev-notes/.htaccess`. It is the right home for client-identifying
material, though anonymised references ("a client site") are still preferred there.
Never copy content from `dev-notes/` into a tracked file without scrubbing it, and
never reference a `dev-notes/` path from a file that ships in the release zip.

## Commands

```bash
phpcs                                  # Check WordPress Coding Standards (configured in phpcs.xml)
phpcbf                                 # Auto-fix coding standards violations
phpcs includes/class-csp-processor.php # Check a specific file
```

```bash
wp-translate . --check-instructions   # Is the block at the end of this file still current?
wp-translate . --sync-instructions    # Update it; review the diff afterwards
wp-translate . --dry-run              # Preview; no DeepL calls, no writes
```

Never hand-edit inside the `wp-translate:begin`/`end` markers — the block is hash-validated.

## Testing

There is no unit-test framework and none is wanted. Behaviour is exercised against the dev site
through WP-CLI. If the plugin is inactive there, load it inside the eval rather than activating it:

```bash
wp eval '
require WP_PLUGIN_DIR . "/easy-csp-headers/easy-csp-headers.php";
$processor = Easy_CSP_Headers\Plugin::instance()->get_processor();
echo $processor->build_csp_header_value( $processor->get_nonce() );
'
```

Reset any option you touch afterwards, and test the defensive path as well as the happy one.

## Architecture

### Entry Point & Initialization

`easy-csp-headers.php` → `easy_csp_headers_run()` → `Plugin::instance()->run()` (singleton).
Classes are loaded manually (no autoloader). `Plugin` lazy-loads its collaborators through
`get_processor()`, `get_output_buffer()`, `get_settings()` and `get_admin_hooks()`.

The GitHub updater (`includes/class-github-updater.php`) loads only for admin,
cron and WP-CLI requests.

### Request Flow

1. `template_redirect` → `Output_Buffer::start_buffer()` calls `ob_start()`
2. On flush → `CSP_Processor::process_output()`
3. `should_skip_csp()` (`functions-private.php`) bails for: plugin disabled, admin, logged-in
   users (unless enabled), excluded paths, and the `ecsp_should_skip_csp` filter
4. `get_nonce()` returns one nonce per request (20 random bytes, base64). The buffer callback can
   run once per chunk if anything calls `ob_flush()`, so the nonce is memoised and the header is
   sent only once
5. Nonces are added to scripts whose type is empty or in `EXECUTABLE_SCRIPT_TYPES`, and to
   `<style>` tags when *Process styles* is on
6. `inject_csp_header()` builds the policy from settings and sends it, unless headers are
   already sent

With page caching, the cached HTML carries a fixed nonce until the cache is cleared; the cache
must also store and replay the header.

### Key Files

| File | Purpose |
|------|---------|
| `easy-csp-headers.php` | Main plugin file: constants, class loading, updater, bootstrap |
| `constants.php` | Option names, defaults, CSP configuration |
| `functions-private.php` | Internal helpers: `should_skip_csp()`, `is_report_only_mode()` |
| `uninstall.php` | Deletes `ecsp_*` options, only when the user has opted in |
| `includes/class-plugin.php` | Singleton orchestrator: hook registration, lazy getters |
| `includes/class-output-buffer.php` | Starts the output buffer and hands it to the processor |
| `includes/class-csp-processor.php` | Nonce generation, tag rewriting, header building |
| `includes/class-settings.php` | Settings API registration and sanitisation |
| `includes/class-admin-hooks.php` | Settings page, admin notices, admin bar, header test AJAX |
| `includes/class-github-updater.php` | Updater ported from Quick 2FA, which owns it. Change only `namespace` and `@package`; fix it there and copy it here (see Quick 2FA's *Porting the GitHub updater*) |
| `admin-templates/` | Settings page and its tabs (code-first templates) |

### Data Storage

- **`wp_options` only.** No user meta, transients or custom tables (the updater keeps its own
  `easy_csp_headers_github_release` and `_github_failed` transients, removed by `uninstall.php`)
- **Every option carries `OPTION_PREFIX` (`ecsp_`).** `uninstall.php` finds options by prefix,
  so an option without it survives an uninstall
- **Uninstall deletes nothing by default.** Only with *Delete all plugin data when uninstalled*
  on (`OPTION_DELETE_DATA_ON_UNINSTALL`). Deactivation never deletes data

## Public Contracts

Treat everything in this table as a contract, whether or not it is documented as public:

| Contract | Examples | Breaks when |
|----------|----------|-------------|
| Filters | `ecsp_should_skip_csp`, `ecsp_updater_enabled`, `headwall_github_updater_enabled` (deprecated, bridged by `apply_deprecated_updater_filter()`) | renamed or removed, or an argument is removed or reordered |
| Option names | `ecsp_enabled`, `ecsp_mode`, `ecsp_whitelisted_domains` | a constant's **value** changes. Settings stored under the old name are ignored |
| Global constants | `ECSP_VERSION`, `ECSP_DIR`, `ECSP_URL`, `ECSP_FILE` | renamed or removed |
| Script and style handles | `ecsp-admin-scripts`, `ecsp-admin-styles` | renamed |
| Admin identifiers | settings page `easy-csp-headers`, settings group `ecsp_settings`, AJAX action `ecsp_test_headers` | renamed. Bookmarks, links and scripts break |
| The policy itself | the directives `build_csp_header_value()` emits for given settings | a directive is added, removed or tightened by default. Sites in Enforce mode can lose working scripts |

- **Add, don't change.** New filter arguments go at the end. New behaviour gets a new filter or
  setting, not a new meaning for an existing one
- **Deprecate, don't rename.** Fire the old name through `apply_filters_deprecated()`
- **Changing what the policy allows by default is a breaking change.** Gate it behind a setting
  defaulting to the old behaviour, and record it in `CHANGELOG.md` and `readme.txt`
- If you can't tell whether anything outside the plugin depends on it, stop and ask

### High-Impact Code

These paths decide what a browser is allowed to run on every front-end page:

- `CSP_Processor::build_csp_header_value()`, `get_script_src_directive()`, `get_style_src_directive()`
- `CSP_Processor::process_output()`, `get_nonce()`, `should_process_script()`, `inject_csp_header()`
- `should_skip_csp()`
- `Output_Buffer`

A bug here either blocks scripts site-wide (Enforce mode) or silently weakens the policy. When a
change touches one, say so, test it in both Report-Only and Enforce mode, and recommend the
maintainer read that diff line by line before tagging. A review by an AI agent does not count as
that read.

## Code Conventions

### PHP Style

- **No `declare(strict_types=1)`**
- **Single-Entry Single-Exit (SESE):** one return at the end. Top-of-function guard clauses are
  fine; `return` mid-function, inside loops or deeply nested is not
- **An `if` with `elseif` branches ends in a plain `else`.** A branch that does nothing is still
  written out, with a short comment. `phpcs.xml` allows comment-only `if`/`elseif`/`else` bodies
- **No assignment inside a condition**
- **Constants for all magic strings/numbers** in `constants.php`. A key constant's **value** is
  stored data and never changes
- **Type hints and return types** on all functions and class properties; check syntax against
  the PHP 8.0 floor
- **Callbacks on hooks the plugin doesn't own take `mixed`** and check each value before use;
  a typed parameter turns a sloppy third-party caller into a `TypeError`. Some older callbacks
  (`Settings::preserve_tab_on_redirect()`, `Admin_Hooks::add_plugin_action_links()`) still take
  typed parameters
- **Cast at the boundary.** `get_option()` returns loose types; cast where it is read
- **Boolean options** are read with `filter_var( ..., FILTER_VALIDATE_BOOLEAN )`

### Template Pattern (Code-First)

Templates use `printf()`/`echo` exclusively — no inline HTML mixed with PHP snippets.

```php
printf(
	'<button>%s</button>',
	esc_html__( 'Click', 'easy-csp-headers' )
);
```

### CSS and JavaScript

- **Logical properties** (`margin-inline-start`, `text-align: start`), never physical left/right
- **Plain JavaScript**, no jQuery, and no inline JavaScript
- This is a CSP plugin: its own admin pages should work under a strict policy. Prefer classes in
  `assets/admin/admin-styles.css` over inline `style` attributes

### Logging

No logging dependency. Genuine failures log unconditionally with `error_log()`; routine tracing
logs only under `WP_DEBUG`. Never hide an error behind a debug flag, and never leave a `catch`
that records nothing.

### Comments

- One-line docblock summary per function, saying what it does
- Inline comments only where the mechanism isn't obvious
- Reasoning and history go in docs, not comments

### Commit Messages

Format: `type: brief description` where type is one of: `feat:`, `fix:`, `chore:`, `refactor:`,
`docs:`, `style:`, `test:`

### Pre-Commit Workflow

1. `phpcs` — check violations
2. `phpcbf` — auto-fix
3. `phpcs` — verify clean: no errors **and no warnings**
4. Stage and commit

Every `phpcs:ignore` and `phpcs:disable` names the exact sniff and ends with `-- reason`.

## Release Workflow

1. Update the version in `easy-csp-headers.php` — **both** the `Version:` header and `ECSP_VERSION`
2. Update `CHANGELOG.md`: move `[Unreleased]` entries under the new version. If the version
   differs from new `@since` tags, update those too
3. Update `readme.txt`: stable tag, changelog and upgrade notice. Update the version in `README.md`
4. Regenerate the template: `wp i18n make-pot . languages/easy-csp-headers.pot --exclude=dev-notes,build,assets`.
   There are no locales yet; `wp-translate .` with no locale argument creates eight
5. Run `phpcs`
6. If the release touches **High-Impact Code**, the maintainer reads that diff line by line
7. Push a `vX.Y.Z` tag. `.github/workflows/release.yml` builds the zips (excluding everything in
   `.distignore`) and creates the GitHub Release

The workflow refuses to build unless the header `Version:`, `ECSP_VERSION` and the `readme.txt`
stable tag all match the tag. The updater compares the installed header version with the latest
release tag, so a mismatch shows a permanent false "update available" notice.

## Reference Files

- `README.md` — developers (GitHub)
- `readme.txt` — site operators (WordPress admin)
- `CHANGELOG.md` — per-version release notes
- `SECURITY.md` — vulnerability reporting

Supporting material (private, untracked):

- `dev-notes/00-project-tracker.md` — milestones and deferred features
- `dev-notes/archive/` — superseded material, including the retired `copilot-instructions.md`
  and the old `patterns/` and `workflows/` guides. Archived, not authoritative: **this file is
  the standard**

<!-- wp-translate:begin v=1.2.0 hash=62baf7eafd05c2bfcd8537836977fb5bd8903e10d81e0c96b13c512c939a34e4 -->
## Translating this plugin (wp-translate conventions)

This plugin's `.po`/`.mo` files are generated from source by
[wp-translate](https://github.com/headwalluk/wp-translate-tool), which
machine-translates strings with DeepL. Machine translation is only as good as
the strings you give it — follow these conventions when adding or editing
user-facing text.

### 1. Disambiguate short or ambiguous strings with `_x()`

DeepL handles full sentences well but guesses badly on short, context-free
labels. Give it context with `_x()` (or `esc_html_x()`, `_ex()`):

```php
// Ambiguous out of context — DeepL may read "Sent" as "late", "Folder" as "leaflet"
__( 'Sent', 'easy-csp-headers' );

// Disambiguated — the context is passed to the translator and to DeepL
_x( 'Sent', 'email delivery status', 'easy-csp-headers' );
_x( 'Folder', 'IMAP mailbox', 'easy-csp-headers' );
_x( 'Open', 'verb; button label', 'easy-csp-headers' );
```

The context (2nd argument) is never shown to users. Use it whenever a string is a
single word, a short label, or has more than one plausible meaning.

### 2. Use placeholders, never concatenation

Build dynamic text with `printf`/`sprintf` so the whole sentence translates as a
unit, and add a `translators:` comment to explain each placeholder:

```php
/* translators: %s is the user's display name */
printf( esc_html__( 'Welcome back, %s', 'easy-csp-headers' ), $name );
```

Never split a sentence across multiple translation calls — word order differs
between languages.

### 3. Use `_n()` for anything that can be counted

Never build a count-dependent sentence by hand, and never settle for a single
form that reads correctly only for one number. Languages differ in how many
plural forms they have — English and German have two, French treats 0 as
singular, Polish and Russian have three, Japanese has one, Arabic has six — and
`_n()` is the only way to express that.

```php
// Wrong — "1 reviews", and untranslatable into languages with other forms
printf( esc_html__( '%d reviews', 'easy-csp-headers' ), $count );

// Right — wp-translate fills every form the target locale needs
printf(
    esc_html( _n( '%d review', '%d reviews', $count, 'easy-csp-headers' ) ),
    $count
);
```

Keep the placeholder in **both** forms, even when the singular reads fine
without it (`'%d review'`, not `'One review'`) — some locales use the singular
slot for other numbers too.

For a short or ambiguous countable noun, use `_nx()` — the plural equivalent of
`_x()` — so the context reaches DeepL:

```php
// "Review" alone is ambiguous: critique? opinion? inspection?
_nx( '%d review', '%d reviews', $count, 'customer feedback on a company', 'easy-csp-headers' );
```

**Locales needing more than two forms will have their extra slots left empty for
a human translator.** DeepL supplies a singular and a plural; nobody can invent
Polish's third form from those, and wp-translate deliberately leaves it blank
rather than filling it with a plausible guess. Expect to see empty
`msgstr[2]` entries in `pl_PL` — that is correct behaviour, not a failure.

### 4. Acronyms and technical tokens

wp-translate keeps common acronyms (`TLS`, `API`, `SMTP`, `URL`, `ID`, `UTC`, …)
verbatim automatically. If you introduce an unusual acronym or product name that
must not be translated, keep it as its own standalone string so it is recognised,
or ask the maintainer to add it to the tool's acronym list.

### 5. Don't translate dates — let WordPress localise them

Never add month or day-of-week names (full or abbreviated) as translatable
strings. DeepL frequently mistranslates short forms like `Mon`, `Tue`, `Jan`,
`Feb` even with context hints. WordPress already ships locale-aware names — use
`$wp_locale`:

```php
global $wp_locale;
$wp_locale->get_month( $month_number );        // "January" (1-based)
$wp_locale->get_month_abbrev( $month_name );   // "Jan"
$wp_locale->get_weekday( $weekday_number );     // "Monday" (0 = Sunday)
$wp_locale->get_weekday_abbrev( $weekday_name ); // "Mon"
```

For formatted dates, prefer `wp_date()` / `date_i18n()`, which localise month and
day names automatically.

### 6. English source dialect

Write source strings in standard English. wp-translate handles English targets
locally (no DeepL): `en`/`en_US` use the source as-is, and `en_GB`/`en_AU`/… get
American spellings converted to British automatically (`color` → `colour`).

### Running wp-translate

After changing strings, regenerate translations:

```bash
wp-translate /path/to/this-plugin              # auto-detect locales from languages/
wp-translate /path/to/this-plugin en_GB,fr_FR  # explicit locales
wp-translate /path/to/this-plugin --dry-run    # preview; no API calls, no writes
```

Requires WP-CLI (`wp`) and a DeepL API key at `~/.config/deepl.env`. The tool
regenerates the `.pot` from source, translates new/changed strings for each
locale, and compiles the `.mo` files.
<!-- wp-translate:end -->
