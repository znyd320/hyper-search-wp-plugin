<?php
namespace Panda\Header_Footer\WidgetsManager\Base;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Module_Base {
    
    private $is_common = false;

    public function __construct() {
        $this->register_widgets();
    }

    public static function is_enable() {
        return true;
    }

    abstract public function get_name();

    public function get_widgets() {
        return [];
    }

    public function register_widgets() {
        $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

        foreach ($this->get_widgets() as $widget) {
            $class_name = $this->get_reflection()->getNamespace() . '\Widgets\\' . $widget;
            $widgets_manager->register(new $class_name());
        }
    }

    final public function get_reflection() {
        return new \ReflectionClass($this);
    }
}
