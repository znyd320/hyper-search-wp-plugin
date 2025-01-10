<?php

/**
 * Support all themes.
 *
 * @package header-footer-elementor
 */

namespace PHF\Themes;

use Panda\Header_Footer\Template_Loader;

/**
 * Global theme compatibility.
 */
class Global_Theme_Compatibility
{

	/**
	 *  Initiator
	 */
	public function __construct()
	{
		add_action('wp', [$this, 'hooks']);
	}

	/**
	 * Run all the Actions / Filters.
	 *
	 * @return void
	 */
	// phpcs:ignore
	public function hooks(): void
	{
		if (phf_header_enabled()) {
			// Replace header.php.
			add_action('get_header', [$this, 'option_override_header']);

			// add_action( 'wp_body_open', 'phf_render_header' );
			add_action('wp_body_open', [Template_Loader::get_instance(), 'render_header'], 10);
			add_action('phf_fallback_header', [Template_Loader::get_instance(), 'render_header']);
		}

		if (phf_before_footer_enabled()) {
			add_action('wp_footer', [Template_Loader::get_instance(), 'render_before_footer'], 20);
		}

		if (phf_footer_enabled()) {
			add_action('wp_footer', [Template_Loader::get_instance(), 'render_footer'], 50);
		}

		if (phf_header_enabled() || phf_footer_enabled()) {
			wp_enqueue_style( 'panda-frontend-style' );
			add_action('wp_enqueue_scripts', [$this, 'force_fullwidth']);
		}
	}

	/**
	 * Force full width CSS for the header.
	 *
	 * @since 1.2.0
	 *
	 *  // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function force_fullwidth(): void
	{
		$css = '
		.force-stretched-header {
			width: 100vw;
			position: relative;
			margin-left: -50vw;
			left: 50%;
		}';

		if (true === phf_header_enabled()) {
			$css .= 'header#masthead {
				display: none;
			}';
		}

		if (true === phf_footer_enabled()) {
			$css .= 'footer#colophon {
				display: none;
			}';
		}

		// wp_add_inline_style( 'hfe-style', $css );
		wp_add_inline_style('panda-frontend-style', $css);
	}

	/**
	 * Function overriding the header in the wp_body_open way.
	 *
	 * @since 1.2.0
	 *
	 * // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function option_override_header(): void
	{
		$templates   = [];
		$templates[] = 'header.php';
		locate_template($templates, true);

		if (! did_action('wp_body_open')) {
			echo '<div class="force-stretched-header">';
			do_action('phf_fallback_header');
			echo '</div>';
		}
	}
}
new Global_Theme_Compatibility();
