<?php
class Hyper_Search_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'hyper-search';
    }
    
    public function get_title() {
        return 'Hyper Search';
    }
    
    public function get_icon() {
        return 'eicon-search';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Search Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $search_forms = get_posts([
            'post_type' => 'hyper_search',
            'posts_per_page' => -1,
        ]);
        
        $forms_options = [];
        foreach($search_forms as $form) {
            $forms_options[$form->ID] = $form->post_title;
        }
        
        $this->add_control(
            'search_form_id',
            [
                'label' => 'Select Search Form',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $forms_options,
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        echo do_shortcode('[hyper_search id="' . $settings['search_form_id'] . '"]');
    }
}
