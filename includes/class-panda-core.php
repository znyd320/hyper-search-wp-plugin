<?php

namespace Panda\Header_Footer;

use Panda\Admin\Admin_Settings;
use Panda\Header_Footer\WidgetsManager\Widgets_Loader;
use Panda\Header_Footer\WidgetsManager\Extensions_Loader;
use Panda\Header_Footer\WidgetsManager\Modules_Manager;

class Core
{

    public $template;
    private static $instance = null;
    private $post_type;
    private $template_loader;

    private $theme_compatibility;

    /**
     * Instance of Elemenntor Frontend class.
     *
     * @var object \Elementor\Frontend()
     */
    private static $elementor_instance;

    public static function get_instance()
    {
        if (null === self::$instance) {
            require_once PANDA_HF_PATH . 'includes/panda-hf-functions.php';
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {

        $this->template = get_template();

        $is_elementor_callable = (defined('ELEMENTOR_VERSION') && is_callable('Elementor\Plugin::instance')) ? true : false;

        $required_elementor_version = '3.5.0';
        // $required_elementor_version = '3.27.0';

        $is_elementor_outdated = ($is_elementor_callable && (! version_compare(ELEMENTOR_VERSION, $required_elementor_version, '>='))) ? true : false;

        if ((! $is_elementor_callable) || $is_elementor_outdated) {
            $this->elementor_not_available($is_elementor_callable, $is_elementor_outdated);
        }

        if ($is_elementor_callable) {
            self::$elementor_instance = \Elementor\Plugin::instance();
            $this->load_dependencies();

            // Disable the Elementor top bar
            add_filter(
                'elementor/admin-top-bar/is-active',
                function ($is_active, $current_screen) {
                    if (strpos($current_screen->id, 'panda_template') !== false) {
                        return false;
                    }
                    return $is_active;
                },
                9,
                2
            );

            $is_theme_supported = true;

            if ('genesis' == $this->template) {
                require PANDA_HF_PATH . 'themes/genesis/class-hfe-genesis-compat.php';
            } elseif ('astra' == $this->template) {
                require PANDA_HF_PATH . 'themes/astra/class-hfe-astra-compat.php';
            } elseif ('bb-theme' == $this->template || 'beaver-builder-theme' == $this->template) {
                $this->template = 'beaver-builder-theme';
                require PANDA_HF_PATH . 'themes/bb-theme/class-hfe-bb-theme-compat.php';
            } elseif ('generatepress' == $this->template) {
                require PANDA_HF_PATH . 'themes/generatepress/class-hfe-generatepress-compat.php';
            } elseif ('oceanwp' == $this->template) {
                require PANDA_HF_PATH . 'themes/oceanwp/class-hfe-oceanwp-compat.php';
            } elseif ('storefront' == $this->template) {
                require PANDA_HF_PATH . 'themes/storefront/class-hfe-storefront-compat.php';
            } elseif ('hello-elementor' == $this->template) {
                require PANDA_HF_PATH . 'themes/hello-elementor/class-hfe-hello-elementor-compat.php';
            } else {
                $is_theme_supported = false;
                // error_log(message: 'Theme Support Status: ' . ($is_theme_supported ? 'true' : 'false') . ' | Template: ' . $this->template);
                add_action('panda_settings_init', [$this, 'add_theme_support_settings']);
                add_action('init', callback: [$this, 'setup_fallback_support']);
            }

            Widgets_Loader::instance();
            Extensions_Loader::instance();
            new Modules_Manager();

            $this->init_hooks();
            add_filter('elementor/editor/post_type/support', [$this, 'add_elementor_support']);
        }
    }

    public function elementor_not_available($is_elementor_callable, $is_elementor_outdated)
    {

        if ((! did_action('elementor/loaded')) || (! $is_elementor_callable)) {
            add_action('admin_notices', [$this, 'elementor_not_installed_activated']);
            add_action('network_admin_notices', [$this, 'elementor_not_installed_activated']);
            return;
        }

        if ($is_elementor_outdated) {
            add_action('admin_notices', [$this, 'elementor_outdated']);
            add_action('network_admin_notices', [$this, 'elementor_outdated']);
            return;
        }
    }

    /**
     * Prints the admin notics when Elementor is not installed or activated.
     *
     * @return void
     */
    public function elementor_not_installed_activated()
    {

        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }

        if (! did_action('elementor/loaded')) {
            // Check user capability.
            if (! (current_user_can('activate_plugins') && current_user_can('install_plugins'))) {
                return;
            }

            /* TO DO */
            $class = 'notice notice-error';
            /* translators: %s: html tags */
            $message = sprintf(__('The %1$sPanda Header Footer Builder%2$s plugin requires %1$sElementor%2$s plugin installed & activated.', 'panda-hf'), '<strong>', '</strong>');

            $plugin = 'elementor/elementor.php';

            if (_is_elementor_installed()) {

                $action_url   = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
                $button_label = __('Activate Elementor', 'panda-hf');
            } else {

                $action_url   = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
                $button_label = __('Install Elementor', 'panda-hf');
            }

            $button = '<p><a href="' . esc_url($action_url) . '" class="button-primary">' . esc_html($button_label) . '</a></p><p></p>';

            printf('<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr($class), wp_kses_post($message), wp_kses_post($button));
        }
    }



    /**
     * Prints the admin notics when Elementor version is outdated.
     *
     * @return void
     */
    public function elementor_outdated()
    {

        // Check user capability.
        if (! (current_user_can('activate_plugins') && current_user_can('install_plugins'))) {
            return;
        }

        /* TO DO */
        $class = 'notice notice-error';
        /* translators: %s: html tags */
        $message = sprintf(__('The %1$sPanda Header Footer Builder%2$s plugin has stopped working because you are using an older version of %1$sElementor%2$s plugin.', 'panda-hf'), '<strong>', '</strong>');

        $plugin = 'elementor/elementor.php';

        if (file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php')) {

            $action_url   = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&amp;plugin=') . $plugin . '&amp;', 'upgrade-plugin_' . $plugin);
            $button_label = __('Update Elementor', 'panda-hf');
        } else {

            $action_url   = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
            $button_label = __('Install Elementor', 'panda-hf');
        }

        $button = '<p><a href="' . esc_url($action_url) . '" class="button-primary">' . esc_html($button_label) . '</a></p><p></p>';

        printf('<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr($class), wp_kses_post($message), wp_kses_post($button));
    }


    public function add_elementor_support($supported_types)
    {
        $supported_types[] = 'panda_template';
        return $supported_types;
    }

    private function load_dependencies()
    {
        require_once PANDA_HF_PATH . 'universal/settings/class-panda-settings.php';
        require_once PANDA_HF_PATH . 'includes/class-panda-post-type.php';
        require_once PANDA_HF_PATH . 'includes/class-panda-template-loader.php';
        require_once PANDA_HF_PATH . 'admin/class-panda-phf-admin.php';
        require_once PANDA_HF_PATH . 'includes/class-panda-theme-compatibility.php';

        // Widget System
        require_once PANDA_HF_PATH . 'includes/widgets-manager/base/module-base.php';
        require_once PANDA_HF_PATH . 'includes/widgets-manager/base/widgets-config.php';
        require_once PANDA_HF_PATH . 'includes/widgets-manager/class-widgets-loader.php';
        require_once PANDA_HF_PATH . 'includes/widgets-manager/class-extensions-loader.php';
        require_once PANDA_HF_PATH . 'includes/widgets-manager/modules-manager.php';
    }


    public function add_theme_support_settings()
    {

        do_action('panda_add_settings_tab', [
            'name' => 'header_footer',
            'label' => 'Header Footer',
        ]);

        do_action('panda_add_settings_field', [
            'tab_name' => 'header_footer',
            'field_name' => 'theme_support',
            'label' => 'Theme Support',
            'type' => 'select',
            'options' => [
                '1' => 'Default Method',
                '2' => 'Global Method'
            ],
            'description' => 'Try the default method first. If it doesn\'t work, use the global method. Contact theme developer if both methods fail.',
            'default' => '1'
        ]);

        do_action('panda_add_tab_notice', 'header_footer', 'warning', 'Theme is not supported!');
    }


    public function add_svg_mime_support($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    // Add to existing Core class
    public function check_svg_filetype($data, $file, $filename, $mimes)
    {
        if (!$data['type']) {
            $wp_filetype = wp_check_filetype($filename, $mimes);
            $ext = $wp_filetype['ext'];
            $type = $wp_filetype['type'];
            $proper_filename = $filename;
            if ($ext === 'svg') {
                $data['type'] = 'image/svg+xml';
                $data['ext'] = 'svg';
                $data['proper_filename'] = $proper_filename;
            }
        }
        return $data;
    }


    /**
     * Add support for theme if the current theme does add support for 'panda_header_footer'
     *
     * @since  1.6.1
     * @return void
     */
    public function setup_fallback_support()
    {
        if (! current_theme_supports('panda_header_footer')) {
            $hfe_compatibility_option = get_option('theme_support', '1');

            if ('1' === $hfe_compatibility_option) {
                if (! class_exists('PHF_Default_Compat')) {
                    require_once PANDA_HF_PATH . 'themes/default/class-hfe-default-compat.php';
                }
            } elseif ('2' === $hfe_compatibility_option) {
                require PANDA_HF_PATH . 'themes/default/class-global-theme-compatibility.php';
            }
        }
    }


    private function init_hooks()
    {
        Admin_Settings::get_instance();
        $this->post_type = Post_Type::get_instance();
        $this->template_loader = Template_Loader::get_instance();
        $this->theme_compatibility = Theme_Compatibility::get_instance();
        Admin\Admin::get_instance();
        add_action('init', [$this, 'init']);
        add_filter('upload_mimes', [$this, 'add_svg_mime_support']);
        add_filter('wp_check_filetype_and_ext', [$this, 'check_svg_filetype'], 10, 4);
    }

    public function init()
    {
        $this->post_type->register_post_type();
        do_action('panda_hf_init');
    }
}
