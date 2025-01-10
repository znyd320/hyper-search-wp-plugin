<?php
namespace Panda\Header_Footer\WidgetsManager;

if (!defined('ABSPATH')) {
    exit;
}

class Extensions_Loader {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $this->include_extensions_files();
    }

    public static function get_extensions_list() {
        return [
            'header-footer' => 'header-footer',
            'logo-effects' => 'logo-effects',
        ];
    }

    public function include_extensions_files() {
        $extensions_list = $this->get_extensions_list();
        require_once PANDA_HF_PATH . 'includes/widgets-manager/extensions/class-base-extension.php';
        foreach ($extensions_list as $extension) {
            require_once PANDA_HF_PATH . 'includes/widgets-manager/extensions/' . $extension . '/extension.php';
        }
    }
}
