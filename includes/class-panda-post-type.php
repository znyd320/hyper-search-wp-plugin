<?php

namespace Panda\Header_Footer;

/**
 * Post Type Handler Class
 */
class Post_Type {

    /**
     * Instance of the class
     * @var Post_Type|null
     */
    private static $instance = null;

    /**
     * Get instance of the class
     * @return Post_Type
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    public function __construct() {
        // Register post type and admin menu
        add_action('init', [$this, 'register_post_type']);
        add_action('admin_menu', [$this, 'register_panda_admin_submenu_menu']);

        // Add shortcode column to post list
        $this->setup_admin_columns();
    }

    /**
     * Setup admin columns for the post type
     */
    private function setup_admin_columns() {
        add_filter('manage_panda_template_posts_columns', function($columns) {
            $columns['shortcode'] = 'Shortcode';
            return $columns;
        });

        add_action('manage_panda_template_posts_custom_column', function($column_name, $post_id) {
            if ($column_name === 'shortcode') {
                static $rendered = [];
                if (!isset($rendered[$post_id])) {
                    echo '<input type="text" readonly value="[panda_template id=\'' . $post_id . '\']" onclick="this.select()" style="width: 100%" title="Shortcut will work only if template type is Shortcut">';
                    $rendered[$post_id] = true;
                }
            }
        }, 10, 2);
    }

    /**
     * Register submenu under Panda Dashboard
     */
    public function register_panda_admin_submenu_menu() {
        add_submenu_page(
            'panda-dashboard',
            'Header Footer',
            'Header Footer',
            'manage_options',
            'edit.php?post_type=panda_template'
        );
    }

    /**
     * Register the custom post type
     */
    public function register_post_type() {
        $labels = [
            'name'               => 'Header Footer',
            'singular_name'      => 'Template',
            'add_new'           => 'Add New Template',
            'add_new_item'      => 'Add New Template',
            'edit_item'         => 'Edit Template',
            'new_item'          => 'New Template',
            'view_item'         => 'View Template',
            'search_items'      => 'Search Templates',
            'not_found'         => 'No templates found',
            'not_found_in_trash'=> 'No templates found in Trash'
        ];

        $args = [
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => false,
            'supports'          => ['title', 'elementor'],
            'hierarchical'      => false,
            'has_archive'       => false,
            'publicly_queryable'=> true,
            'rewrite'           => false
        ];

        register_post_type('panda_template', $args);
    }
}
