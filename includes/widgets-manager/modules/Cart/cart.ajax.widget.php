<?php
if (! defined('ABSPATH')) {
	exit();
}

/**
 * Helper class for the Cart Ajax.
 *
 * @since 1.2.0
 */
class Cart_Ajax
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		// Register AJAX handlers
		add_action('wp_ajax_update_cart_count', [$this, 'update_cart_count']);
		add_action('wp_ajax_nopriv_update_cart_count', [$this, 'update_cart_count']);
		
		add_action('wp_ajax_panda_update_cart_quantity', [$this, 'update_cart_quantity']);
		add_action('wp_ajax_nopriv_panda_update_cart_quantity', [$this, 'update_cart_quantity']);
		
		add_action('wp_ajax_panda_remove_cart_item', [$this, 'remove_cart_item']);
		add_action('wp_ajax_nopriv_panda_remove_cart_item', [$this, 'remove_cart_item']);
	}

	/**
	 * Get cart response data with error handling
	 * 
	 * @return array
	 */
	private function get_cart_response_data() {
		try {
			ob_start();
			woocommerce_mini_cart();
			$mini_cart = ob_get_clean();
			
			if (!WC()->cart) {
				throw new Exception('Cart not initialized');
			}

			return [
				'cart_content' => $mini_cart,
				'cart_count' => WC()->cart->get_cart_contents_count(),
				'cart_total' => WC()->cart->get_cart_total(),
				'fragments' => apply_filters('woocommerce_add_to_cart_fragments', [])
			];
		} catch (Exception $e) {
			error_log('Error getting cart response data: ' . $e->getMessage());
			return [
				'cart_content' => __('Error loading cart content', 'panda-hf'),
				'cart_count' => 0,
				'cart_total' => wc_price(0),
				'error' => $e->getMessage()
			];
		}
	}

	/**
	 * Update cart count via AJAX
	 */
	public function update_cart_count() {
		check_ajax_referer('update_cart_count', 'nonce');
		
		wp_send_json_success($this->get_cart_response_data());
	}

	/**
	 * Update cart item quantity via AJAX
	 */
	public function update_cart_quantity() {
		// Debug logging
		error_log('Received update_cart_quantity request: ' . print_r($_POST, true));
		
		// Verify nonce with detailed error
		if (!check_ajax_referer('panda_cart_nonce', 'nonce', false)) {
			error_log('Nonce verification failed for cart update');
			wp_send_json_error([
				'message' => __('Security check failed', 'panda-hf'),
				'debug' => 'Nonce verification failed'
			]);
			return;
		}
		
		// Validate input data
		$cart_item_key = isset($_POST['key']) ? sanitize_text_field($_POST['key']) : '';
		$quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;
		
		if (empty($cart_item_key)) {
			wp_send_json_error([
				'message' => __('Invalid cart item key', 'panda-hf'),
				'debug' => 'Empty cart item key'
			]);
			return;
		}
		
		// Check if WooCommerce cart is available
		if (!function_exists('WC') || !WC()->cart) {
			wp_send_json_error([
				'message' => __('Cart is not available', 'panda-hf'),
				'debug' => 'WooCommerce cart not initialized'
			]);
			return;
		}
		
		try {
			// Get cart item first to verify it exists
			$cart_item = WC()->cart->get_cart_item($cart_item_key);
			if (!$cart_item) {
				throw new Exception(__('Cart item not found', 'panda-hf'));
			}
			
			if ($quantity === 0) {
				$result = WC()->cart->remove_cart_item($cart_item_key);
				if (!$result) {
					throw new Exception(__('Failed to remove item from cart', 'panda-hf'));
				}
			} else {
				// Validate quantity against stock
				$_product = $cart_item['data'];
				if ($_product->managing_stock() && !$_product->backorders_allowed()) {
					$stock = $_product->get_stock_quantity();
					if ($quantity > $stock) {
						throw new Exception(sprintf(
							__('Sorry, we do not have enough "%s" in stock (%s available)', 'panda-hf'),
							$_product->get_name(),
							$stock
						));
					}
				}
				
				$result = WC()->cart->set_quantity($cart_item_key, $quantity);
				if ($result === false) {
					throw new Exception(__('Failed to update cart quantity', 'panda-hf'));
				}
			}
			
			WC()->cart->calculate_totals();
			
			// Get updated cart data
			$response_data = $this->get_cart_response_data();
			error_log('Cart update successful. Response: ' . print_r($response_data, true));
			
			wp_send_json_success($response_data);
			
		} catch (Exception $e) {
			error_log('Cart update failed: ' . $e->getMessage());
			wp_send_json_error([
				'message' => $e->getMessage(),
				'debug' => 'Exception caught during cart update'
			]);
		}
	}

	/**
	 * Remove cart item via AJAX
	 */
	public function remove_cart_item() {
		check_ajax_referer('panda_cart_nonce', 'nonce');
		
		$cart_item_key = isset($_POST['key']) ? sanitize_text_field($_POST['key']) : '';
		
		if (empty($cart_item_key)) {
			wp_send_json_error([
				'message' => __('Invalid cart item key', 'panda-hf')
			]);
		}
		
		try {
			WC()->cart->remove_cart_item($cart_item_key);
			
			wp_send_json_success($this->get_cart_response_data());
			
		} catch (Exception $e) {
			wp_send_json_error([
				'message' => $e->getMessage()
			]);
		}
	}
}

new Cart_Ajax();