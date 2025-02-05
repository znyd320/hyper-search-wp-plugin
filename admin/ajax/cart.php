<?php
if (!defined('ABSPATH')) {
	exit();
}

class Cart_Ajax
{
	public function __construct()
	{

		add_action('wp_ajax_panda_update_cart_quantity', [$this, 'update_cart_quantity']);
		add_action('wp_ajax_nopriv_panda_update_cart_quantity', [$this, 'update_cart_quantity']);

		add_action('woocommerce_add_to_cart', [$this, 'after_cart_update']);
		add_action('woocommerce_remove_cart_item', [$this, 'after_cart_update']);
		add_action('woocommerce_cart_item_restored', [$this, 'after_cart_update']);

		add_filter('woocommerce_add_to_cart_fragments', [$this, 'get_refreshed_fragments']);
	}

	public function get_refreshed_fragments($fragments)
	{
		ob_start();
		$this->render_cart_items();
		$cart_content = ob_get_clean();

		$fragments['.panda-mini-cart-items div'] = $cart_content;
		$fragments['.panda-cart-count-number'] = sprintf('<span class="panda-cart-count-number">%s</span>', WC()->cart->get_cart_contents_count());
		$fragments['.panda-cart-total span'] = sprintf('<span>%s</span>', WC()->cart->get_cart_total());

		return $fragments;
	}


	private function get_cart_response_data()
	{
		ob_start();
		$this->render_cart_items();
		$cart_content = ob_get_clean();

		return [
			'cart_content' => $cart_content,
			'cart_count' => WC()->cart->get_cart_contents_count(),
			'cart_total' => WC()->cart->get_cart_total(),
			'fragments' => apply_filters('woocommerce_add_to_cart_fragments', [])
		];
	}

	private function render_cart_items() {
		$cart_items = WC()->cart->get_cart();

		if (empty($cart_items)) {
			printf('<div class="panda-mini-cart-empty">%s</div>', esc_html__('Your cart is empty', 'panda-hf'));
			return;
		}

		echo '<div>';
		foreach ($cart_items as $cart_item_key => $cart_item) {
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			if (!$_product || !$_product->exists() || $cart_item['quantity'] < 0) {
				continue;
			}
			printf(
				'<div class="panda-mini-cart-item" data-key="%s">
					<div class="item-thumbnail">%s</div>
					<div class="item-details">
						<h4 class="item-title">%s</h4>
						<div class="item-price">%s</div>
						<div class="item-quantity">
							<button class="quantity-btn minus">-</button>
							<span class="qty">%d</span>
							<button class="quantity-btn plus">+</button>
						</div>
					</div>
					<button class="remove-item" aria-label="%s">×</button>
				</div>',
				esc_attr($cart_item_key),
				$_product->get_image(),
				$_product->get_name(),
				WC()->cart->get_product_price($_product),
				$cart_item['quantity'],
				esc_attr__('Remove item', 'panda-hf')
			);
		}
		echo '</div>';
	}
	public function update_cart_quantity()
	{

		// Verify nonce with 'security' parameter
		if (!check_ajax_referer('panda_cart_nonce', 'security', false)) {
			wp_send_json_error([
				'message' => __('Security check failed', 'panda-hf')
			]);
			return;
		}

		if (!isset($_POST['key']) || !isset($_POST['quantity'])) {
			wp_send_json_error([
				'message' => __('Missing required parameters', 'panda-hf')
			]);
			return;
		}

		$cart_item_key = sanitize_text_field($_POST['key']);
		$quantity = (int) $_POST['quantity'];

		try {
			if ($quantity === 0) {
				$removed = WC()->cart->remove_cart_item($cart_item_key);
				if (!$removed) {
					throw new Exception(__('Failed to remove item', 'panda-hf'));
				}
			} else {
				$updated = WC()->cart->set_quantity($cart_item_key, $quantity);
				if ($updated === false) {
					throw new Exception(__('Failed to update quantity', 'panda-hf'));
				}
			}

			WC()->cart->calculate_totals();

			wp_send_json_success($this->get_cart_response_data());
		} catch (Exception $e) {
			wp_send_json_error([
				'message' => $e->getMessage()
			]);
		}
	}
	public function after_cart_update()
	{
		WC()->cart->calculate_totals();
	}
}

new Cart_Ajax();
