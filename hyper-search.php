<?php
/**
 * Plugin Name: Hyper Search
 * Plugin URI: https://example.com/hyper-search
 * Description: Advanced search functionality for any post type with content and meta search capabilities
 * Version: 1.0.0
 * Requires at least: 5.6
 * Requires PHP: 7.2
 * Author: Jonayed320
 * Author URI: https://profiles.wordpress.org/jonayed320/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hyper-search
 * Domain Path: /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'HYPER_SEARCH_VERSION', '1.0.0' );
define( 'HYPER_SEARCH_FILE', __FILE__ );
define( 'HYPER_SEARCH_PATH', plugin_dir_path( HYPER_SEARCH_FILE ) );
define( 'HYPER_SEARCH_URL', plugin_dir_url( HYPER_SEARCH_FILE ) );

require_once HYPER_SEARCH_PATH . 'includes/class-hyper-search.php';
function hyper_search_init() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'hyper_search_admin_notice' );
    }
    Class_Hyper_Search::get_instance();
}

function hyper_search_admin_notice() {
    $install_url = wp_nonce_url(
        add_query_arg(
            array(
                'action' => 'install-plugin',
                'plugin' => 'elementor'
            ),
            admin_url( 'update.php' )
        ),
        'install-plugin_elementor'
    );
    
    $message = sprintf(
        '%s <a href="%s" class="button button-primary">%s</a>',
        esc_html__( 'Hyper Search requires Elementor to be installed and activated.', 'hyper-search' ),
        esc_url( $install_url ),
        esc_html__( 'Install Elementor Now', 'hyper-search' )
    );
    printf( '<div class="notice notice-warning"><p>%s</p></div>', $message );
}
add_action( 'plugins_loaded', 'hyper_search_init' );