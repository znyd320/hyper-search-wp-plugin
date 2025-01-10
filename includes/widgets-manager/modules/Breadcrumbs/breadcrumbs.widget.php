<?php
namespace Panda\Header_Footer\WidgetsManager\Modules\Breadcrumbs\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Panda\Header_Footer\WidgetsManager\Widget_Base_Custom;

if (!defined('ABSPATH')) {
    exit;
}

class Breadcrumbs extends Widget_Base_Custom {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        add_action('elementor/frontend/before_enqueue_styles', [$this, 'load_widget_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_widget_assets']);
    }

    public function get_name() {
        return 'panda-breadcrumbs';
    }

    public function get_title() {
        return __('Breadcrumbs', 'panda-hf');
    }

    public function get_icon() {
        return 'eicon-navigation-horizontal';
    }

    public function get_categories() {
        return ['panda-hf'];
    }

    protected function register_controls() {
        // General Section
        $this->start_controls_section(
            'section_general',
            [
                'label' => __('General', 'panda-hf'),
            ]
        );

        $this->add_control(
            'show_home',
            [
                'label' => __('Show Home', 'panda-hf'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'home_icon',
            [
                'label' => __('Home Icon', 'panda-hf'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_home' => 'yes',
                ],
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Separator Section
        $this->start_controls_section(
            'section_separator',
            [
                'label' => __('Separator', 'panda-hf'),
            ]
        );

        $this->add_control(
            'separator_type',
            [
                'label' => __('Separator Type', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => __('Text', 'panda-hf'),
                    'icon' => __('Icon', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'separator_text',
            [
                'label' => __('Separator', 'panda-hf'),
                'type' => Controls_Manager::TEXT,
                'default' => __('»', 'panda-hf'),
                'condition' => [
                    'separator_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'separator_icon',
            [
                'label' => __('Separator Icon', 'panda-hf'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'separator_type' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumbs_typography',
                'selector' => '{{WRAPPER}} .panda-breadcrumbs',
            ]
        );

        $this->add_control(
            'breadcrumbs_text_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'breadcrumbs_link_color',
            [
                'label' => __('Link Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __('Separator Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs-separator' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $delimiter = 'text' === $settings['separator_type'] ? $settings['separator_text'] : '';
        
        if ('icon' === $settings['separator_type']) {
            ob_start();
            Icons_Manager::render_icon($settings['separator_icon'], ['aria-hidden' => 'true']);
            $delimiter = ob_get_clean();
        }

        $breadcrumbs = [];
        
        if ('yes' === $settings['show_home']) {
            $breadcrumbs[] = [
                'title' => __('Home', 'panda-hf'),
                'url' => esc_url(home_url()),
                'class' => 'panda-breadcrumbs-first',
            ];
        }

        if (!is_front_page()) {
            if (is_home()) {
                $breadcrumbs[] = [
                    'title' => get_the_title(get_option('page_for_posts')),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            } elseif (is_single()) {
                $category = get_the_category();
                if ($category) {
                    $breadcrumbs[] = [
                        'title' => $category[0]->name,
                        'url' => esc_url(get_category_link($category[0]->term_id)),
                        'class' => '',
                    ];
                }
                $breadcrumbs[] = [
                    'title' => get_the_title(),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            } elseif (is_page()) {
                $breadcrumbs[] = [
                    'title' => get_the_title(),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            }
        }

        echo '<ul class="panda-breadcrumbs">';
        
        foreach ($breadcrumbs as $index => $item) {
            echo '<li class="panda-breadcrumbs-item ' . esc_attr($item['class']) . '">';
            
            if ('yes' === $settings['show_home'] && 0 === $index) {
                echo '<span class="panda-breadcrumbs-home-icon">';
                Icons_Manager::render_icon($settings['home_icon'], ['aria-hidden' => 'true']);
                echo '</span>';
            }

            if ($item['url']) {
                echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>';
            } else {
                echo '<span>' . esc_html($item['title']) . '</span>';
            }
            
            if ($index < count($breadcrumbs) - 1) {
                echo '<span class="panda-breadcrumbs-separator">' . wp_kses_post($delimiter) . '</span>';
            }
            
            echo '</li>';
        }
        
        echo '</ul>';
    }
}
