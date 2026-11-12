# CLAUDE.md

Repository: public

Follows the **headwall-wp-plugin** skill. Load it before working on this plugin; this file holds
only what is specific to Easy CSP Headers.

## Project Overview

Adds a nonce-based Content Security Policy to front-end pages. It buffers the page from
`template_redirect`, adds a `nonce` attribute to every executable `<script>` (and optionally
every `<style>`) with `WP_HTML_Tag_Processor`, and sends a matching `Content-Security-Policy` or
`Content-Security-Policy-Report-Only` header.

- **Namespace:** `Easy_CSP_Headers` · **Text domain:** `easy-csp-headers` · **Prefixes:** `ecsp_` (options, hooks), `ECSP_` (constants)
- **PHP / WordPress floor:** 8.2 / 6.4. The 6.4 floor dates from 0.1.0; `WP_HTML_Tag_Processor` itself arrived in 6.2
- **No build system, no WP-CLI commands.** Plain CSS and JS in `assets/admin/`

## Request Flow

1. `template_redirect`: `Output_Buffer::start_buffer()` calls `ob_start()` with
   `process_buffer_callback()`
2. When the buffer flushes, `CSP_Processor::process_output()`: return the HTML untouched if it
   is empty, `WP_HTML_Tag_Processor` is missing, or `should_skip_csp()` says so (plugin off,
   admin, logged-in user without the setting, excluded path, then the `ecsp_should_skip_csp`
   filter)
3. Otherwise: one nonce per request (`get_nonce()`, memoised, because the callback runs once per
   `ob_flush()` chunk); `add_nonce_to_scripts()` for scripts whose `type` passes
   `should_process_script()` (missing, empty, or one of `EXECUTABLE_SCRIPT_TYPES` after
   lowercasing and trimming); optionally `add_nonce_to_styles()`; then `inject_csp_header()`,
   once, and only if headers haven't been sent
4. `build_csp_header_value()`: `script-src` (nonce, `'strict-dynamic'`, `'unsafe-inline'` and
   `http: https:` fallbacks unless omitted, whitelisted domains), `style-src` when styles are
   processed, `object-src 'none'`, `base-uri 'none'`, custom directives, `report-uri`

Admin: `Settings` registers the options and the tabbed page under **Settings → CSP Headers**
(`easy-csp-headers`); `Admin_Hooks` adds the menu, assets, notices, admin-bar status and the
`wp_ajax_ecsp_test_headers` handler, which fetches the home page from the server and reports the
CSP headers it got back.

## Key Files

| File | Purpose |
|------|---------|
| `easy-csp-headers.php` | Header, `ECSP_*` constants, requires, updater with the deprecated-filter bridge, run |
| `constants.php` | Option names and defaults, modes, executable script types, nonce length, plugin name, updater |
| `functions-private.php` | `is_report_only_mode()`, `should_skip_csp()`, `apply_deprecated_updater_filter()` |
| `includes/class-plugin.php` | Singleton, hooks, textdomain, lazy getters |
| `includes/class-output-buffer.php` | `ob_start()` on `template_redirect` |
| `includes/class-csp-processor.php` | Nonces, tag processing, the header |
| `includes/class-settings.php` | Option registration and sanitising, tab-preserving redirect |
| `includes/class-admin-hooks.php` | Menu, assets, notices, admin bar, header test |
| `includes/class-github-updater.php` | Copied from Quick 2FA; change only `namespace` and `@package` |
| `admin-templates/` | The settings page and its tabs |
| `uninstall.php` | Opt-in: deletes every `ecsp_` option and the updater cache |

## Data Storage

- Options, all `ecsp_` (`OPTION_*` in `constants.php`). `ecsp_use_unsafe_hashes` is retired:
  it may still be stored, so never reuse the name
- Updater transients `easy_csp_headers_github_release` / `_github_failed`
- Nothing per user or per post. Uninstall deletes nothing unless
  `ecsp_delete_data_on_uninstall` is on

## Public Contracts

The skill's `public-contracts.md` has the rules. This plugin's:

| Contract | Names |
|----------|-------|
| Filters | `ecsp_should_skip_csp`, `ecsp_updater_enabled`; `headwall_github_updater_enabled` deprecated in 1.3.0, bridged by `apply_deprecated_updater_filter()` |
| Options | the `OPTION_*` values in `constants.php` |
| Settings page | `options-general.php?page=easy-csp-headers` |
| AJAX action | `ecsp_test_headers` |
| Script and style handles | `ecsp-admin-scripts`, `ecsp-admin-styles` |
| Global constants | `ECSP_VERSION`, `ECSP_FILE`, `ECSP_DIR`, `ECSP_URL`, `ECSP_BASENAME` |

The filters are documented in `docs/developers/hooks-and-filters.md`.

### High-Impact Code

These paths decide what runs on every front-end page. A mistake in Enforce mode blocks a site's
scripts for every visitor:

- `CSP_Processor::process_output()`, `add_nonce_to_scripts()`, `should_process_script()` and
  `build_csp_header_value()`
- `should_skip_csp()`

## Testing

`dev-notes/testing/test-csp-processor.php` covers script types, tag processing, nonces, the
header value and the skip rules. It sets options through `pre_option_` filters, so nothing
stored changes:

```bash
wp eval-file wp-content/plugins/easy-csp-headers/dev-notes/testing/test-csp-processor.php
```

The plugin must be active for the harness. On the dev site it is stored enabled in Report-Only
mode, so activating it adds a report-only header and blocks nothing. Check the real header and
the nonces over HTTP with a cache-busting query string (`?ecsp_check=<random>`): the dev site's
page cache otherwise serves a copy built while the plugin was off.

Run it before a release and after any change to high-impact code.

## Release

Follow the skill's `release.md` (public). The updater's repository is `UPDATER_GITHUB_REPO` in
`constants.php`. The default branch is `main`.

## Reference Files

`docs/` (tracked, public):

- `docs/how-it-works.md`, `docs/troubleshooting.md`: site owners
- `docs/developers/hooks-and-filters.md`: developers

`dev-notes/` (private, untracked):

- `dev-notes/00-project-tracker.md`: milestones and open questions
- `dev-notes/archive/`: superseded material
- `dev-notes/testing/`: the harness

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
