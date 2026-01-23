<?php
/**
 * Settings page template.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped.
// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited -- $mode is locally scoped variable, not overriding WP global.

?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php settings_errors(); ?>

	<nav class="nav-tab-wrapper wp-clearfix">
		<?php
		printf(
			'<a href="#general" class="nav-tab nav-tab-active" data-tab="general">%s</a>',
			esc_html__( 'General', 'easy-csp-headers' )
		);
		printf(
			'<a href="#csp-rules" class="nav-tab" data-tab="csp-rules">%s</a>',
			esc_html__( 'CSP Rules', 'easy-csp-headers' )
		);
		printf(
			'<a href="#exclusions" class="nav-tab" data-tab="exclusions">%s</a>',
			esc_html__( 'Exclusions', 'easy-csp-headers' )
		);
		printf(
			'<a href="#help" class="nav-tab" data-tab="help">%s</a>',
			esc_html__( 'Help', 'easy-csp-headers' )
		);
		?>
	</nav>

	<div class="tab-content">
		<!-- General Tab -->
		<div id="general-panel" class="tab-panel active">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'ecsp_general' );
				?>
				<input type="hidden" name="ecsp_active_tab" class="ecsp-active-tab" value="general">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable CSP Headers', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$enabled = (bool) filter_var( get_option( OPT_ENABLED, DEF_ENABLED ), FILTER_VALIDATE_BOOLEAN );
							printf(
								'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
								esc_attr( OPT_ENABLED ),
								checked( $enabled, true, false ),
								esc_html__( 'Enable Content Security Policy processing', 'easy-csp-headers' )
							);
							?>
							<p class="description">
								<?php esc_html_e( 'Master switch to enable or disable CSP header injection.', 'easy-csp-headers' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'CSP Mode', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$mode = get_option( OPT_MODE, DEF_MODE );
							printf(
								'<label><input type="radio" name="%s" value="report-only" %s> %s</label><br>',
								esc_attr( OPT_MODE ),
								checked( $mode, 'report-only', false ),
								esc_html__( 'Report-Only (violations logged, not blocked)', 'easy-csp-headers' )
							);
							printf(
								'<label><input type="radio" name="%s" value="enforce" %s> %s</label>',
								esc_attr( OPT_MODE ),
								checked( $mode, 'enforce', false ),
								esc_html__( 'Enforce (violations blocked)', 'easy-csp-headers' )
							);
							?>
							<p class="description">
								<?php esc_html_e( 'Start with Report-Only mode to test without breaking your site.', 'easy-csp-headers' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable for Logged-in Users', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$enable_logged_in = (bool) filter_var(
								get_option( OPT_ENABLE_FOR_LOGGED_IN, DEF_ENABLE_FOR_LOGGED_IN ),
								FILTER_VALIDATE_BOOLEAN
							);
							printf(
								'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
								esc_attr( OPT_ENABLE_FOR_LOGGED_IN ),
								checked( $enable_logged_in, true, false ),
								esc_html__( 'Apply CSP headers when users are logged in', 'easy-csp-headers' )
							);
							?>
							<p class="description">
								<?php esc_html_e( 'By default, CSP is disabled for logged-in users. Enable this to test while authenticated.', 'easy-csp-headers' ); ?>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>

		<!-- CSP Rules Tab -->
		<div id="csp-rules-panel" class="tab-panel" style="display:none;">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'ecsp_rules' );
				?>
				<input type="hidden" name="ecsp_active_tab" class="ecsp-active-tab" value="csp-rules">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Use Strict-Dynamic', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$strict_dynamic = (bool) filter_var(
								get_option( OPT_USE_STRICT_DYNAMIC, DEF_USE_STRICT_DYNAMIC ),
								FILTER_VALIDATE_BOOLEAN
							);
							printf(
								'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
								esc_attr( OPT_USE_STRICT_DYNAMIC ),
								checked( $strict_dynamic, true, false ),
								esc_html__( "Use 'strict-dynamic' in script-src directive", 'easy-csp-headers' )
							);
							?>
							<p class="description">
								<?php esc_html_e( 'Recommended for best security. Allows scripts loaded by trusted scripts.', 'easy-csp-headers' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Use Unsafe-Hashes', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$unsafe_hashes = (bool) filter_var(
								get_option( OPT_USE_UNSAFE_HASHES, DEF_USE_UNSAFE_HASHES ),
								FILTER_VALIDATE_BOOLEAN
							);
							printf(
								'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
								esc_attr( OPT_USE_UNSAFE_HASHES ),
								checked( $unsafe_hashes, true, false ),
								esc_html__( "Allow inline event handlers with 'unsafe-hashes'", 'easy-csp-headers' )
							);
							?>
							<p class="description">
								<?php esc_html_e( 'Less secure. Allows onclick, onload, etc. Use only if needed for legacy code.', 'easy-csp-headers' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Process Styles', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$process_styles = (bool) filter_var(
								get_option( OPT_PROCESS_STYLES, DEF_PROCESS_STYLES ),
								FILTER_VALIDATE_BOOLEAN
							);
							printf(
								'<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
								esc_attr( OPT_PROCESS_STYLES ),
								checked( $process_styles, true, false ),
								esc_html__( 'Add nonces to <style> tags and include style-src directive', 'easy-csp-headers' )
							);
							?>
							<p class="description">
								<?php esc_html_e( 'Enable if you want to restrict inline styles as well as scripts.', 'easy-csp-headers' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Whitelisted Domains', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$whitelisted = get_option( OPT_WHITELISTED_DOMAINS, DEF_WHITELISTED_DOMAINS );
							printf(
								'<textarea name="%s" rows="5" cols="50" class="large-text code">%s</textarea>',
								esc_attr( OPT_WHITELISTED_DOMAINS ),
								esc_textarea( $whitelisted )
							);
							?>
							<p class="description">
								<?php esc_html_e( 'Add trusted domains (one per line). Example: https://cdn.example.com', 'easy-csp-headers' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Custom CSP Directives', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$custom = get_option( OPT_CUSTOM_DIRECTIVES, DEF_CUSTOM_DIRECTIVES );
							printf(
								'<textarea name="%s" rows="5" cols="50" class="large-text code">%s</textarea>',
								esc_attr( OPT_CUSTOM_DIRECTIVES ),
								esc_textarea( $custom )
							);
							?>
							<p class="description">
								<?php
								esc_html_e( 'Add custom CSP directives (one per line). Example: img-src * data:', 'easy-csp-headers' );
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Report URI', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$report_uri = get_option( OPT_REPORT_URI, DEF_REPORT_URI );
							printf(
								'<input type="url" name="%s" value="%s" class="regular-text code">',
								esc_attr( OPT_REPORT_URI ),
								esc_attr( $report_uri )
							);
							?>
							<p class="description">
								<?php esc_html_e( 'URL where CSP violation reports should be sent.', 'easy-csp-headers' ); ?>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>

		<!-- Exclusions Tab -->
		<div id="exclusions-panel" class="tab-panel" style="display:none;">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'ecsp_exclusions' );
				?>
				<input type="hidden" name="ecsp_active_tab" class="ecsp-active-tab" value="exclusions">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Excluded Paths', 'easy-csp-headers' ); ?></th>
						<td>
							<?php
							$excluded = get_option( OPT_EXCLUDED_PATHS, DEF_EXCLUDED_PATHS );
							printf(
								'<textarea name="%s" rows="8" cols="50" class="large-text code">%s</textarea>',
								esc_attr( OPT_EXCLUDED_PATHS ),
								esc_textarea( $excluded )
							);
							?>
							<p class="description">
								<?php
								esc_html_e( 'Paths to exclude from CSP processing (one per line). Examples:', 'easy-csp-headers' );
								echo '<br>';
								echo esc_html( '/checkout/' ) . '<br>';
								echo esc_html( '/wp-admin/*' ) . '<br>';
								echo esc_html( '/my-account/*' );
								?>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>

		<!-- Help Tab -->
		<div id="help-panel" class="tab-panel" style="display:none;">
			<h2><?php esc_html_e( 'What is Content Security Policy (CSP)?', 'easy-csp-headers' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'Content Security Policy is a security feature that helps prevent cross-site scripting (XSS) attacks by controlling which resources can be loaded and executed on your website.',
					'easy-csp-headers'
				);
				?>
			</p>

			<h2><?php esc_html_e( 'How Nonces Work', 'easy-csp-headers' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'This plugin generates a unique nonce (random value) for each page load and adds it to all script and style tags. The same nonce is included in the CSP header, allowing only scripts with matching nonces to execute.',
					'easy-csp-headers'
				);
				?>
			</p>

			<h2><?php esc_html_e( 'Getting Started', 'easy-csp-headers' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Start with "Report-Only" mode to test without breaking your site', 'easy-csp-headers' ); ?></li>
				<li><?php esc_html_e( 'Check your browser console for CSP violation reports', 'easy-csp-headers' ); ?></li>
				<li><?php esc_html_e( 'Add trusted domains to the whitelist if needed', 'easy-csp-headers' ); ?></li>
				<li><?php esc_html_e( 'Once satisfied, switch to "Enforce" mode', 'easy-csp-headers' ); ?></li>
			</ol>

			<h2><?php esc_html_e( 'Common Issues', 'easy-csp-headers' ); ?></h2>
			<h3><?php esc_html_e( 'Inline Event Handlers', 'easy-csp-headers' ); ?></h3>
			<p>
				<?php
				esc_html_e(
					'Inline event handlers like onclick="..." will be blocked by strict CSP. These should be updated to use addEventListener() instead.',
					'easy-csp-headers'
				);
				?>
			</p>

			<h3><?php esc_html_e( 'External Scripts', 'easy-csp-headers' ); ?></h3>
			<p>
				<?php
				esc_html_e(
					'With strict-dynamic enabled, external scripts loaded by trusted scripts will automatically be allowed. You can also add specific domains to the whitelist.',
					'easy-csp-headers'
				);
				?>
			</p>

			<h3><?php esc_html_e( 'Caching', 'easy-csp-headers' ); ?></h3>
			<p>
				<?php
				esc_html_e(
					'This plugin works with page caching. The nonce is generated when the cache is built and remains consistent until the cache is cleared.',
					'easy-csp-headers'
				);
				?>
			</p>
		</div>
	</div>
</div>
