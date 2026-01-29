/**
 * Admin scripts for Easy CSP Headers.
 *
 * Hash-based tabbed navigation implementation.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

document.addEventListener('DOMContentLoaded', () => {
	const tabs = document.querySelectorAll('.nav-tab');
	const panels = document.querySelectorAll('.tab-panel');

	if (!tabs.length || !panels.length) {
		return;
	}

	// Activate tab from URL hash on page load
	const activeTab = window.location.hash.substring(1) || 'general';
	activateTab(activeTab);

	// Tab click handlers
	tabs.forEach(tab => {
		tab.addEventListener('click', (e) => {
			e.preventDefault();
			const tabName = tab.dataset.tab;
			window.location.hash = tabName;
			activateTab(tabName);
		});
	});

	// Handle browser back/forward
	window.addEventListener('hashchange', () => {
		const tabName = window.location.hash.substring(1) || 'general';
		activateTab(tabName);
	});

	/**
	 * Activate a specific tab.
	 *
	 * @param {string} tabName Tab identifier.
	 */
	function activateTab(tabName) {
		// Update nav tabs
		tabs.forEach(t => t.classList.remove('nav-tab-active'));
		const activeTabEl = document.querySelector(`[data-tab="${tabName}"]`);
		if (activeTabEl) {
			activeTabEl.classList.add('nav-tab-active');
		}

		// Show/hide panels
		panels.forEach(panel => {
			panel.style.display = 'none';
			panel.classList.remove('active');
		});

		const activePanel = document.getElementById(`${tabName}-panel`);
		if (activePanel) {
			activePanel.style.display = 'block';
			activePanel.classList.add('active');
		}

		// Update hidden field for tab preservation on save
		const hiddenField = document.querySelector('.ecsp-active-tab');
		if (hiddenField) {
			hiddenField.value = tabName;
		}
	}

	// Test CSP Headers button handler
	const testButton = document.getElementById('ecsp-test-headers');
	if (testButton) {
		testButton.addEventListener('click', function() {
			const spinner = this.parentElement.querySelector('.spinner');
			const resultsDiv = document.getElementById('ecsp-test-results');
			
			// Show spinner
			spinner.classList.add('is-active');
			testButton.disabled = true;
			resultsDiv.style.display = 'none';

			// Make AJAX request
			fetch(ecspAdmin.ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'ecsp_test_headers',
					nonce: ecspAdmin.nonce
				})
			})
			.then(response => response.json())
			.then(data => {
				spinner.classList.remove('is-active');
				testButton.disabled = false;
				
				if (data.success) {
					displayTestResults(data.data);
				} else {
					displayError(data.data.message || 'Test failed');
				}
			})
			.catch(error => {
				spinner.classList.remove('is-active');
				testButton.disabled = false;
				displayError('Request failed: ' + error.message);
			});
		});
	}

	/**
	 * Display test results.
	 *
	 * @param {Object} data Test results data.
	 */
	function displayTestResults(data) {
		const resultsDiv = document.getElementById('ecsp-test-results');
		let html = '';

		if (data.csp_found) {
			html += '<div class="notice notice-success inline"><p><strong>✓ CSP Headers Detected</strong></p>';
			html += '<p><strong>URL Tested:</strong> ' + escapeHtml(data.url) + '</p>';
			html += '<p><strong>HTTP Status:</strong> ' + escapeHtml(data.status) + '</p>';
			
			if (data.csp_header) {
				html += '<p><strong>Content-Security-Policy:</strong></p>';
				html += '<pre style="background:#f5f5f5;padding:10px;border:1px solid #ddd;overflow-x:auto;white-space:pre-wrap;word-wrap:break-word;">' + escapeHtml(data.csp_header) + '</pre>';
			}
			
			if (data.csp_ro_header) {
				html += '<p><strong>Content-Security-Policy-Report-Only:</strong></p>';
				html += '<pre style="background:#f5f5f5;padding:10px;border:1px solid #ddd;overflow-x:auto;white-space:pre-wrap;word-wrap:break-word;">' + escapeHtml(data.csp_ro_header) + '</pre>';
			}
			
			html += '</div>';
		} else {
			html += '<div class="notice notice-error inline"><p><strong>✗ No CSP Headers Found</strong></p>';
			html += '<p><strong>URL Tested:</strong> ' + escapeHtml(data.url) + '</p>';
			html += '<p><strong>HTTP Status:</strong> ' + escapeHtml(data.status) + '</p>';
			html += '<p>Make sure CSP is enabled and cache is cleared.</p></div>';
		}

		resultsDiv.innerHTML = html;
		resultsDiv.style.display = 'block';
	}

	/**
	 * Display error message.
	 *
	 * @param {string} message Error message.
	 */
	function displayError(message) {
		const resultsDiv = document.getElementById('ecsp-test-results');
		resultsDiv.innerHTML = '<div class="notice notice-error inline"><p><strong>Error:</strong> ' + escapeHtml(message) + '</p></div>';
		resultsDiv.style.display = 'block';
	}

	/**
	 * Escape HTML to prevent XSS.
	 *
	 * @param {string} text Text to escape.
	 * @return {string} Escaped text.
	 */
	function escapeHtml(text) {
		const div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}
});
