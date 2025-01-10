<?php

namespace Panda\Header_Footer\Admin;

use Panda\Admin\Admin_Settings;

class Admin
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
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_assets']);
        add_action('save_post_panda_template', [$this, 'save_template_meta']);
        add_action('add_meta_boxes', [$this, 'add_template_meta_boxes']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
    }

    public function enqueue_frontend_assets()
    {
        // Register and enqueue frontend styles and scripts
        wp_register_style('panda-frontend-style', PANDA_HF_URL . 'admin/assets/css/frontend.css', [], PANDA_HF_VERSION);
        wp_register_script('panda-frontend-script', PANDA_HF_URL . 'admin/assets/js/frontend.js', ['jquery'], PANDA_HF_VERSION, true);

        wp_enqueue_style('panda-frontend-style');
        wp_enqueue_script('panda-frontend-script');
    }



    public function add_template_meta_boxes()
    {
        add_meta_box(
            'panda_template_settings',
            'Template Settings',
            [$this, 'render_template_settings'],
            'panda_template',
            'normal',
            'high'
        );
    }



    public function render_template_settings($post)
    {
        wp_nonce_field('panda_template_settings', 'panda_template_nonce');

        // Get saved values or set defaults
        $template_type = get_post_meta($post->ID, '_template_type', true) ?: '';
        $display_condition = get_post_meta($post->ID, '_display_condition', true) ?: '';
        $display_location = get_post_meta($post->ID, '_display_location', true) ?: '';
        $user_roles = get_post_meta($post->ID, '_user_roles', true) ?: 'all';
        $post_id = $post->ID;

        // Get all registered post types
        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $post_types = get_post_types($args, 'names', 'and');
        $post_types = array_merge(['post' => 'post', 'page' => 'page'], $post_types);

        $location_options_singulars = [];
        foreach ($post_types as $post_type) {
            if ($post_type === "panda_template") {
                continue;
            }
            if (has_filter('the_content')) {
                $location_options_singulars["before_single--{$post_type}"] = "Before single {$post_type}";
                $location_options_singulars["after_single--{$post_type}"] = "After single {$post_type}";
            }
        }


        include PANDA_HF_PATH . 'templates/template-meta-box.php';
    }



    public function save_template_meta($post_id)
    {
        if (
            !isset($_POST['panda_template_nonce']) ||
            !wp_verify_nonce($_POST['panda_template_nonce'], 'panda_template_settings')
        ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save template type
        if (isset($_POST['template_type'])) {
            update_post_meta($post_id, '_template_type', sanitize_text_field($_POST['template_type']));
        }

        // Save display condition
        if (isset($_POST['display_condition'])) {
            update_post_meta($post_id, '_display_condition', sanitize_text_field($_POST['display_condition']));
        }

        // Save display position
        if (isset($_POST['display_location'])) {
            update_post_meta($post_id, '_display_location', sanitize_text_field($_POST['display_location']));
        }

        // Save user roles
        if (isset($_POST['user_roles'])) {
            update_post_meta($post_id, '_user_roles', sanitize_text_field($_POST['user_roles']));
        }
    }



    public function enqueue_assets($hook)
    {
        if (!in_array($hook, ['post.php', 'post-new.php'])) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->post_type !== 'panda_template') {
            return;
        }

        wp_enqueue_style(
            'panda-admin',
            PANDA_HF_PATH . 'admin/assets/css/admin.css',
            [],
            PANDA_HF_VERSION
        );

        wp_enqueue_script(
            'panda-admin',
            PANDA_HF_PATH . 'admin/assets/js/admin.js',
            ['jquery'],
            PANDA_HF_VERSION,
            true
        );

        wp_localize_script('panda-admin', 'pandaAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('panda_ajax_nonce')
        ]);
    }

    public function enqueue_editor_assets()
    {
        if (get_post_type() !== 'panda_template') {
            return;
        }

        wp_enqueue_script(
            'panda-elementor',
            PANDA_HF_PATH . 'admin/assets/js/elementor.js',
            ['jquery', 'elementor-editor'],
            PANDA_HF_VERSION,
            true
        );
    }
}
