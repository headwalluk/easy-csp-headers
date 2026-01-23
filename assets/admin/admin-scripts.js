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
	}
});
