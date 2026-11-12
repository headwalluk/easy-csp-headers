# Hooks and filters

The plugin's public extension surface. Anything not listed here (functions, classes, constants)
is internal and may change in any release.

## `ecsp_should_skip_csp`

Filter. Whether to leave the current front-end request alone: no nonces, no header.

```php
apply_filters( 'ecsp_should_skip_csp', bool $skip );
```

`$skip` already reflects the settings: true when the plugin is off, in the admin, for a logged-in
user (unless **Enable for Logged-in Users** is on), or on an excluded path. The result is read as
a boolean. It runs once the page's HTML is complete, so conditional tags such as `is_singular()`
work.

```php
/**
 * Leave single pages of a custom post type without a CSP.
 *
 * @param mixed $skip Whether to skip CSP processing.
 * @return bool Whether to skip CSP processing.
 */
function example_ecsp_skip_landing_pages( $skip ): bool {
	return (bool) $skip || is_singular( 'landing_page' );
}
add_filter( 'ecsp_should_skip_csp', 'example_ecsp_skip_landing_pages' );
```

Since 0.3.0.

## `ecsp_updater_enabled`

Filter. Whether the plugin checks GitHub for updates.

```php
apply_filters( 'ecsp_updater_enabled', bool $enabled );
```

Return `false` to stop the checks for this plugin only, for example on a staging site:

```php
add_filter( 'ecsp_updater_enabled', '__return_false' );
```

Since 1.3.0.

## `headwall_github_updater_enabled` (deprecated)

The filter used by 1.2.x and earlier. It still works, with a deprecation notice, and its result
is passed into `ecsp_updater_enabled`. Deprecated in 1.3.0; it will be removed in 2.0.0.

```php
apply_filters( 'headwall_github_updater_enabled', bool $enabled, string $plugin_slug, string $github_repo );
```
