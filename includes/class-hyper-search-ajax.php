<?php

/**
 * Ajax Handler
 *
 * @package Hyper_Search
 */

if (! defined('WPINC')) {
    die;
}

class Class_Hyper_Search_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_hyper_search_query', array($this, 'handle_search'));
        add_action('wp_ajax_nopriv_hyper_search_query', array($this, 'handle_search'));
    }

    public function handle_search()
    {
        check_ajax_referer('hyper_search_nonce', 'nonce');

        $search_query = sanitize_text_field($_POST['query']);
        $form_id     = absint($_POST['form_id']);

        $post_types = get_post_meta($form_id, '_hyper_search_post_types', true);
        $meta_keys  = get_post_meta($form_id, '_hyper_search_meta_keys', true);

        $args = array(
            'post_type'      => array_map('trim', explode(',', $post_types)),
            's'              => $search_query,
            'posts_per_page' => apply_filters('hyper_search_results_per_page', 10)
        );

        if (! empty($meta_keys)) {
            $args['meta_query'] = array(
                'relation' => 'OR'
            );
            foreach (array_map('trim', explode(',', $meta_keys)) as $meta_key) {
                $args['meta_query'][] = array(
                    'key'     => $meta_key,
                    'value'   => $search_query,
                    'compare' => 'LIKE'
                );
            }
        }

        $results = $this->get_search_results($args, $meta_keys);
        wp_send_json_success($results);
    }

    private function get_search_results($args, $meta_keys)
    {
        $query   = new WP_Query($args);
        $results = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $results[] = array(
                    'title'   => get_the_title(),
                    'url'     => get_permalink(),
                    'excerpt' => get_the_excerpt(),
                );
            }
        }

        wp_reset_postdata();
        return $results;
    }
}
