<?php
/**
 * PHF_Storefront_Compat setup
 *
 * @package header-footer-elementor
 */

/**
 * Astra theme compatibility.
 */
class PHF_Storefront_Compat {

	/**
	 * Instance of PHF_Storefront_Compat.
	 *
	 * @var $PHF_Storefront_Compat
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 *
	 *  @return PHF_Storefront_Compat
	 */
	// phpcs:ignore
	public static function instance(): PHF_Storefront_Compat {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new PHF_Storefront_Compat();

			add_action( 'wp', [ self::$instance, 'hooks' ] );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 *
	 * @return void
	 */
	// phpcs:ignore
	public function hooks(): void {
		if ( phf_header_enabled() ) {
			add_action( 'template_redirect', [ $this, 'setup_header' ], 10 );
			add_action( 'storefront_before_header', 'phf_render_header', 500 );
		}

		if ( phf_footer_enabled() ) {
			add_action( 'template_redirect', [ $this, 'setup_footer' ], 10 );
			add_action( 'storefront_after_footer', 'phf_render_footer', 500 );
		}

		if ( phf_before_footer_enabled() ) {
			add_action( 'storefront_before_footer', 'phf_render_before_footer' );
		}

		if ( phf_header_enabled() || phf_footer_enabled() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'styles' ] );
		}
	}

	/**
	 * Add inline CSS to hide empty divs for header and footer in storefront
	 *
	 * @since 1.2.0
	 * 
	 * // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function styles(): void {
		$css = '';

		if ( true === phf_header_enabled() ) {
			$css .= '.site-header {
				display: none;
			}';
		}

		if ( true === phf_footer_enabled() ) {
			$css .= '.site-footer {
				display: none;
			}';
		}

		wp_add_inline_style( 'hfe-style', $css );
	}

	/**
	 * 
	 * Disable header from the theme.
	 * 
	 * @return void
	 *
	 * // phpcs:ignore
	 */
	// phpcs:ignore
	public function setup_header(): void {
		for ( $priority = 0; $priority < 200; $priority++ ) {
			remove_all_actions( 'storefront_header', $priority );
		}
	}
	// phpcs:ignore
	/**
	 * Disable footer from the theme.
	 * // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function setup_footer(): void {
		for ( $priority = 0; $priority < 200; $priority++ ) {
			remove_all_actions( 'storefront_footer', $priority );
		}
	}
}

PHF_Storefront_Compat::instance();
