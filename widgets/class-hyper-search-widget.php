<?php

/**
 * Elementor Widget
 *
 * @package Hyper_Search
 */

if (! defined('WPINC')) {
    die;
};

class Class_Hyper_Search_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'hyper-search';
    }

    public function get_title()
    {
        return esc_html__('Hyper Search', 'hyper-search');
    }

    public function get_icon()
    {
        return 'eicon-search';
    }

    public function get_categories()
    {
        return array('general');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            array(
                'label' => esc_html__('Search Settings', 'hyper-search'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'form_id',
            array(
                'label'   => esc_html__('Select Search Form', 'hyper-search'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_search_forms(),
            )
        );

        $this->end_controls_section();
    }

    private function get_search_forms()
    {
        $forms = get_posts(array(
            'post_type'      => 'hyper-search',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ));

        $options = array();
        foreach ($forms as $form) {
            $options[$form->ID] = $form->post_title;
        }
        return $options;
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['form_id'])) {
            return;
        }

        $post_types = get_post_meta($settings['form_id'], '_hyper_search_post_types', true);
        $meta_keys = get_post_meta($settings['form_id'], '_hyper_search_meta_keys', true);


        wp_enqueue_style('hyper-search');
        wp_enqueue_script('hyper-search');

        include HYPER_SEARCH_PATH . 'templates/form.php';
    }
}
