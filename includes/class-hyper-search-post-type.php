<?php
/**
 * Post Type Registration
 *
 * @package Hyper_Search
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Class_Hyper_Search_Post_Type {
    public function __construct() {
        add_filter( 'manage_hyper-search_posts_columns', array( $this, 'set_columns' ) );
        add_action( 'manage_hyper-search_posts_custom_column', array( $this, 'render_column' ), 10, 2 );
    }

    public function register_post_type() {
        register_post_type(
            'hyper-search',
            array(
                'labels'              => array(
                    'name'               => __( 'Search Forms', 'hyper-search' ),
                    'singular_name'      => __( 'Search Form', 'hyper-search' ),
                    'add_new'           => __( 'Add New Form', 'hyper-search' ),
                    'add_new_item'      => __( 'Add New Search Form', 'hyper-search' ),
                    'edit_item'         => __( 'Edit Search Form', 'hyper-search' ),
                    'all_items'         => __( 'All Search Forms', 'hyper-search' ),
                ),
                'public'              => false,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'menu_icon'           => 'dashicons-search',
                'supports'            => array( 'title' ),
                'rewrite'             => false,
                'show_in_rest'        => false,
                'publicly_queryable'  => false,
                'hierarchical'        => false,
            )
        );
    }

    public function set_columns( $columns ) {

        $new_columns = array(
            'cb'        => $columns['cb'],
            'title'     => __( 'Title', 'hyper-search' ),
            'shortcode' => __( 'Shortcode', 'hyper-search' ),
            'date'      => __( 'Date', 'hyper-search' ),
        );
        return $new_columns;
    }

    public function render_column( $column, $post_id ) {

        switch ( $column ) {
            case 'shortcode':
                $shortcode = sprintf( '[hyper_search id="%d"]', $post_id );
                printf(
                    '<input type="text" class="widefat" readonly value="%s" onclick="this.select()">',
                    esc_attr( $shortcode )
                );
                break;
        }
    }
}
