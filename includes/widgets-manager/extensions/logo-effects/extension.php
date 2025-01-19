<?php
namespace Panda\Header_Footer\WidgetsManager\Extensions\LogoEffects;

use Panda\Header_Footer\WidgetsManager\Extensions\Base_Extension;

class Extension extends Base_Extension {
    private $supported_widgets = [
        'panda-site-logo',
        'panda-navigation',
        'panda-button'
    ];

    public function __construct() {
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'load_styles']);
        add_action('wp_enqueue_scripts', [$this, 'load_styles']);
        foreach($this->supported_widgets as $widget) {
            add_action("elementor/element/{$widget}/section_content/after_section_end", [$this, 'add_logo_effects'], 10, 2);
        }
    }

    public function add_logo_effects($element) {
        $element->start_controls_section(
            'section_logo_effects',
            [
                'label' => __('Logo Effects', 'panda-hf'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $element->add_control(
            'hover_animation',
            [
                'label' => __('Hover Animation', 'panda-hf'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'panda-hf'),
                    'grow' => __('Grow', 'panda-hf'),
                    'shrink' => __('Shrink', 'panda-hf'),
                    'pulse' => __('Pulse', 'panda-hf'),
                ],
                'default' => 'none',
            ]
        );

        $element->end_controls_section();
    }
}
new Extension();
