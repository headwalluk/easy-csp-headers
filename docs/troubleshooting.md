# Troubleshooting

## A script is blocked or reported

Open the browser console. Each violation names the blocked source.

- **An external script (`https://…`) is blocked**: with **Use Strict-Dynamic** on, scripts
  loaded by the page's own scripts are allowed automatically. A script tag written directly
  into the page carries the nonce. If something is still blocked, add its domain under
  **Whitelisted Domains**
- **An inline script is blocked**: the plugin adds the nonce to every inline `<script>` in the
  page HTML. A script added later by JavaScript without a nonce, or HTML that bypasses WordPress
  (for example, printed after the page has finished), isn't covered. With strict-dynamic, a
  script created by a trusted script with `document.createElement( 'script' )` is allowed
- **A page builder or plugin breaks on one area of the site**: add that path under
  **Excluded Paths** while you look into it

## Inline event handlers

Attributes such as `onclick="…"` and `onload="…"` aren't scripts the plugin can give a nonce
to, so a strict CSP blocks them. Move them into a script:

```html
<!-- Blocked -->
<button onclick="handleClick()">Click me</button>

<!-- Works -->
<button class="my-button">Click me</button>
<script>
  document.querySelector( '.my-button' ).addEventListener( 'click', handleClick );
</script>
```

Report-Only mode lists every one of these before you enforce anything.

## Caching

The nonce is generated when a page is built. With a page cache, it's generated when the cache
entry is built, and every visitor gets the same cached nonce until the cache is cleared.

- **Clear your page cache after changing any setting**, or cached pages keep the old policy, or
  no policy at all
- Whether the header is cached with the page depends on the cache. If yours stores response
  headers, the cached header and the cached nonces match. If it stores only the HTML, cached
  pages are served with no CSP header: nothing breaks, but those pages aren't protected. Use
  **Test CSP Headers**, or `curl -I` on a page, to see what visitors get

## Checking the header

**Settings → CSP Headers → General → Test CSP Headers** requests your home page from the server
and shows the header that came back.

From a terminal:

```bash
curl -sI https://example.com/ | grep -i content-security
```

No header usually means one of these:

- **Enable CSP Headers** is off
- You're logged in and **Enable for Logged-in Users** is off (`curl` is never logged in)
- The path is excluded
- A page cache served a copy built before the plugin was enabled. Clear the cache, or add a
  query string such as `?nocache=1` to bypass it

## Updates

The plugin checks GitHub for new releases at most every 12 hours, and offers them on the normal
**Dashboard → Updates** screen. A developer can switch the check off; see
[hooks and filters](developers/hooks-and-filters.md).
