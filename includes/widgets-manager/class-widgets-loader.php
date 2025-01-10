<?php

namespace Panda\Header_Footer\WidgetsManager;

if (!defined('ABSPATH')) {
    exit;
}

class Widgets_Loader
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function __construct()
    {
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_category'], 1);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function register_widget_category($elements_manager)
    {
        $elements_manager->add_category(
            'panda-hf',
            [
                'title' => __('Panda HF', 'panda-hf'),
                'icon' => 'fa fa-plug'
            ],
            -1
        );

        add_filter('elementor/document/config', function ($config, $post_id) {
            
            if (get_post_type($post_id) === 'panda_template') {
                if (!isset($config['panel']['categories_to_show'])) {
                    $config['panel']['categories_to_show'] = [];
                }
                array_unshift($config['panel']['categories_to_show'], 'panda-hf'); // Use array_unshift instead of array_push
            }
            return $config;
        }, 1, 2);
    }

    
    public function register_widgets($widgets_manager)
    {

        // Load base widget class from: includes/widgets-manager/base/class-widget-base.php
        require_once PANDA_HF_PATH . 'includes/widgets-manager/base/class-widget-base.php';

        // Load all widget files from: includes/widgets-manager/modules/{module-folder}/*.widget.php
        $widget_files = glob(PANDA_HF_PATH . 'includes/widgets-manager/modules/*/*.widget.php');
        foreach ($widget_files as $widget_file) {
            require_once $widget_file;
        }

        // Build modules array with proper namespacing
        // File naming convention: {name}.widget.php
        // Class naming convention: Modules\{ModuleDir}\Widgets\{ClassName}
        // Example: hello-world.widget.php -> Modules\ModuleDir\Widgets\Hello_World
        $modules = [];
        foreach ($widget_files as $widget_file) {
            $file_name = basename($widget_file, '.widget.php');
            $module_name = strtolower(str_replace('_', '-', $file_name));
            $class_name = str_replace('-', '_', ucwords($file_name, '-'));
            $module_dir = basename(dirname($widget_file));
            $modules[$module_name] = 'Modules\\' . $module_dir . '\\Widgets\\' . $class_name;
        }

        // Register each widget class with Elementor
        // Full namespace will be: Panda\Header_Footer\WidgetsManager\Modules\{ModuleDir}\Widgets\{ClassName}
        foreach ($modules as $module_name => $class_name) {
            $class = __NAMESPACE__ . '\\' . $class_name;
            error_log($class);

            if (class_exists($class)) {
                $widgets_manager->register(new $class());
            }
        }
    }
}
