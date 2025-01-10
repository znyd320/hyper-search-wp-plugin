<?php
/**
 * PHF_OceanWP_Compat setup
 *
 * @package header-footer-elementor
 */

/**
 * OceanWP theme compatibility.
 */
class PHF_OceanWP_Compat {

	/**
	 * Instance of PHF_OceanWP_Compat.
	 *
	 * @var PHF_OceanWP_Compat
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new PHF_OceanWP_Compat();

			add_action( 'wp', [ self::$instance, 'hooks' ] );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function hooks() {
		if ( phf_header_enabled() ) {
			add_action( 'template_redirect', [ $this, 'setup_header' ], 10 );
			add_action( 'ocean_header', 'phf_render_header' );
		}

		if ( phf_before_footer_enabled() ) {
			add_action( 'ocean_footer', 'phf_render_before_footer', 5 );
		}

		if ( phf_footer_enabled() ) {
			add_action( 'template_redirect', [ $this, 'setup_footer' ], 10 );
			add_action( 'ocean_footer', 'phf_render_footer' );
		}
	}

	/**
	 * Disable header from the theme.
	 */
	public function setup_header() {
		remove_action( 'ocean_top_bar', 'oceanwp_top_bar_template' );
		remove_action( 'ocean_header', 'oceanwp_header_template' );
		remove_action( 'ocean_page_header', 'oceanwp_page_header_template' );
	}

	/**
	 * Disable footer from the theme.
	 */
	public function setup_footer() {
		remove_action( 'ocean_footer', 'oceanwp_footer_template' );
	}

}

PHF_OceanWP_Compat::instance();
