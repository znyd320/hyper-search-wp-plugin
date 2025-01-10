<?php
namespace Panda\Header_Footer\WidgetsManager\Extensions;

class Base_Extension {
    protected function get_extension_path() {
        $reflection = new \ReflectionClass($this);
        return dirname($reflection->getFileName());
    }

    protected function get_extension_url() {
        return PANDA_HF_URL . 'includes/widgets-manager/extensions/' . basename($this->get_extension_path());
    }

    public function load_styles() {
        $css_file = $this->get_extension_path() . '/css/style.css';
        if (file_exists($css_file)) {
            $extension_name = basename($this->get_extension_path());
            wp_register_style(
                'panda-' . $extension_name,
                $this->get_extension_url() . '/css/style.css',
                [],
                PANDA_HF_VERSION
            );
            wp_enqueue_style('panda-' . $extension_name);
        }
    }
}
