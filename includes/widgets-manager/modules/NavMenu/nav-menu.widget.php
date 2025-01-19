<?php
namespace Panda\Header_Footer\WidgetsManager\Modules\NavMenu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Panda\Header_Footer\WidgetsManager\Widget_Base_Custom;

if (!defined('ABSPATH')) {
    exit;
}

class Nav_Menu extends Widget_Base_Custom {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_styles', [$this, 'load_widget_assets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'load_widget_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_widget_assets']);
    }

    public function get_name()
    {
        return 'panda-nav-menu';
    }

    public function get_title()
    {
        return __('Navigation Menu', 'panda-hf');
    }

    public function get_icon()
    {
        return 'eicon-nav-menu';
    }

    public function get_categories()
    {
        return ['panda-hf'];
    }

    private function get_available_menus() {
        $menus = wp_get_nav_menus();
        $options = [];
        foreach ($menus as $menu) {
            $options[$menu->term_id] = $menu->name;
        }
        return $options;
    }
    protected function register_controls() {
        // Menu Settings Section
        $this->start_controls_section(
            'section_menu',
            [
                'label' => __('Menu Settings', 'panda-hf'),
            ]
        );

        $this->add_menu_selection_control();
        $this->add_layout_controls();

        $this->end_controls_section();

        // Menu Items Style Section
        $this->start_controls_section(
            'section_style_main_menu',
            [
                'label' => __('Menu Items', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_spacing_controls();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'selector' => '{{WRAPPER}} .panda-nav-menu ul li a',
            ]
        );

        $this->start_controls_tabs('menu_item_colors');
        $this->add_normal_state_controls();
        $this->add_hover_state_controls();
        $this->add_active_state_controls();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // Submenu Style Section
        $this->start_controls_section(
            'section_style_dropdown',
            [
                'label' => __('Submenu', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dropdown_background',
            [
                'label' => __('Background Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu ul.sub-menu' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} .panda-nav-menu ul.sub-menu',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'selector' => '{{WRAPPER}} .panda-nav-menu ul.sub-menu',
            ]
        );

        $this->end_controls_section();

        // Mobile Menu Style Section
        $this->start_controls_section(
            'section_style_mobile',
            [
                'label' => __('Mobile Menu', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'toggle_color',
            [
                'label' => __('Toggle Button Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu-toggle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function add_menu_selection_control() {
        $menus = $this->get_available_menus();
        
        if (!empty($menus)) {
            $this->add_control(
                'menu_select',
                [
                    'label' => __('Select Menu', 'panda-hf'),
                    'type' => Controls_Manager::SELECT,
                    'options' => $menus,
                    'default' => array_keys($menus)[0],
                    'save_default' => true,
                    'separator' => 'after',
                    'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'panda-hf'), admin_url('nav-menus.php')),
                ]
            );
        } else {
            $this->add_control(
                'menu_select',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<strong>' . __('There are no menus in your site.', 'panda-hf') . '</strong><br>' .
                        sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'panda-hf'), admin_url('nav-menus.php?action=edit&menu=0')),
                    'separator' => 'after',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }
    }

    private function add_layout_controls() {
        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __('Horizontal', 'panda-hf'),
                    'vertical' => __('Vertical', 'panda-hf'),
                    'dropdown' => __('Dropdown', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'submenu_animation',
            [
                'label' => __('Submenu Animation', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'none' => __('None', 'panda-hf'),
                    'fade' => __('Fade', 'panda-hf'),
                    'slide' => __('Slide', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'mobile_breakpoint',
            [
                'label' => __('Mobile Breakpoint', 'panda-hf'),
                'type' => Controls_Manager::NUMBER,
                'default' => 768,
            ]
        );
    }

    private function add_spacing_controls() {
        $this->add_responsive_control(
            'menu_item_spacing',
            [
                'label' => __('Menu Item Spacing', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu-ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_row_spacing',
            [
                'label' => __('Row Spacing', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu-ul > li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .panda-nav-menu-ul > li:last-child' => 'margin-bottom: 0;',
                ],
                'condition' => [
                    'layout' => 'vertical',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_column_spacing',
            [
                'label' => __('Column Spacing', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu-ul > li' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .panda-nav-menu-ul > li:last-child' => 'margin-right: 0;',
                ],
                'condition' => [
                    'layout' => 'horizontal',
                ],
            ]
        );
    }

    private function add_normal_state_controls() {
        $this->start_controls_tab(
            'menu_item_normal',
            ['label' => __('Normal', 'panda-hf')]
        );

        $this->add_control(
            'menu_item_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu ul li a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_item_bg_color',
            [
                'label' => __('Background Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu ul li a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
    }

    private function add_hover_state_controls() {
        $this->start_controls_tab(
            'menu_item_hover',
            ['label' => __('Hover', 'panda-hf')]
        );

        $this->add_control(
            'menu_item_hover_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu ul li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_item_hover_bg_color',
            [
                'label' => __('Background Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu ul li a:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
    }

    private function add_active_state_controls() {
        $this->start_controls_tab(
            'menu_item_active',
            ['label' => __('Active', 'panda-hf')]
        );

        $this->add_control(
            'menu_item_active_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-nav-menu ul li.current-menu-item a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (!$settings['menu_select']) {
            return;
        }

        $menu_classes = [
            'panda-nav-menu',
            'layout-' . $settings['layout'],
            'submenu-' . $settings['submenu_animation']
        ];

        ?>
        <nav class="<?php echo esc_attr(implode(' ', $menu_classes)); ?>" 
             data-mobile-breakpoint="<?php echo esc_attr($settings['mobile_breakpoint']); ?>">
            <button class="panda-nav-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <?php
            wp_nav_menu([
                'menu' => $settings['menu_select'],
                'container' => false,
                'menu_class' => 'panda-nav-menu-ul',
                'fallback_cb' => function() {
                    echo __('Please select a menu', 'panda-hf');
                },
            ]);
            ?>
        </nav>
        <?php
    }

    protected function content_template() {
        ?>
        <# 
        var menuClasses = [
            'panda-nav-menu',
            'layout-' + settings.layout,
            'submenu-' + settings.submenu_animation
        ];
        #>
        <nav class="{{ menuClasses.join(' ') }}" data-mobile-breakpoint="{{ settings.mobile_breakpoint }}">
            <button class="panda-nav-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <# if ( settings.menu_select ) { #>
                <div class="elementor-widget-container">
                    <?php
                    $menu_id = '{{{ settings.menu_select }}}';
                    wp_nav_menu([
                        'menu' => $menu_id,
                        'container' => false,
                        'menu_class' => 'panda-nav-menu-ul',
                        'fallback_cb' => function() {
                            echo __('Please select a menu', 'panda-hf');
                        },
                    ]);
                    ?>
                </div>
            <# } else { #>
                <div class="elementor-widget-container">
                    <?php _e('Please select a menu', 'panda-hf'); ?>
                </div>
            <# } #>
        </nav>
        <?php
    }
}
