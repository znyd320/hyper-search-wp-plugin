<?php

namespace Panda\Header_Footer;

class Theme_Compatibility
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('wp', [$this, 'init_hooks']);
    }

    public function init_hooks()
    {

        // Method 1: Template override
        // add_action('get_header', [$this, 'override_theme_header']);

        // Method 2: Action removal
        // add_action('template_redirect', [$this, 'remove_theme_header_actions']);

        // Add CSS to hide theme header if needed

        // wp_register_style('panda-hf-styles', false);
        // wp_enqueue_style('panda-hf-styles');
        // add_action('wp_enqueue_scripts', [$this, 'add_header_hiding_styles']);
        // add_action('admin_enqueue_scripts', [$this, 'add_header_hiding_styles']);
    }

    public function remove_theme_header(){
        wp_register_style('panda-hf-styles', false);
        wp_enqueue_style('panda-hf-styles');
        add_action('wp_enqueue_scripts', [$this, 'add_header_hiding_styles']);
        add_action('admin_enqueue_scripts', [$this, 'add_header_hiding_styles']);
    }

    public function override_theme_header()
    {
        $templates = [];
        $templates[] = 'header.php';

        // Remove existing wp_head hooks
        remove_all_actions('wp_head');

        // Use wp_enqueue_block_template_skip_link
        wp_enqueue_block_template_skip_link();

        ob_start();
        locate_template($templates, true);
        ob_get_clean();
    }

    public function remove_theme_header_actions()
    {
        // Remove common theme header hooks
        remove_all_actions('get_header');
        remove_all_actions('wp_head');

        // Theme specific removals
        if (function_exists('astra_header_markup')) {
            remove_action('astra_header', 'astra_header_markup');
        }

        if (function_exists('generate_construct_header')) {
            remove_action('generate_header', 'generate_construct_header');
        }

        if (function_exists('oceanwp_header_template')) {
            remove_action('ocean_header', 'oceanwp_header_template');
        }
    }

    public function add_header_hiding_styles()
    {
        $css = '
        header#masthead,
        header#site-header,
        .site-header:not(.panda-header)
            {
                display: none !important;
            }
        ';
        wp_add_inline_style('panda-hf-styles', $css);
    }
}
