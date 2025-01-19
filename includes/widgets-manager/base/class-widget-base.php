<?php

namespace Panda\Header_Footer\WidgetsManager;

use Elementor\Widget_Base;

class Widget_Base_Custom extends Widget_Base
{
    public function get_name()
    {
        return '';
    }

    public function get_title()
    {
        return '';
    }

    public function get_icon()
    {
        return '';
    }

    public function get_categories()
    {
        return [];
    }

    protected function get_widget_path()
    {
        $reflection = new \ReflectionClass($this);
        return dirname($reflection->getFileName());
    }

    protected function get_widget_url()
    {
        return PANDA_HF_URL . str_replace(PANDA_HF_PATH, '', $this->get_widget_path());
    }

    public function load_widget_assets()
    {

        wp_enqueue_style('elementor-icons');
        $css_file = $this->get_widget_path() . '/assets/css/style.css';
        if (file_exists($css_file)) {
            $style_handle = 'panda-widget-' . $this->get_name();
            if (!wp_style_is($style_handle, 'registered')) {
                wp_register_style(
                    $style_handle,
                    $this->get_widget_url() . '/assets/css/style.css',
                    [],
                    PANDA_HF_VERSION
                );
            }
            wp_enqueue_style($style_handle);
        }
        $js_file = $this->get_widget_path() . '/assets/js/script.js';
        if (file_exists($js_file)) {
            $script_handle = 'panda-widget-' . $this->get_name();
            if (!wp_script_is($script_handle, 'registered')) {
                wp_register_script(
                    $script_handle,
                    $this->get_widget_url() . '/assets/js/script.js',
                    ['jquery'],
                    PANDA_HF_VERSION,
                    true
                );
            }
            wp_enqueue_script($script_handle);
        }
    }
}
