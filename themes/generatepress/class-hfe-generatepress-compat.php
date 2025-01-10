<?php
/**
 * GeneratepressCompatibility.
 *
 * @package  header-footer-elementor
 */

/**
 * PHF_GeneratePress_Compat setup
 *
 * @since 1.0
 */
class PHF_GeneratePress_Compat {

	/**
	 * Instance of PHF_GeneratePress_Compat
	 *
	 * @var PHF_GeneratePress_Compat|null
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 *
	 * @return PHF_GeneratePress_Compat
	 */
	// phpcs:ignore
	public static function instance(): PHF_GeneratePress_Compat {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new PHF_GeneratePress_Compat();

			add_action( 'wp', [ self::$instance, 'hooks' ] );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 * // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function hooks(): void {
		if ( phf_header_enabled() ) {
			add_action( 'template_redirect', [ $this, 'generatepress_setup_header' ] );
			add_action( 'generate_header', 'phf_render_header' );
		}

		if ( phf_before_footer_enabled() ) {
			add_action( 'generate_footer', 'phf_render_before_footer', 5 );
		}

		if ( phf_footer_enabled() ) {
			add_action( 'template_redirect', [ $this, 'generatepress_setup_footer' ] );
			add_action( 'generate_footer', 'phf_render_footer' );
		}
	}

	/**
	 * Disable header from the theme.
	 * // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function generatepress_setup_header(): void {
		remove_action( 'generate_header', 'generate_construct_header' );
	}

	/**
	 * Disable footer from the theme.
	 *
	 * // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function generatepress_setup_footer(): void {
		remove_action( 'generate_footer', 'generate_construct_footer_widgets', 5 );
		remove_action( 'generate_footer', 'generate_construct_footer' );
	}
}

PHF_GeneratePress_Compat::instance();
