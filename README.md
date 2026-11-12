# Easy CSP Headers

A WordPress plugin that adds a nonce-based Content Security Policy to your site's front end,
without hand-writing a policy or editing your theme.

A Content Security Policy tells the browser which scripts it may run. With one in place, a
script injected into a page by an attacker is refused, which stops most cross-site scripting.
Writing one by hand for a WordPress site is hard, because themes and plugins add inline scripts
everywhere. Easy CSP Headers gives every legitimate script on the page a fresh random nonce on
each request, and sends a header that allows only scripts carrying it.

## What it does

- **Nonces on every executable script**, using WordPress's own HTML parser. Data blocks such as
  JSON-LD are left alone
- **Report-Only mode** to see what a policy would block before enforcing it. New installs start
  here
- **Strict-dynamic** on by default, so scripts your page trusts can load others (analytics, chat
  widgets) without listing each one
- **Optional style nonces**, whitelisted domains, custom directives and a report URI
- **Path exclusions** with wildcards, and a filter for anything more specific
- **A header test** on the settings page

The admin area is never touched, and logged-in users are left alone unless you turn that on.

## Requirements

- WordPress 6.4 or later
- PHP 8.2 or later

## Installation

1. Download `easy-csp-headers.zip` from the
   [latest release](https://github.com/headwalluk/easy-csp-headers/releases/latest)
2. In WordPress, go to **Plugins → Add New Plugin → Upload Plugin** and upload the zip
3. Activate it, then go to **Settings → CSP Headers**, turn it on in Report-Only mode, and clear
   your page cache

Updates arrive through the normal WordPress updates screen.

## Documentation

- [How it works](docs/how-it-works.md): what happens to each page, every setting, and how to
  roll it out safely
- [Troubleshooting](docs/troubleshooting.md): blocked scripts, inline event handlers, caching
- [Hooks and filters](docs/developers/hooks-and-filters.md), for developers

## Licence

GPL v2 or later. See [LICENSE](LICENSE).
