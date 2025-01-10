<?php
namespace Panda\Header_Footer\WidgetsManager\Modules\SiteLogo\Widgets;


use Elementor\Controls_Manager;
use Panda\Header_Footer\WidgetsManager\Widget_Base_Custom;

if (!defined('ABSPATH')) {
    exit;
}


class Site_Logo extends Widget_Base_Custom {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        add_action('elementor/frontend/after_enqueue_styles', [$this, 'load_widget_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_widget_assets']);
    }

    public function get_name() {
        return 'panda-site-logo';
    }

    public function get_title() {
        return __('Site Logo', 'panda-hf');
    }

    public function get_icon() {
        return 'eicon-site-logo';
    }

    public function get_categories() {
        return ['panda-hf'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'panda-hf'),
            ]
        );

        $this->add_control(
            'logo_source',
            [
                'label' => __('Logo Source', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'custom' => __('Custom Logo', 'panda-hf'),
                    'site' => __('Site Logo', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'custom_logo',
            [
                'label' => __('Choose Logo', 'panda-hf'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'logo_source' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ($settings['logo_source'] === 'site') {
            $logo_id = get_theme_mod('custom_logo');
            $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        } else {
            $logo_url = $settings['custom_logo']['url'];
        }

        if ($logo_url) {
            $animation_class = $settings['hover_animation'] ? 'logo-hover-' . $settings['hover_animation'] : '';
            echo '<div class="panda-site-logo ' . esc_attr($animation_class) . '">';
            echo '<img src="' . esc_url($logo_url) . '" alt="' . get_bloginfo('name') . '">';
            echo '</div>';
        }
    }
}
