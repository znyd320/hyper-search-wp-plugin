<?php

namespace Panda\Admin;

class Admin_Settings
{
    private $tabs = [];
    private $pending_fields = [];
    private $auto_register_tabs = false;
    private $notices = [];

    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('panda_add_settings_tab', [$this, 'add_tab']);
        add_action('panda_add_settings_field', [$this, 'add_field']);
        add_action('panda_add_tab_notice', [$this, 'add_tab_notice'], 10, 3);
        do_action('panda_settings_init');
    }

    public function add_notice($type, $message)
    {
        $this->notices[] = [
            'type' => $type, // 'error', 'warning', 'success', 'info'
            'message' => $message
        ];
    }

    public function add_tab_notice($tab_name, $type, $message)
    {
        if (!isset($this->tabs[$tab_name]['notices'])) {
            $this->tabs[$tab_name]['notices'] = [];
        }
        $this->tabs[$tab_name]['notices'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public function set_auto_register_tabs($value)
    {
        $this->auto_register_tabs = (bool) $value;
    }

    public function add_tab($tab)
    {
        if (isset($tab['name'], $tab['label'])) {
            if (!isset($tab['settings_fields'])) {
                $tab['settings_fields'] = [];
            }
            if (!isset($tab['notices'])) {
                $tab['notices'] = [];
            }
            $this->tabs[$tab['name']] = $tab;

            if (isset($this->pending_fields[$tab['name']])) {
                $this->tabs[$tab['name']]['settings_fields'] = array_merge(
                    $this->tabs[$tab['name']]['settings_fields'],
                    $this->pending_fields[$tab['name']]
                );
                unset($this->pending_fields[$tab['name']]);
            }
        }
    }

    public function add_field($field)
    {
        if (isset($field['tab_name'], $field['field_name'], $field['label'], $field['type'])) {
            if (!isset($field['description'])) {
                $field['description'] = '';
            }

            if (isset($this->tabs[$field['tab_name']])) {
                $this->tabs[$field['tab_name']]['settings_fields'][] = $field;
            } elseif ($this->auto_register_tabs) {
                $this->add_tab([
                    'name' => $field['tab_name'],
                    'label' => ucfirst(str_replace('_', ' ', $field['tab_name'])),
                    'settings_fields' => [$field]
                ]);
            } else {
                if (!isset($this->pending_fields[$field['tab_name']])) {
                    $this->pending_fields[$field['tab_name']] = [];
                }
                $this->pending_fields[$field['tab_name']][] = $field;
            }
        }
    }

    public function register_admin_menu()
    {
        global $panda_menu_created;
        if (empty($panda_menu_created)) {
            add_menu_page(
                __('Panda', 'panda-header-footer'),
                __('Panda', 'panda-header-footer'),
                'manage_options',
                'panda-dashboard',
                [$this, 'render_dashboard'],
                'dashicons-heart',
                30
            );

            if(!empty($this->tabs)) {
                add_submenu_page(
                    'panda-dashboard',
                    __('Settings', 'panda-header-footer'),
                    __('Settings', 'panda-header-footer'),
                    'manage_options',
                    'panda-settings',
                    [$this, 'render_settings_page']
                );
            }
        }
    }

    public function register_settings()
    {
        foreach ($this->tabs as $tab_name => $tab) {
            $group = "panda_settings_" . sanitize_key($tab_name);
            foreach ($tab['settings_fields'] as $field) {
                register_setting($group, sanitize_key($field['field_name']), [
                    'type' => $field['type'] === 'boolean' ? 'boolean' : 'string',
                    'sanitize_callback' => $this->get_sanitize_callback($field['type']),
                    'default' => $field['default'] ?? ''
                ]);
            }
        }
    }

    private function get_sanitize_callback($type)
    {
        switch ($type) {
            case 'boolean':
                return 'rest_sanitize_boolean';
            case 'textarea':
                return 'sanitize_textarea_field';
            case 'number':
                return 'absint';
            case 'select':
            case 'string':
            default:
                return 'sanitize_text_field';
        }
    }

    public function render_settings_page()
    {
?>
        <div class="wrap">
            <h1><?php esc_html_e('Panda Settings', 'panda-header-footer'); ?></h1>

            <?php
            // Display global notices
            foreach ($this->notices as $notice) : ?>
                <div class="notice notice-<?php echo esc_attr($notice['type']); ?> is-dismissible">
                    <p><?php echo esc_html($notice['message']); ?></p>
                </div>
            <?php endforeach; ?>

            <?php if (empty($this->tabs)) : ?>
                <p><?php esc_html_e('No settings tabs are registered.', 'panda-header-footer'); ?></p>
                <?php return; ?>
            <?php endif; ?>

            <div class="panda-settings-container">
                <div class="panda-settings-sidebar">
                    <?php
                    $current_tab = $_GET['tab'] ?? array_key_first($this->tabs);
                    foreach ($this->tabs as $tab_name => $tab) : ?>
                        <a href="?page=panda-settings&tab=<?php echo esc_attr($tab_name); ?>"
                            class="panda-tab-button <?php echo $current_tab === $tab_name ? 'active' : ''; ?>">
                            <?php echo esc_html($tab['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="panda-settings-content">
                    <?php
                    // Display tab-specific notices
                    if (isset($this->tabs[$current_tab]['notices'])) {
                        foreach ($this->tabs[$current_tab]['notices'] as $notice) : ?>
                            <div class="notice notice-<?php echo esc_attr($notice['type']); ?> is-dismissible">
                                <p><?php echo esc_html($notice['message']); ?></p>
                            </div>
                    <?php endforeach;
                    }
                    ?>
                    <form method="post" action="options.php" class="panda-settings-form">
                        <?php
                        if (isset($this->tabs[$current_tab])) {
                            settings_fields("panda_settings_" . sanitize_key($current_tab));
                            echo '<div class="panda-settings-section">';
                            foreach ($this->tabs[$current_tab]['settings_fields'] as $field) {
                                $this->render_field($field);
                            }
                            echo '</div>';
                            submit_button();
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <style>
            .panda-settings-container {
                display: flex;
                gap: 20px;
                margin-top: 20px;
            }

            .panda-settings-sidebar {
                flex: 0 0 200px;
                display: flex;
                flex-direction: column;
            }

            .panda-settings-content {
                flex: 1;
            }

            .panda-tab-button {
                display: block;
                padding: 10px 15px;
                text-decoration: none;
                color: #23282d;
                background: #fff;
                border: 1px solid #ccd0d4;
                border-bottom: none;
            }

            .panda-tab-button:last-child {
                border-bottom: 1px solid #ccd0d4;
            }

            .panda-tab-button.active {
                background: #2271b1;
                color: #fff;
                font-weight: 600;
            }

            .panda-settings-section {
                background: #fff;
                padding: 20px;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
            }

            .panda-settings-form .form-table th {
                padding: 20px 10px;
            }

            .panda-settings-form select {
                min-width: 200px;
            }

            .panda-settings-form textarea {
                width: 100%;
                min-height: 100px;
            }

            .field-description {
                color: #666;
                font-style: italic;
                margin-top: 5px;
            }
        </style>
    <?php
    }

    private function render_field($field)
    {
        $option = get_option($field['field_name'], $field['default'] ?? '');
    ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="<?php echo esc_attr($field['field_name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                <td>
                    <?php switch ($field['type']):
                        case 'boolean': ?>
                            <input type="checkbox" id="<?php echo esc_attr($field['field_name']); ?>"
                                name="<?php echo esc_attr($field['field_name']); ?>"
                                value="1" <?php checked(1, $option, true); ?> />
                        <?php break;
                        case 'textarea': ?>
                            <textarea id="<?php echo esc_attr($field['field_name']); ?>"
                                name="<?php echo esc_attr($field['field_name']); ?>"
                                class="large-text"><?php echo esc_textarea($option); ?></textarea>
                        <?php break;
                        case 'select': ?>
                            <select id="<?php echo esc_attr($field['field_name']); ?>"
                                name="<?php echo esc_attr($field['field_name']); ?>">
                                <?php foreach ($field['options'] as $value => $label): ?>
                                    <option value="<?php echo esc_attr($value); ?>"
                                        <?php selected($option, $value); ?>>
                                        <?php echo esc_html($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php break;
                        case 'number': ?>
                            <input type="number" id="<?php echo esc_attr($field['field_name']); ?>"
                                name="<?php echo esc_attr($field['field_name']); ?>"
                                value="<?php echo esc_attr($option); ?>" class="regular-text" />
                        <?php break;
                        default: ?>
                            <input type="text" id="<?php echo esc_attr($field['field_name']); ?>"
                                name="<?php echo esc_attr($field['field_name']); ?>"
                                value="<?php echo esc_attr($option); ?>" class="regular-text" />
                    <?php endswitch; ?>
                    <?php if (!empty($field['description'])) : ?>
                        <p class="field-description"><?php echo esc_html($field['description']); ?></p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
<?php
    }

    public function render_dashboard()
    {
        require_once PANDA_HF_PATH . 'universal/dashboard/panda.support.page.php';
    }
}
