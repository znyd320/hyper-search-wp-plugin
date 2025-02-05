<?php

namespace Panda\Header_Footer\WidgetsManager\Modules\SiteLogo\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Panda\Header_Footer\WidgetsManager\Widget_Base_Custom;

if (!defined('ABSPATH')) {
    exit;
}

class Site_Logo extends Widget_Base_Custom
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'load_widget_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_widget_assets']);
    }

    public function get_name()
    {
        return 'panda-site-logo';
    }

    public function get_title()
    {
        return __('Site Logo', 'panda-hf');
    }

    public function get_icon()
    {
        return 'eicon-site-logo';
    }

    public function get_categories()
    {
        return ['panda-hf'];
    }

    protected function register_controls()
    {
        // Content Tab
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Logo Settings', 'panda-hf'),
                'tab' => Controls_Manager::TAB_CONTENT,
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
            'logo_link_type',
            [
                'label' => __('Logo Link', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'home',
                'options' => [
                    'none' => __('None', 'panda-hf'),
                    'home' => __('Home URL', 'panda-hf'),
                    'custom' => __('Custom URL', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'logo_custom_link',
            [
                'label' => __('Custom Link', 'panda-hf'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'condition' => [
                    'logo_link_type' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'logo_description_source',
            [
                'label' => __('Logo Description', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'panda-hf'),
                    'site_title' => __('Site Title', 'panda-hf'),
                    'site_tagline' => __('Site Tagline', 'panda-hf'),
                    'both' => __('Title & Tagline', 'panda-hf'),
                    'custom' => __('Custom', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'custom_description',
            [
                'label' => __('Custom Description', 'panda-hf'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'logo_description_source' => 'custom',
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

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'logo_image',
                'default' => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'panda-hf'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'panda-hf'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'panda-hf'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'panda-hf'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-site-logo' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Logo Style
        $this->start_controls_section(
            'section_style_logo',
            [
                'label' => __('Logo Style', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => __('Width', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'max_width',
            [
                'label' => __('Max Width', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'logo_border',
                'selector' => '{{WRAPPER}} .panda-site-logo img',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'logo_border_radius',
            [
                'label' => __('Border Radius', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-site-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'logo_box_shadow',
                'selector' => '{{WRAPPER}} .panda-site-logo img',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'logo_css_filters',
                'selector' => '{{WRAPPER}} .panda-site-logo img',
            ]
        );

        $this->end_controls_section();

        // Hover Effects Section
        $this->start_controls_section(
            'section_hover_effects',
            [
                'label' => __('Hover Effects', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'logo_hover_animation',
            [
                'label' => __('Hover Animation', 'panda-hf'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'logo_css_filters_hover',
                'selector' => '{{WRAPPER}} .panda-site-logo img:hover',
            ]
        );

        $this->add_control(
            'hover_transition',
            [
                'label' => __('Transition Duration', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-site-logo img' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_section();

        // Description Style Section
        $this->start_controls_section(
            'section_style_description',
            [
                'label' => __('Description Style', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'logo_description_source!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_align',
            [
                'label' => __('Description Alignment', 'panda-hf'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'panda-hf'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'panda-hf'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'panda-hf'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .panda-logo-description' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .panda-logo-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-logo-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_spacing',
            [
                'label' => __('Spacing', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-logo-description' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'panda-site-logo');

        if ($settings['logo_hover_animation']) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-animation-' . $settings['logo_hover_animation']);
        }

        echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';

        if ($settings['logo_source'] === 'site') {
            $logo_id = get_theme_mod('custom_logo');
            $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        } else {
            $logo_url = $settings['custom_logo']['url'];
        }

        $link_open = '';
        $link_close = '';

        if ($settings['logo_link_type'] === 'home') {
            $link_open = '<a href="' . esc_url(home_url('/')) . '">';
            $link_close = '</a>';
        } elseif ($settings['logo_link_type'] === 'custom' && !empty($settings['logo_custom_link']['url'])) {
            $this->add_link_attributes('logo_link', $settings['logo_custom_link']);
            $link_open = '<a ' . $this->get_render_attribute_string('logo_link') . '>';
            $link_close = '</a>';
        }

        if ($logo_url) {
            echo $link_open;
            echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="panda-logo-image">';
            echo $link_close;
        }

        if ($settings['logo_description_source'] !== 'none') {
            $description = '';
            switch($settings['logo_description_source']) {
                case 'site_title':
                    $description = get_bloginfo('name');
                    break;
                case 'site_tagline':
                    $description = get_bloginfo('description');
                    break;
                case 'both':
                    $description = sprintf(
                        '%s<br>%s',
                        get_bloginfo('name'),
                        get_bloginfo('description')
                    );
                    break;
                case 'custom':
                    $description = $settings['custom_description'];
                    break;
            }

            if ($description) {
                echo '<div class="panda-logo-description">' . wp_kses_post($description) . '</div>';
            }
        }
        echo '</div>';
    }
}
