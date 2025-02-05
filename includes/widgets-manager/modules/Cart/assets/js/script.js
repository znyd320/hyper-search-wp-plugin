jQuery(document).ready(function ($) {
    const cartWrapper = $('.panda-cart-wrapper');
    const cartPanel = $('.panda-mini-cart-side-panel');

    // Cart Panel Toggle
    $('.panda-cart-icon-wrapper').on('click', function (e) {
        e.preventDefault();
        cartPanel.addClass('active');
        $('body').addClass('cart-open');
        $('.panda-cart-overlay').fadeIn();
    });

    // Close Panel
    $('.panda-mini-cart-close, .panda-cart-overlay').on('click', function () {
        cartPanel.removeClass('active');
        $('body').removeClass('cart-open');
        $('.panda-cart-overlay').fadeOut();
    });

    // Quantity Controls
    $(document).on('click', '.quantity-btn', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const $qtySpan = $btn.siblings('.qty');
        const currentVal = parseInt($qtySpan.text());
        const isPlus = $btn.hasClass('plus');
        const newVal = isPlus ? currentVal + 1 : Math.max(0, currentVal - 1);

        $qtySpan.text(newVal);
        updateCartItem($btn.closest('.panda-mini-cart-item').data('key'), newVal);
    });


    $(document.body).on('added_to_cart removed_from_cart updated_cart_totals wc_fragments_loaded wc_fragments_refreshed', function (event, fragments) {
        if (fragments) {
            $.each(fragments, function (selector, content) {
                console.log("Selector: " + selector);
                console.log("Content: " + content);
                console.log("=====================")
                $(selector).replaceWith(content);
            });
        }
    });




    // Direct Quantity Input
    $(document).on('change', '.qty', function () {
        const $input = $(this);
        const quantity = parseInt($input.val());
        const key = $input.closest('.panda-mini-cart-item').data('key');

        updateCartItem(key, quantity);
    });

    // Remove Item
    $(document).on('click', '.remove-item', function (e) {
        e.preventDefault();
        const $item = $(this).closest('.panda-mini-cart-item');
        updateCartItem($item.data('key'), 0);
    });

    // Update Cart Item
    function updateCartItem(key, quantity) {
        const $item = $(`.panda-mini-cart-item[data-key="${key}"]`);
        $item.addClass('updating');

        $.ajax({
            url: pandaCart.ajaxurl,
            type: 'POST',
            data: {
                action: 'panda_update_cart_quantity',
                security: pandaCart.panda_cart_nonce,
                key: key,
                quantity: quantity
            },
            success: function (response) {
                if (response.success) {
                    // If quantity is 0, remove the item
                    if (quantity === 0) {
                        $item.fadeOut(300, function () { $(this).remove(); });
                    } else {
                        // Just update the quantity display
                        $item.find('.qty').text(quantity);
                    }

                    updateCartUI(response.data);

                } else {
                    console.error('Cart update failed:', response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Ajax error:', status, error);
            },
            complete: function () {
                $item.removeClass('updating');
            }
        });
    }
    // Update Cart UI
    function updateCartUI(data) {

        $('.panda-cart-count').text(data.cart_count);
        $('.panda-cart-total').html(data.cart_total);
        $('.cart-subtotal .amount').html(data.cart_total);

        if (data.cart_count === 0) {
            $('.panda-mini-cart-items').html(
                `<div class="panda-mini-cart-empty">${pandaCart.i18n.cartEmpty}</div>`
            );
        }
    }

    // Listen for WooCommerce cart updates
    $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function () {
        $.ajax({
            url: pandaCart.ajaxurl,
            type: 'POST',
            data: {
                // action: 'update_cart_count',
                action: 'panda_update_cart_quantity',
                nonce: pandaCart.panda_cart_nonce
            },
            success: function (response) {
                if (response.success) {
                    updateCartUI(response.data);
                }
            }
        });
    });
});