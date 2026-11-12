# How it works

For site owners and the people who look after their sites.

## What happens to each page

A Content Security Policy (CSP) is a header that tells the browser which scripts it may run. A
script that isn't allowed is blocked (in Enforce mode) or reported (in Report-Only mode). It's
one of the strongest defences against cross-site scripting: an attacker who manages to inject a
`<script>` into a page can't make the browser run it.

Easy CSP Headers uses a **nonce**, a random code that changes on every page load:

1. When WordPress starts building a front-end page, the plugin captures the page's HTML
2. It generates a new nonce for this request
3. It adds `nonce="…"` to every script that runs: scripts with no `type`, `type="module"`, and
   the JavaScript types (`text/javascript` and its older spellings, in any capitalisation).
   Data blocks such as JSON-LD (`application/ld+json`), import maps and templates are left alone
4. It sends a `Content-Security-Policy` (or `Content-Security-Policy-Report-Only`) header that
   allows scripts carrying that nonce, and nothing else
5. The browser runs the page's own scripts, because they carry the nonce, and refuses any that
   don't

It works with WordPress's own HTML parser (`WP_HTML_Tag_Processor`), so text that only looks
like a tag, such as `<script>` inside an attribute or a `<textarea>`, is never touched.

The admin area, and pages for logged-in users unless you turn that on, are left alone.

## The settings

Go to **Settings → CSP Headers**.

### General

- **Enable CSP Headers**: the master switch. Off by default
- **CSP Mode**: **Report-Only** (violations are logged in the browser console, nothing is
  blocked) or **Enforce** (violations are blocked). New installs start in Report-Only
- **Enable for Logged-in Users**: off by default, so editors and admins aren't affected while
  you test. Turn it on to test the policy while logged in
- **Delete all plugin data when uninstalled**: off by default. Deactivating never deletes
  anything
- **Test CSP Headers**: requests your home page from the server and shows which CSP header
  came back

### CSP Rules

- **Use Strict-Dynamic**: on by default. Scripts the page trusts can load further scripts
  (analytics, chat widgets) without each one being listed
- **Omit Unsafe-Inline Fallback** and **Omit http:/https: Fallback**: by default the policy also
  contains `'unsafe-inline'` and, with strict-dynamic, `http:` and `https:`. Current browsers
  ignore all three when a nonce is present; they only keep scripts working on old browsers.
  Tick these for a shorter, strict policy everywhere, accepting broken scripts on those old
  browsers (such as Safari before 15.4)
- **Add Nonces to Styles**: adds the nonce to `<style>` tags and a `style-src` directive. Off by
  default; turn it on only if you also want to restrict inline styles
- **Whitelisted Domains**: extra script (and style) sources, one per line, for example
  `https://cdn.example.com`
- **Custom CSP Directives**: any other directives, one per line, for example `img-src * data:`
  or `frame-ancestors 'self'`
- **Report URI**: where browsers should send violation reports

The policy always includes `object-src 'none'` and `base-uri 'none'`.

### Exclusions

**Excluded Paths**: one path per line. `/my-account/` matches that page exactly; `/checkout/*`
matches everything under it. Matching ignores the query string.

## Rolling it out

1. Turn the plugin on in **Report-Only** mode
2. Clear your page cache
3. Browse the site with the browser console open, and note any CSP violations
4. Fix or allow what you find: see [troubleshooting](troubleshooting.md)
5. When the console is clean, switch to **Enforce**, and clear the cache again

## Requirements

- WordPress 6.4 or later
- PHP 8.2 or later

## Privacy

The plugin stores its settings in the WordPress options table and nothing else. It sends no data
anywhere, except that:

- **Test CSP Headers** makes one request from your server to your own home page
- To check for updates, it asks `api.github.com` for the latest release, at most every 12
  hours. Like every request WordPress makes, that request carries your site's address in its
  user agent

## Uninstalling

Deleting the plugin from the **Plugins** screen removes nothing, unless **Delete all plugin data
when uninstalled** is on. Then it removes every option whose name starts with `ecsp_`, and the
update-check cache.
