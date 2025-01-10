<?php
namespace Panda\Header_Footer\WidgetsManager;

if (!defined('ABSPATH')) {
    exit;
}

class Modules_Manager {
    private $_modules = [];

    public function __construct() {
        $this->require_files();
        $this->register_modules();
    }

    public function require_files() {
        require_once PANDA_HF_PATH . 'includes/widgets-manager/base/module-base.php';
    }

    public function register_modules() {
        $modules = [
            'site-logo',
            'navigation'
        ];

        foreach ($modules as $module_name) {
            $class_name = str_replace('-', ' ', $module_name);
            $class_name = str_replace(' ', '', ucwords($class_name));
            $class_name = __NAMESPACE__ . '\Modules\\' . $class_name . '\Module';

            if (class_exists($class_name)) {
                $this->_modules[$module_name] = $class_name::instance();
            }
        }
    }

    public function get_modules($module_name = null) {
        if ($module_name) {
            return isset($this->_modules[$module_name]) ? $this->_modules[$module_name] : null;
        }
        return $this->_modules;
    }
}
