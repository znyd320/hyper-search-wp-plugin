<?php
/**
 * Admin Settings Handler
 *
 * @package Hyper_Search
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Class_Hyper_Search_Admin {
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post_hyper-search', array( $this, 'save_meta_box' ) );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'hyper-search-settings',
            __( 'Search Settings', 'hyper-search' ),
            array( $this, 'render_meta_box' ),
            'hyper-search',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'hyper_search_settings', 'hyper_search_nonce' );
        
        $post_types = get_post_meta( $post->ID, '_hyper_search_post_types', true );
        $meta_keys  = get_post_meta( $post->ID, '_hyper_search_meta_keys', true );

        wp_enqueue_script( 'hyper-search' );
        
        include HYPER_SEARCH_PATH . 'templates/form.php';
    }

    public function save_meta_box( $post_id ) {
        if ( ! isset( $_POST['hyper_search_nonce'] ) || 
             ! wp_verify_nonce( $_POST['hyper_search_nonce'], 'hyper_search_settings' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['hyper_search_post_types'] ) ) {
            update_post_meta(
                $post_id,
                '_hyper_search_post_types',
                sanitize_text_field( $_POST['hyper_search_post_types'] )
            );
        }

        if ( isset( $_POST['hyper_search_meta_keys'] ) ) {
            update_post_meta(
                $post_id,
                '_hyper_search_meta_keys',
                sanitize_text_field( $_POST['hyper_search_meta_keys'] )
            );
        }
    }
}
