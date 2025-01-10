<?php
/**
 * PHF_Hello_Elementor_Compat setup
 *
 * @package header-footer-elementor
 */

/**
 * Hello Elementor compatibility.
 */
class PHF_Hello_Elementor_Compat {

	/**
	 * Instance of PHF_Hello_Elementor_Compat.
	 *
	 * @var PHF_Hello_Elementor_Compat|null
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 *
	 * @return PHF_Hello_Elementor_Compat
	 */
	// phpcs:ignore
	public static function instance(): PHF_Hello_Elementor_Compat {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new PHF_Hello_Elementor_Compat();

			if ( ! class_exists( 'PHF_Default_Compat' ) ) {
				require_once PANDA_HF_PATH . 'themes/default/class-hfe-default-compat.php';
			}
		}

		return self::$instance;
	}
}

PHF_Hello_Elementor_Compat::instance();
