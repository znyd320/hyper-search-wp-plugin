<?php

namespace Panda\Header_Footer\WidgetsManager\Modules\Breadcrumbs\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Panda\Header_Footer\WidgetsManager\Widget_Base_Custom;

if (!defined('ABSPATH')) {
    exit;
}

class Breadcrumbs extends Widget_Base_Custom
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_styles', [$this, 'load_widget_assets']);
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'load_widget_assets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'load_widget_assets']);
    }

    public function get_name()
    {
        return 'panda-breadcrumbs';
    }

    public function get_title()
    {
        return __('Breadcrumbs', 'panda-hf');
    }

    public function get_icon()
    {
        return 'eicon-navigation-horizontal';
    }

    public function get_categories()
    {
        return ['panda-hf'];
    }

    protected function register_controls()
    {
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
                'skin' => 'inline',
                'label_block' => false,
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

        $this->add_control(
            'enable_text_limit',
            [
                'label' => __('Enable Text Limit', 'panda-hf'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'panda-hf'),
                'label_off' => __('No', 'panda-hf'),
                'separator' => 'before',
            ]
        );



        $this->add_control(
            'truncate_style',
            [
                'label' => __('Text Display', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'tooltip',
                'options' => [
                    'tooltip' => __('Hover Tooltip', 'panda-hf'),
                    'ellipsis' => __('Ellipsis', 'panda-hf'),
                ],
                // 'selectors' => [
                //     '{{WRAPPER}} .panda-breadcrumbs-item.overflow.panda-slide-anim a' => 'animation: slideText 12s ease-in-out infinite;',
                //     '{{WRAPPER}} .panda-breadcrumbs-item.overflow.panda-slide-anim span' => 'animation: slideText 12s ease-in-out infinite;',
                // ],
                'condition' => [
                    'enable_text_limit' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'text_length',
            [
                'label' => __('Maximum Text Length', 'panda-hf'),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 500,
                'step' => 1,
                'default' => 30,
                'condition' => [
                    'enable_text_limit' => 'yes',
                    'truncate_style' => ['tooltip', 'ellipsis'],
                ],
                'description' => __('Set maximum number of characters. Text will be trimmed with ellipsis (...)', 'panda-hf'),
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
                'skin' => 'inline',
                'label_block' => false,
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

        // General Style Section
        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __('General Style', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_spacing',
            [
                'label' => __('Space Between Items', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
               'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs-separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumbs_typography',
                'selector' => '{{WRAPPER}} .panda-breadcrumbs',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .panda-breadcrumbs',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Separator Style Section
        $this->start_controls_section(
            'section_separator_style',
            [
                'label' => __('Separator Style', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __('Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs-separator' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panda-breadcrumbs-separator i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panda-breadcrumbs-separator svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_size',
            [
                'label' => __('Size', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs-separator' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .panda-breadcrumbs-separator i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .panda-breadcrumbs-separator svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        // Home icon style
        $this->start_controls_section(
            'section_home_icon_style',
            [
                'label' => __('Home Icon Style', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_home' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'home_icon_color',
            [
                'label' => __('Icon Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs-home-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panda-breadcrumbs-home-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'home_icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs-home-icon:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panda-breadcrumbs-home-icon:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'home_icon_size',
            [
                'label' => __('Size', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs-home-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .panda-breadcrumbs-home-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'home_icon_spacing',
            [
                'label' => __('Spacing', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-breadcrumbs-home-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function trim_text($text, $length)
    {
        if (strlen($text) > $length) {
            return substr($text, 0, $length);
        }
        return $text;
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        
        // Define breadcrumb defaults
        $delimiter = '';
        if ('text' === $settings['separator_type']) {
            $delimiter = $settings['separator_text'];
        } elseif ('icon' === $settings['separator_type']) {
            ob_start();
            Icons_Manager::render_icon($settings['separator_icon'], ['aria-hidden' => 'true']);
            $delimiter = ob_get_clean();
        }

        // Start the breadcrumbs array
        $breadcrumbs = [];

        if ('yes' === $settings['show_home']) {
            $breadcrumbs[] = [
                'title' => __('Home', 'panda-hf'),
                'url' => esc_url(home_url()),
                'class' => 'panda-breadcrumbs-first',
            ];
        }

        // Add current page details based on conditions
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
                    $parent_cats = get_ancestors($category[0]->term_id, 'category');
                    $parent_cats = array_reverse($parent_cats);

                    foreach ($parent_cats as $cat_id) {
                        $cat = get_category($cat_id);
                        $breadcrumbs[] = [
                            'title' => $cat->name,
                            'url' => esc_url(get_category_link($cat_id)),
                            'class' => '',
                        ];
                    }

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
                $parents = get_post_ancestors(get_the_ID());
                foreach (array_reverse($parents) as $parent) {
                    $breadcrumbs[] = [
                        'title' => get_the_title($parent),
                        'url' => esc_url(get_permalink($parent)),
                        'class' => '',
                    ];
                }
                $breadcrumbs[] = [
                    'title' => get_the_title(),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            } elseif (is_category()) {
                $breadcrumbs[] = [
                    'title' => single_cat_title('', false),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            } elseif (is_tag()) {
                $breadcrumbs[] = [
                    'title' => single_tag_title('', false),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            } elseif (is_author()) {
                $breadcrumbs[] = [
                    'title' => get_the_author(),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            } elseif (is_search()) {
                $breadcrumbs[] = [
                    'title' => __('Search results for:', 'panda-hf') . ' ' . get_search_query(),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            } elseif (is_404()) {
                $breadcrumbs[] = [
                    'title' => __('404 not found', 'panda-hf'),
                    'url' => '',
                    'class' => 'panda-breadcrumbs-last',
                ];
            }
        }

        // Build the breadcrumb output
        echo '<ul class="panda-breadcrumbs">';

        foreach ($breadcrumbs as $index => $breadcrumb) {
            echo '<li class="panda-breadcrumbs-item ' . esc_attr($breadcrumb['class']) . '">';

            if ('yes' === $settings['show_home'] && 0 === $index) {
                echo '<span class="panda-breadcrumbs-home-icon">';
                Icons_Manager::render_icon($settings['home_icon'], ['aria-hidden' => 'true']);
                echo '</span>';
            }

            $title = $breadcrumb['title'];
            $should_truncate = 'yes' === $settings['enable_text_limit'] 
                && !empty($settings['text_length']) 
                && strlen($title) > $settings['text_length'];

            if ($breadcrumb['url']) {
                if ($should_truncate) {
                    $truncated_title = substr($title, 0, $settings['text_length']) . '...';
                    
                    if ($settings['truncate_style'] === 'tooltip') {
                        printf(
                            '<a href="%s" title="%s"><span>%s</span></a>',
                            esc_url($breadcrumb['url']),
                            esc_attr($title),
                            esc_html($truncated_title)
                        );
                    } else { // ellipsis
                        printf(
                            '<a href="%s"><span>%s</span></a>',
                            esc_url($breadcrumb['url']),
                            esc_html($truncated_title)
                        );
                    }
                } else {
                    printf(
                        '<a href="%s"><span>%s</span></a>',
                        esc_url($breadcrumb['url']),
                        esc_html($title)
                    );
                }
            } else {
                if ($should_truncate) {
                    $truncated_title = substr($title, 0, $settings['text_length']) . '...';
                    
                    if ($settings['truncate_style'] === 'tooltip') {
                        printf(
                            '<span title="%s">%s</span>',
                            esc_attr($title),
                            esc_html($truncated_title)
                        );
                    } else { // ellipsis
                        echo '<span>' . esc_html($truncated_title) . '</span>';
                    }
                } else {
                    echo '<span>' . esc_html($title) . '</span>';
                }
            }

            if ($index < count($breadcrumbs) - 1) {
                echo '<span class="panda-breadcrumbs-separator">' . $delimiter . '</span>';
            }

            echo '</li>';
        }

        echo '</ul>';
    }
}
