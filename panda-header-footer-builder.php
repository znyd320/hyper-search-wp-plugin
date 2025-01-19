<?php

/**
 * Plugin Name: Panda Header Footer Builder
 * Description: Build header and footer with Elementor
 * Version: 1.0.0
 * Author: Jonayed320
 * text-domain: panda-hf
 */

define('PANDA_HF_VERSION', '1.0.0');
define('PANDA_HF_PATH', plugin_dir_path(__FILE__));
define('PANDA_HF_URL', plugin_dir_url(__FILE__));
define('PANDA_HF_FILE', __FILE__);

require_once PANDA_HF_PATH . 'includes/class-panda-core.php';

function panda_hf_plugin_init()
{
    \Panda\Header_Footer\Core::get_instance();
}

add_action('plugins_loaded', 'panda_hf_plugin_init');


function panda_hf_enqueue_font_awesome() {

	if ( class_exists( 'Elementor\Plugin' ) ) {
		
		wp_enqueue_style(
			'phf-icons-list',
			plugins_url( '/elementor/assets/css/widget-icon-list.min.css', 'elementor' ),
			[],
			'3.24.3'
		);
		wp_enqueue_style(
			'phf-social-icons',
			plugins_url( '/elementor/assets/css/widget-social-icons.min.css', 'elementor' ),
			[],
			'3.24.0'
		);
		wp_enqueue_style(
			'phf-social-share-icons-brands',
			plugins_url( '/elementor/assets/lib/font-awesome/css/brands.css', 'elementor' ),
			[],
			'5.15.3'
		);

		wp_enqueue_style(
			'phf-social-share-icons-fontawesome',
			plugins_url( '/elementor/assets/lib/font-awesome/css/fontawesome.css', 'elementor' ),
			[],
			'5.15.3'
		);
		wp_enqueue_style(
			'phf-nav-menu-icons',
			plugins_url( '/elementor/assets/lib/font-awesome/css/solid.css', 'elementor' ),
			[],
			'5.15.3'
		);
	}
	if ( class_exists( '\ElementorPro\Plugin' ) ) {
		wp_enqueue_style(
			'phf-widget-blockquote',
			plugins_url( '/elementor-pro/assets/css/widget-blockquote.min.css', 'elementor' ),
			[],
			'3.25.0'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'panda_hf_enqueue_font_awesome', 20 );
