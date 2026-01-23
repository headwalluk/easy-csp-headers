<?php
/**
 * Output Buffer class.
 *
 * Manages output buffering for CSP processing.
 *
 * @package Easy_CSP_Headers
 * @since 0.1.0
 */

namespace Easy_CSP_Headers;

defined( 'ABSPATH' ) || die();

/**
 * Output buffering handler.
 *
 * @since 0.1.0
 */
class Output_Buffer {

	/**
	 * CSP Processor instance.
	 *
	 * @since 0.1.0
	 *
	 * @var CSP_Processor
	 */
	private CSP_Processor $processor;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param CSP_Processor $processor CSP processor instance.
	 */
	public function __construct( CSP_Processor $processor ) {
		$this->processor = $processor;
	}

	/**
	 * Start output buffering.
	 *
	 * Hooks into template_redirect to capture all output.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function start_buffer(): void {
		ob_start( array( $this, 'process_buffer_callback' ) );
	}

	/**
	 * Process buffered output.
	 *
	 * Callback for ob_start(). Passes HTML to CSP processor.
	 *
	 * @since 0.1.0
	 *
	 * @param string $html Buffered HTML output.
	 *
	 * @return string Processed HTML with CSP applied.
	 */
	public function process_buffer_callback( string $html ): string {
		return $this->processor->process_output( $html );
	}
}
