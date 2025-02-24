<?php
/**
 * PHF_Astra_Compat setup
 *
 * @package header-footer-elementor
 */

/**
 * Astra theme compatibility.
 */
class PHF_Astra_Compat {

	/**
	 * Instance of PHF_Astra_Compat.
	 *
	 * @var PHF_Astra_Compat
	 */
	private static $instance;

	/**
	 *  Initiator
	 *
	 * @return PHF_Astra_Compat
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new PHF_Astra_Compat();

			add_action( 'wp', [ self::$instance, 'hooks' ] );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 *
	 * @return void
	 */
	public function hooks() {
		if ( phf_header_enabled() ) {
			add_action( 'template_redirect', [ $this, 'astra_setup_header' ], 10 );
			add_action( 'astra_header', 'phf_render_header' );
		}

		if ( phf_footer_enabled() ) {
			add_action( 'template_redirect', [ $this, 'astra_setup_footer' ], 10 );
			add_action( 'astra_footer', 'phf_render_footer' );
		}

		if ( phf_before_footer_enabled() ) {
			add_action( 'astra_footer_before', 'phf_render_before_footer' );
		}
	}

	/**
	 * Disable header from the theme.
	 *
	 * @return void
	 */
	public function astra_setup_header() {
		remove_action( 'astra_header', 'astra_header_markup' );

		// Remove the new header builder action.
		if ( class_exists( 'Astra_Builder_Helper' ) && Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_header', [ Astra_Builder_Header::get_instance(), 'prepare_header_builder_markup' ] );
		}
	}

	/**
	 * Disable footer from the theme.
	 *
	 * @return void
	 */
	public function astra_setup_footer() {
		remove_action( 'astra_footer', 'astra_footer_markup' );

		// Remove the new footer builder action.
		if ( class_exists( 'Astra_Builder_Helper' ) && Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_footer', [ Astra_Builder_Footer::get_instance(), 'footer_markup' ] );
		}
	}
}

PHF_Astra_Compat::instance();
