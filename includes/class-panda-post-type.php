<?php

namespace Panda\Header_Footer;

/**
 * Post Type Handler Class
 */
class Post_Type
{

    /**
     * Instance of the class
     * @var Post_Type|null
     */
    private static $instance = null;

    /**
     * Get instance of the class
     * @return Post_Type
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
    public function __construct()
    {
        // Register post type and admin menu
        add_action('init', [$this, 'register_post_type']);
        add_action('admin_menu', [$this, 'register_panda_admin_submenu_menu']);

        // Elementor canvas and title removal
        add_action('elementor/documents/register', function ($documents_manager) {
            $documents_manager->register_document_type('panda_template', \Elementor\Core\DocumentTypes\Page::class);
        });
        add_filter('template_include', function ($template) {
            if (is_singular('panda_template') && isset($_GET['elementor-preview'])) {
                return ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';
            }
            return $template;
        });

        add_filter(
            'elementor/admin-top-bar/is-active',
            function ($is_active, $current_screen) {
                if (strpos($current_screen->id, 'panda_template') !== false) {
                    return false;
                }
                return $is_active;
            },
            9,
            2
        );

        // Add shortcode column to post list
        $this->setup_admin_columns();
    }

    /**
     * Setup admin columns for the post type
     */
    private function setup_admin_columns()
    {
        add_filter('manage_panda_template_posts_columns', function ($columns) {
            $columns['shortcode'] = 'Shortcode';
            return $columns;
        });

        add_action('manage_panda_template_posts_custom_column', function ($column_name, $post_id) {
            if ($column_name === 'shortcode') {
                static $rendered = [];
                if (!isset($rendered[$post_id])) {
                    echo '<input type="text" readonly value="[panda_template id=\'' . $post_id . '\']" onclick="this.select()" style="width: 100%" title="Shortcode will work only if template type is Shortcode">';
                    $rendered[$post_id] = true;
                }
            }
        }, 10, 2);


        add_filter('manage_panda_template_posts_columns', function ($columns) {
            $columns['display_rules'] = 'Display Rules';
            return $columns;
        });

        add_action('manage_panda_template_posts_custom_column', function ($column_name, $post_id) {
            if ($column_name === 'display_rules') {
                $user_role = get_post_meta($post_id, '_user_roles', true);
                $display_condition = get_post_meta($post_id, '_display_condition', true);

                static $rendered = [];
                if (!isset($rendered[$post_id])) {
                    if ($user_role) {
                        echo '<div style="margin-bottom: 5px;"><strong>Users:</strong> ' . ucwords(str_replace('_', ' ', $user_role)) . '</div>';
                    }
                    if ($display_condition) {
                        echo '<div><strong>Display:</strong> ' . ucwords(str_replace('_', ' ', $display_condition)) . '</div>';
                    }
                    if (!$user_role && !$display_condition) {
                        echo '-';
                    }
                    $rendered[$post_id] = true;
                }
            }
        }, 10, 2);    }

    /**
     * Register submenu under Panda Dashboard
     */
    public function register_panda_admin_submenu_menu()
    {
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
    public function register_post_type()
    {
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
            'not_found_in_trash' => 'No templates found in Trash'
        ];

        $args = [
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => false,
            'supports'          => ['title', 'elementor'],
            'hierarchical'      => false,
            'has_archive'       => false,
            'publicly_queryable' => true,
            'rewrite'           => false
        ];

        register_post_type('panda_template', $args);
    }
}
