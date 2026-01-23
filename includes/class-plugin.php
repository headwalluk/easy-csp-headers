<?php
/**
 * Main plugin class.
 *
 * Coordinates all plugin functionality and manages dependencies.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

/**
 * Main plugin orchestrator class.
 *
 * @since 0.1.0
 */
class Plugin {

	/**
	 * CSP Processor instance.
	 *
	 * @since 0.1.0
	 *
	 * @var CSP_Processor|null
	 */
	private ?CSP_Processor $processor = null;

	/**
	 * Output Buffer instance.
	 *
	 * @since 0.1.0
	 *
	 * @var Output_Buffer|null
	 */
	private ?Output_Buffer $output_buffer = null;

	/**
	 * Settings instance.
	 *
	 * @since 0.1.0
	 *
	 * @var Settings|null
	 */
	private ?Settings $settings = null;

	/**
	 * Admin Hooks instance.
	 *
	 * @since 0.1.0
	 *
	 * @var Admin_Hooks|null
	 */
	private ?Admin_Hooks $admin_hooks = null;

	/**
	 * Run the plugin.
	 *
	 * Registers all hooks and initializes components.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function run(): void {
		// Register settings (must happen early).
		add_action( 'admin_init', array( $this->get_settings(), 'register_settings' ) );

		// Register admin menu and assets.
		add_action( 'admin_menu', array( $this->get_admin_hooks(), 'add_menu_items' ) );
		add_action( 'admin_enqueue_scripts', array( $this->get_admin_hooks(), 'enqueue_admin_assets' ) );
		add_filter( 'plugin_action_links_' . ECSP_BASENAME, array( $this->get_admin_hooks(), 'add_plugin_action_links' ) );

		// Start output buffering for CSP processing.
		add_action( 'template_redirect', array( $this->get_output_buffer(), 'start_buffer' ) );
	}

	/**
	 * Get plugin version.
	 *
	 * @since 0.1.0
	 *
	 * @return string Plugin version.
	 */
	public function get_version(): string {
		return ECSP_VERSION;
	}

	/**
	 * Get CSP Processor instance (lazy-loaded).
	 *
	 * @since 0.1.0
	 *
	 * @return CSP_Processor CSP processor instance.
	 */
	public function get_processor(): CSP_Processor {
		if ( is_null( $this->processor ) ) {
			$this->processor = new CSP_Processor();
		}

		return $this->processor;
	}

	/**
	 * Get Output Buffer instance (lazy-loaded).
	 *
	 * @since 0.1.0
	 *
	 * @return Output_Buffer Output buffer instance.
	 */
	public function get_output_buffer(): Output_Buffer {
		if ( is_null( $this->output_buffer ) ) {
			$this->output_buffer = new Output_Buffer( $this->get_processor() );
		}

		return $this->output_buffer;
	}

	/**
	 * Get Settings instance (lazy-loaded).
	 *
	 * @since 0.1.0
	 *
	 * @return Settings Settings instance.
	 */
	public function get_settings(): Settings {
		if ( is_null( $this->settings ) ) {
			$this->settings = new Settings();
		}

		return $this->settings;
	}

	/**
	 * Get Admin Hooks instance (lazy-loaded).
	 *
	 * @since 0.1.0
	 *
	 * @return Admin_Hooks Admin hooks instance.
	 */
	public function get_admin_hooks(): Admin_Hooks {
		if ( is_null( $this->admin_hooks ) ) {
			$this->admin_hooks = new Admin_Hooks();
		}

		return $this->admin_hooks;
	}
}
