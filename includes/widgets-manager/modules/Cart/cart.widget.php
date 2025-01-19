<?php

namespace Panda\Header_Footer\WidgetsManager\Modules\Cart\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Panda\Header_Footer\WidgetsManager\Widget_Base_Custom;

if (!defined('ABSPATH')) {
    exit;
}

class Cart extends Widget_Base_Custom
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        // Register widget assets
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'load_widget_assets']);
        add_action('elementor/preview/enqueue_styles', [$this, 'load_widget_assets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'load_widget_assets']);

        // Add AJAX handlers if needed
        add_action('wp_ajax_update_cart_count', [$this, 'update_cart_count']);
        add_action('wp_ajax_nopriv_update_cart_count', [$this, 'update_cart_count']);
    }

    public function get_name()
    {
        return 'panda-cart';
    }

    public function get_title()
    {
        return __('Shopping Cart', 'panda-hf');
    }

    public function get_icon()
    {
        return 'eicon-cart';
    }

    public function get_categories()
    {
        return ['panda-hf'];
    }

    public function get_script_depends()
    {
        return ['panda-cart-widget'];
    }

    public function get_style_depends()
    {
        return ['panda-cart-widget-style'];
    }

    public function load_widget_assets()
    {
        // Enqueue the script
        wp_enqueue_script(
            'panda-cart-widget',
            plugins_url('/assets/js/script.js', __FILE__),
            ['jquery', 'wc-cart-fragments'],
            '1.0.0',
            true
        );

        // Verify nonce creation
        $cart_nonce = wp_create_nonce('panda_cart_nonce');
        error_log('Generated cart nonce: ' . $cart_nonce);

        // Localize the script with new data
        wp_localize_script( 
            'panda-cart-widget',
            'pandaCart',
            [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'cart_nonce' => $cart_nonce,
                'update_cart_nonce' => wp_create_nonce('update_cart_count'),
                'i18n' => [
                    'errorMessage' => __('Something went wrong. Please try again.', 'panda-hf'),
                    'cartEmpty' => __('Your cart is empty', 'panda-hf'),
                    'removeItem' => __('Remove this item', 'panda-hf'),
                    'cartUpdating' => __('Updating cart...', 'panda-hf')
                ],
                'debug' => WP_DEBUG
            ]
        );

        // Enqueue the style
        wp_enqueue_style(
            'panda-cart-widget-style',
            plugins_url('/assets/css/style.css', __FILE__),
            [],
            '1.0.0'
        );
    }

    protected function register_controls()
    {
        // First, check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            $this->start_controls_section(
                'section_notice',
                [
                    'label' => __('Notice', 'panda-hf'),
                ]
            );

            $this->add_control(
                'wc_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate WooCommerce first.', 'panda-hf'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                ]
            );

            $this->end_controls_section();
            return;
        }

        // Cart Icon Section
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'panda-hf'),
            ]
        );

        $this->add_control(
            'cart_icon',
            [
                'label' => __('Icon', 'panda-hf'),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-shopping-cart',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'shopping-cart',
                        'shopping-bag',
                        'shopping-basket',
                        'cart-shopping',
                        'cart-plus',
                        'basket-shopping',
                    ],
                    'fa-regular' => [
                        'shopping-cart',
                        'shopping-bag',
                        'shopping-basket',
                    ],
                ],
            ]
        );

        $this->add_control(
            'cart_layout',
            [
                'label' => __('Layout Style', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => [
                    'style1' => __('Icon Left', 'panda-hf'),
                    'style2' => __('Icon Right', 'panda-hf'),
                    'style3' => __('Stacked', 'panda-hf'),
                    'style4' => __('Minimal', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'counter_position',
            [
                'label' => __('Counter Position', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top-right',
                'options' => [
                    'top-right' => __('Top Right', 'panda-hf'),
                    'top-left' => __('Top Left', 'panda-hf'),
                    'bottom-right' => __('Bottom Right', 'panda-hf'),
                    'bottom-left' => __('Bottom Left', 'panda-hf'),
                    'inline' => __('Inline', 'panda-hf'),
                ],
            ]
        );

        $this->add_responsive_control(
            'show_cart_count',
            [
                'label' => __('Show Item Count', 'panda-hf'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_default' => 'yes',
                'devices' => ['desktop', 'tablet', 'mobile'],
            ]
        );

        $this->add_responsive_control(
            'show_cart_total',
            [
                'label' => __('Show Total', 'panda-hf'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_default' => 'no',
                'devices' => ['desktop', 'tablet', 'mobile'],
            ]
        );

        $this->add_control(
            'show_subtotal_label',
            [
                'label' => __('Show Subtotal Label', 'panda-hf'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => [
                    'show_cart_total' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Mini Cart Section with enhanced options
        $this->start_controls_section(
            'section_mini_cart',
            [
                'label' => __('Mini Cart', 'panda-hf'),
            ]
        );

        $this->add_control(
            'mini_cart_type',
            [
                'label' => __('Display Type', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'dropdown',
                'options' => [
                    'dropdown' => __('Dropdown', 'panda-hf'),
                    'side_panel' => __('Side Panel', 'panda-hf'),
                ],
            ]
        );

        $this->add_control(
            'side_panel_position',
            [
                'label' => __('Panel Position', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'right' => __('Right', 'panda-hf'),
                    'left' => __('Left', 'panda-hf'),
                ],
                'condition' => [
                    'mini_cart_type' => 'side_panel',
                ],
            ]
        );

        $this->add_control(
            'close_button_type',
            [
                'label' => __('Close Button Style', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'circle',
                'options' => [
                    'circle' => __('Circle', 'panda-hf'),
                    'square' => __('Square', 'panda-hf'),
                    'minimal' => __('Minimal', 'panda-hf'),
                ],
                'condition' => [
                    'mini_cart_type' => 'side_panel',
                ],
            ]
        );

        $this->end_controls_section();


        // Icon Style Tab
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => __('Icon', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('icon_style_tabs');

        // Normal Tab
        $this->start_controls_tab(
            'icon_style_normal',
            ['label' => __('Normal', 'panda-hf')]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-icon-wrapper i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panda-cart-icon-wrapper svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .panda-cart-icon-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .panda-cart-icon-wrapper',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __('Border Radius', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-icon-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} .panda-cart-icon-wrapper',
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __('Padding', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Size', 'panda-hf'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .panda-cart-icon-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'icon_style_hover',
            ['label' => __('Hover', 'panda-hf')]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-icon-wrapper:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panda-cart-icon-wrapper:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_hover_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .panda-cart-icon-wrapper:hover',
            ]
        );

        $this->add_control(
            'icon_hover_border_color',
            [
                'label' => __('Border Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-icon-wrapper:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_border_border!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_hover_box_shadow',
                'selector' => '{{WRAPPER}} .panda-cart-icon-wrapper:hover',
            ]
        );

        $this->add_control(
            'icon_hover_animation',
            [
                'label' => __('Hover Animation', 'panda-hf'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Counter Style Section
        $this->start_controls_section(
            'section_counter_style',
            [
                'label' => __('Counter', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_cart_count' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('counter_style_tabs');

        $this->start_controls_tab(
            'counter_style_normal',
            ['label' => __('Normal', 'panda-hf')]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'counter_typography',
                'selector' => '{{WRAPPER}} .panda-cart-count',
            ]
        );

        $this->add_control(
            'counter_text_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'counter_background_color',
            [
                'label' => __('Background Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-count' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'counter_border',
                'selector' => '{{WRAPPER}} .panda-cart-count',
            ]
        );

        $this->add_control(
            'counter_border_radius',
            [
                'label' => __('Border Radius', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_box_shadow',
                'selector' => '{{WRAPPER}} .panda-cart-count',
            ]
        );

        $this->add_responsive_control(
            'counter_padding',
            [
                'label' => __('Padding', 'panda-hf'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'counter_style_hover',
            ['label' => __('Hover', 'panda-hf')]
        );

        // Add hover state controls for counter
        $this->add_control(
            'counter_hover_text_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-count:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'counter_hover_background_color',
            [
                'label' => __('Background Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-count:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Counter Animation
        $this->add_control(
            'counter_animation_heading',
            [
                'label' => __('Animation', 'panda-hf'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'counter_animation',
            [
                'label' => __('Animation Type', 'panda-hf'),
                'type' => Controls_Manager::SELECT,
                'default' => 'scale',
                'options' => [
                    'none' => __('None', 'panda-hf'),
                    'scale' => __('Scale', 'panda-hf'),
                    'bounce' => __('Bounce', 'panda-hf'),
                    'fade' => __('Fade', 'panda-hf'),
                ],
                'prefix_class' => 'panda-counter-animation-',
            ]
        );

        $this->end_controls_section();

        // Amount Style Section
        $this->start_controls_section(
            'section_amount_style',
            [
                'label' => __('Amount', 'panda-hf'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_cart_total' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'amount_typography',
                'selector' => '{{WRAPPER}} .panda-cart-total',
            ]
        );

        $this->start_controls_tabs('amount_style_tabs');

        $this->start_controls_tab(
            'amount_style_normal',
            ['label' => __('Normal', 'panda-hf')]
        );

        $this->add_control(
            'amount_text_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-total' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'amount_style_hover',
            ['label' => __('Hover', 'panda-hf')]
        );

        $this->add_control(
            'amount_hover_text_color',
            [
                'label' => __('Text Color', 'panda-hf'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panda-cart-total:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        if (!class_exists('WooCommerce')) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">';
                echo __('WooCommerce is not installed/activated. Please install and activate WooCommerce first.', 'panda-hf');
                echo '</div>';
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        // Set default values if not set
        $cart_layout = $settings['cart_layout'] ?? 'style1';
        $counter_position = $settings['counter_position'] ?? 'top-right';
        $mini_cart_type = $settings['mini_cart_type'] ?? 'dropdown';
        $panel_position = $settings['side_panel_position'] ?? 'right';
        $close_button_type = $settings['close_button_type'] ?? 'circle';

        // Properly handle responsive controls with defaults
        $show_cart_total = isset($settings['show_cart_total']) ? $settings['show_cart_total'] : 'yes';
        $show_cart_count = isset($settings['show_cart_count']) ? $settings['show_cart_count'] : 'yes';
        $show_cart_total_mobile = isset($settings['show_cart_total_mobile']) ? $settings['show_cart_total_mobile'] : 'no';
        $show_cart_count_mobile = isset($settings['show_cart_count_mobile']) ? $settings['show_cart_count_mobile'] : 'yes';

        // Build data attributes
        $wrapper_data = [
            'data-layout' => $cart_layout,
            'data-hide-total-mobile' => $show_cart_total_mobile !== 'yes' ? 'true' : 'false',
            'data-hide-count-mobile' => $show_cart_count_mobile !== 'yes' ? 'true' : 'false',
        ];

        $wrapper_data_string = implode(' ', array_map(
            function ($key, $value) {
                return $key . '="' . esc_attr($value) . '"';
            },
            array_keys($wrapper_data),
            $wrapper_data
        ));
?>
        <div class="panda-cart-wrapper" <?php echo $wrapper_data_string; ?>>
            <div class="panda-cart-icon-wrapper">
                <span class="elementor-icon">
                    <?php
                    if (!empty($settings['cart_icon']['value'])) {
                        Icons_Manager::render_icon($settings['cart_icon'], [
                            'aria-hidden' => 'true',
                            'class' => 'panda-cart-icon'
                        ]);
                    } else {
                        echo '<i class="fas fa-shopping-cart panda-cart-icon" aria-hidden="true"></i>';
                    }
                    ?>
                </span>

                <?php if ('yes' === $settings['show_cart_count']) : ?>
                    <span class="panda-cart-count" data-position="<?php echo esc_attr($counter_position); ?>">
                        <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : '0'; ?>
                    </span>
                <?php endif; ?>

                <?php if ('yes' === $settings['show_cart_total']) : ?>
                    <span class="panda-cart-total">
                        <?php if ('yes' === $settings['show_subtotal_label']) : ?>
                            <span class="panda-cart-subtotal-label"><?php echo __('Subtotal:', 'panda-hf'); ?></span>
                        <?php endif; ?>
                        <?php echo WC()->cart ? WC()->cart->get_cart_total() : wc_price(0); ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ('dropdown' === $mini_cart_type) : ?>
                <div class="panda-mini-cart-dropdown">
                    <div class="panda-mini-cart-content">
                        <?php $this->render_mini_cart_content(); ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="panda-mini-cart-side-panel" data-position="<?php echo esc_attr($panel_position); ?>">
                    <div class="panda-mini-cart-header">
                        <h3><?php echo __('Shopping Cart', 'panda-hf'); ?></h3>
                        <button class="panda-mini-cart-close" data-style="<?php echo esc_attr($close_button_type); ?>">
                            <span class="screen-reader-text"><?php echo __('Close cart', 'panda-hf'); ?></span>
                            <i class="eicon-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="panda-mini-cart-content">
                        <?php $this->render_mini_cart_content(); ?>
                    </div>
                </div>
                <div class="panda-cart-overlay"></div>
            <?php endif; ?>
        </div>
    <?php
    }

    protected function render_mini_cart_content()
    {
        if (!WC()->cart) {
            return;
        }

        $cart_items = WC()->cart->get_cart();

        if (empty($cart_items)) {
            echo '<div class="panda-mini-cart-empty">';
            echo __('Your cart is empty', 'panda-hf');
            echo '</div>';
            return;
        }
    ?>
        <div class="panda-mini-cart-items">
            <?php foreach ($cart_items as $cart_item_key => $cart_item) :
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                if (!$_product || !$_product->exists() || $cart_item['quantity'] < 0) {
                    continue;
                }
            ?>
                <div class="panda-mini-cart-item" data-key="<?php echo esc_attr($cart_item_key); ?>">
                    <div class="item-thumbnail">
                        <?php echo $_product->get_image(); ?>
                    </div>
                    <div class="item-details">
                        <h4 class="item-title"><?php echo $_product->get_name(); ?></h4>
                        <div class="item-price">
                            <?php echo WC()->cart->get_product_price($_product); ?>
                        </div>
                        <div class="item-quantity">
                            <button class="quantity-btn minus">-</button>
                            <input type="number"
                                class="qty"
                                value="<?php echo $cart_item['quantity']; ?>"
                                min="0"
                                max="<?php echo $_product->get_max_purchase_quantity(); ?>"
                                step="1">
                            <button class="quantity-btn plus">+</button>
                        </div>
                    </div>
                    <button class="remove-item" aria-label="<?php esc_attr_e('Remove item', 'panda-hf'); ?>">×</button>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="panda-mini-cart-footer">
            <div class="cart-subtotal">
                <span class="label"><?php _e('Subtotal:', 'panda-hf'); ?></span>
                <span class="amount"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            </div>
            <div class="cart-buttons">
                <a href="<?php echo wc_get_cart_url(); ?>" class="button view-cart">
                    <?php _e('View Cart', 'panda-hf'); ?>
                </a>
                <a href="<?php echo wc_get_checkout_url(); ?>" class="button checkout">
                    <?php _e('Checkout', 'panda-hf'); ?>
                </a>
            </div>
        </div>
<?php
    }

    public function update_cart_count()
    {
        check_ajax_referer('update_cart_count', 'nonce');

        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        wp_send_json_success([
            'count' => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
            'total' => WC()->cart ? WC()->cart->get_cart_total() : wc_price(0),
            'cart_content' => $mini_cart
        ]);
    }
}
