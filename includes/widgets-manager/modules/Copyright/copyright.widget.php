<?php

namespace Panda\Header_Footer\WidgetsManager\Modules\Copyright\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Panda\Header_Footer\WidgetsManager\Widget_Base_Custom;

if (!defined('ABSPATH')) {
    exit;
}

class Copyright extends Widget_Base_Custom
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        add_action('elementor/frontend/after_enqueue_styles', [$this, 'load_widget_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_widget_assets']);
        wp_register_style('panda_hf-copyright-widget', false);
    }

    public function get_name()
    {
        return 'panda-copyright';
    }

    public function get_title()
    {
        return __('Copyright', 'panda-hf');
    }
    public function get_icon()
    {
        return 'eicon-text-area';
    }

    public function get_categories()
    {
        return ['panda-hf'];
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Copyright Content', 'panda-hf'),
            ]
        );

        $this->add_control(
            'copyright_layout',
            [
                'label' => __('Layout', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => __('Inline', 'panda-hf'),
                    'stacked' => __('Stacked', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'shortcode',
            [
                'label' => __('Copyright Text', 'panda-hf'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'default' => __('Copyright © [phf_current_year] [phf_site_title] | Powered by [phf_site_title]', 'panda-hf'),
                'description' => __('Available shortcodes: [phf_current_year], [phf_site_title], [phf_site_tagline]', 'panda-hf'),
            ]
        );

        $this->add_control(
            'show_social_icons',
            [
                'label' => __('Show Social Icons', 'panda-hf'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'separator' => 'before',
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'social_icon',
            [
                'label' => __('Icon', 'panda-hf'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $repeater->add_control(
            'social_link',
            [
                'label' => __('Link', 'panda-hf'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
            ]
        );

        $repeater->add_control(
            'custom_colors_switch',
            [
                'label' => __('Custom Colors', 'panda-hf'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'custom_icon_color',
            [
                'label' => __('Icon Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'custom_colors_switch' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'custom_icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'custom_colors_switch' => 'yes',
                ],
            ]
        );


        $this->add_control(
            'social_icons_list',
            [
                'label' => __('Social Icons', 'panda-hf'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'condition' => [
                    'show_social_icons' => 'yes',
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

        // Container Style
        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'selector' => '{{WRAPPER}} .panda-copyright-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .panda-copyright-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .panda-copyright-wrapper',
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .panda-copyright-wrapper, {{WRAPPER}} .panda-copyright-wrapper a',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        // Colors
        $this->start_controls_tabs('text_colors');

        $this->start_controls_tab(
            'colors_normal',
            [
                'label' => __('Normal', 'panda-hf'),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-wrapper a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'colors_hover',
            [
                'label' => __('Hover', 'panda-hf'),
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-wrapper a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Social Icons Style
        $this->start_controls_section(
            'section_social_style',
            [
                'label' => __('Social Icons', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_social_icons' => 'yes',
                ],
            ]
        );


        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-social-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .panda-copyright-social-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Add container size control
        $this->add_responsive_control(
            'icon_container_size',
            [
                'label' => __('Container Size', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 400,
                    ],
                ],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-social-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __('Spacing', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-social-icon:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('social_icons_colors');

        $this->start_controls_tab(
            'social_icons_normal',
            [
                'label' => __('Normal', 'panda-hf'),
            ]
        );

        $this->add_control(
            'icon_default_color',
            [
                'label' => __('Default Icon Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-social-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panda-copyright-social-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'social_icons_hover',
            [
                'label' => __('Hover', 'panda-hf'),
            ]
        );

        $this->add_control(
            'icon_default_hover_color',
            [
                'label' => __('Default Hover Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-social-icon:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panda-copyright-social-icon:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $copy_right_shortcode = do_shortcode(shortcode_unautop($settings['shortcode']));
?>
        <div class="panda-copyright-wrapper elementor-layout-<?php echo esc_attr($settings['copyright_layout']); ?>">
            <div class="panda-copyright-text">
                <?php echo wp_kses_post($copy_right_shortcode); ?>
            </div>

            <?php if ('yes' === $settings['show_social_icons'] && !empty($settings['social_icons_list'])) : ?>
                <div class="panda-copyright-social-icons">
                    <?php foreach ($settings['social_icons_list'] as $index => $item) :
                        $link_key = 'link_' . $index;
                        $this->add_link_attributes($link_key, $item['social_link']);

                        $icon_class = 'custom-social-icon-' . $index;

                        if ('yes' === $item['custom_colors_switch'] && (!empty($item['custom_icon_color']) || !empty($item['custom_icon_hover_color']))) {
                            $this->add_render_attribute('custom_style_' . $index, 'class', $icon_class);

                            echo '<style>';
                            if (!empty($item['custom_icon_color'])) {
                                echo '.' . $icon_class . ' i, .' . $icon_class . ' svg { color: ' . esc_attr($item['custom_icon_color']) . ' !important; fill: ' . esc_attr($item['custom_icon_color']) . ' !important; }';
                            }
                            if (!empty($item['custom_icon_hover_color'])) {
                                echo '.' . $icon_class . ':hover i, .' . $icon_class . ':hover svg { color: ' . esc_attr($item['custom_icon_hover_color']) . ' !important; fill: ' . esc_attr($item['custom_icon_hover_color']) . ' !important; }';
                            }
                            echo '</style>';
                        }
                    ?>
                        <a class="panda-copyright-social-icon <?php echo esc_attr($icon_class); ?>" <?php echo $this->get_render_attribute_string($link_key); ?>>
                            <?php \Elementor\Icons_Manager::render_icon($item['social_icon'], ['aria-hidden' => 'true']); ?>
                        </a>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>
        </div>
<?php
    }

    protected function content_template() {}
}
