<?php
/**
 * PHF_Default_Compat setup
 *
 * @package header-footer-elementor
 */

namespace PHE\Themes;

/**
 * Astra theme compatibility.
 */
class PHF_Default_Compat {

	/**
	 *  Initiator
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'hooks' ] );
	}

	/**
	 * Run all the Actions / Filters.
	 *
	 * @return void
	 *
	 * // phpcs:ignore
	 */
	// phpcs:ignore
	public function hooks(): void {
		if ( phf_header_enabled() ) {
			// Replace header.php template.
			add_action( 'get_header', [ $this, 'override_header' ] );

			// Display HFE's header in the replaced header.
			add_action( 'phf_header', 'phf_render_header' );
		}

		if ( phf_footer_enabled() || phf_before_footer_enabled() ) {
			// Replace footer.php template.
			add_action( 'get_footer', [ $this, 'override_footer' ] );
		}

		if ( phf_footer_enabled() ) {
			// Display HFE's footer in the replaced header.
			add_action( 'phf_footer', 'phf_render_footer' );
		}

		if ( phf_before_footer_enabled() ) {
			add_action('phf_footer_before', 'phf_render_before_footer');
		}
	}

	/**
	 * Function for overriding the header in the elmentor way.
	 *
	 * @since 1.2.0
	 *
	 * // phpcs:ignore
	 * @return void
	 */ 
	// phpcs:ignore
	public function override_header(): void {
		require PANDA_HF_PATH . 'themes/default/hfe-header.php';
		$templates   = [];
		$templates[] = 'header.php';
		// Avoid running wp_head hooks again.
		remove_all_actions( 'wp_head' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

	/**
	 * Function for overriding the footer in the elmentor way.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 *
	 * // phpcs:ignore
	 */ 
	// phpcs:ignore
	public function override_footer(): void {
		require PANDA_HF_PATH . 'themes/default/hfe-footer.php';
		$templates   = [];
		$templates[] = 'footer.php';
		// Avoid running wp_footer hooks again.
		remove_all_actions( 'wp_footer' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}
}

new PHF_Default_Compat();
