<?php
namespace Panda\Header_Footer\WidgetsManager\Modules\Copyright\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Panda\Header_Footer\WidgetsManager\Widget_Base_Custom;

if (!defined('ABSPATH')) {
    exit;
}

class Copyright extends Widget_Base_Custom {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        add_action('elementor/frontend/after_enqueue_styles', [$this, 'load_widget_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_widget_assets']);
    }

    public function get_name() {
        return 'panda-copyright';
    }

    public function get_title() {
        return __('Copyright', 'panda-hf');
    }
    public function get_icon() {
        return 'eicon-text-area';
    }

    public function get_categories() {
        return ['panda-hf'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __('Copyright', 'panda-hf'),
            ]
        );

        $this->add_control(
            'shortcode',
            [
                'label'   => __('Copyright Text', 'panda-hf'),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Copyright © [phf_current_year] [phf_site_title] | Powered by [phf_site_title]', 'panda-hf'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label'       => __('Link', 'panda-hf'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'panda-hf'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'              => __('Alignment', 'panda-hf'),
                'type'               => Controls_Manager::CHOOSE,
                'options'            => [
                    'left'   => [
                        'title' => __('Left', 'panda-hf'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'panda-hf'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'panda-hf'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors'          => [
                    '{{WRAPPER}} .panda-copyright-wrapper' => 'text-align: {{VALUE}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Text Color', 'panda-hf'),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-copyright-wrapper a, {{WRAPPER}} .panda-copyright-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'caption_typography',
                'selector' => '{{WRAPPER}} .panda-copyright-wrapper, {{WRAPPER}} .panda-copyright-wrapper a',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $link     = isset($settings['link']['url']) ? $settings['link']['url'] : '';

        if (!empty($link)) {
            $this->add_link_attributes('link', $settings['link']);
        }

        $copy_right_shortcode = do_shortcode(shortcode_unautop($settings['shortcode'])); ?>
        <div class="panda-copyright-wrapper">
            <?php if (!empty($link)) { ?>
                <a <?php echo wp_kses_post($this->get_render_attribute_string('link')); ?>>
                    <span><?php echo wp_kses_post($copy_right_shortcode); ?></span>
                </a>
            <?php } else { ?>
                <span><?php echo wp_kses_post($copy_right_shortcode); ?></span>
            <?php } ?>
        </div>
        <?php
    }

    protected function content_template() {}
}
