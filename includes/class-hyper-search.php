<?php

/**
 * Main plugin class
 *
 * @package Hyper_Search
 */

if (! defined('WPINC')) {
    die;
}

class Class_Hyper_Search
{
    /**
     * Single instance of the class
     *
     * @var Class_Hyper_Search|null
     */
    private static $instance = null;

    /**
     * Returns the singleton instance of this class
     *
     * @return Class_Hyper_Search
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load required dependencies
     */
    private function load_dependencies()
    {
        require_once HYPER_SEARCH_PATH . 'includes/class-hyper-search-post-type.php';
        require_once HYPER_SEARCH_PATH . 'includes/class-hyper-search-ajax.php';
        require_once HYPER_SEARCH_PATH . 'includes/admin/class-hyper-search-admin.php';
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks()
    {
        add_action('init', array($this, 'init'));
        add_action('elementor/widgets/register', array($this, 'register_widgets'));
        add_action('wp_enqueue_scripts', array($this, 'register_assets'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_shortcode('hyper_search', array($this, 'render_shortcode'));
    }

    /**
     * Render shortcode output
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_shortcode($atts)
    {
        $attributes = shortcode_atts(
            array(
                'id' => 0
            ),
            $atts
        );

        if (empty($attributes['id'])) {
            return '';
        }

        wp_enqueue_style('hyper-search');
        wp_enqueue_script('hyper-search');
        
        ob_start();
        include HYPER_SEARCH_PATH . 'templates/form.php';
        return ob_get_clean();
    }

    /**
     * Initialize plugin components
     */
    public function init()
    {
        $post_type = new Class_Hyper_Search_Post_Type();
        $post_type->register_post_type();
        
        new Class_Hyper_Search_Ajax();
        new Class_Hyper_Search_Admin();
    }

    /**
     * Register Elementor widgets
     *
     * @param object $widgets_manager Elementor widgets manager
     */
    public function register_widgets($widgets_manager)
    {
        require_once HYPER_SEARCH_PATH . 'widgets/class-hyper-search-widget.php';
        $widgets_manager->register(new Class_Hyper_Search_Widget());
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts()
    {
        // Enqueue admin styles
        wp_enqueue_style(
            'hyper-search',
            HYPER_SEARCH_URL . 'assets/css/style.css',
            array(),
            HYPER_SEARCH_VERSION
        );

        // Enqueue admin scripts
        wp_enqueue_script(
            'hyper-search',
            HYPER_SEARCH_URL . 'assets/js/script.js',
            array('jquery'),
            HYPER_SEARCH_VERSION
        );

        // Localize script data
        $this->localize_script_data('hyper-search');
    }

    /**
     * Register front-end assets
     */
    public function register_assets()
    {
        // Register styles
        wp_register_style(
            'hyper-search',
            HYPER_SEARCH_URL . 'assets/css/style.css',
            array(),
            HYPER_SEARCH_VERSION
        );

        // Register scripts
        wp_register_script(
            'hyper-search',
            HYPER_SEARCH_URL . 'assets/js/script.js',
            array('jquery'),
            HYPER_SEARCH_VERSION,
            true
        );

        // Localize script data
        $this->localize_script_data('hyper-search');
    }

    /**
     * Localize script data helper
     *
     * @param string $handle Script handle
     */
    private function localize_script_data($handle)
    {
        wp_localize_script(
            $handle,
            'hyperSearchData',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('hyper_search_nonce'),
            )
        );
    }
}
