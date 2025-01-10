<?php
namespace Panda\Header_Footer\WidgetsManager\Extensions\HeaderFooter;

if (!defined('ABSPATH')) {
    exit;
}

class Extension {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('elementor/element/after_section_end', [$this, 'register_controls'], 10, 2);
    }

    public function register_controls($element, $section_id) {
        if ('section_advanced' === $section_id) {
            $element->start_controls_section(
                'panda_header_footer_section',
                [
                    'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                    'label' => __('Header Footer Settings', 'panda-hf'),
                ]
            );

            $element->add_control(
                'panda_header_footer_enabled',
                [
                    'label' => __('Enable Header Footer', 'panda-hf'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Yes', 'panda-hf'),
                    'label_off' => __('No', 'panda-hf'),
                ]
            );

            $element->end_controls_section();
        }
    }
}

Extension::instance();
