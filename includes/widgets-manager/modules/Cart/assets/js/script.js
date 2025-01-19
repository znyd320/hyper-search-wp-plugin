jQuery(document).ready(function($) {

    console.log("Panda Cart Widget JS loaded");
      // Cache DOM elements
      const $body = $('body');
      const $document = $(document);
      
      // Add overlay to body if not exists
      if (!$('.panda-cart-overlay').length) {
          $body.append('<div class="panda-cart-overlay"></div>');
      }
  
      // Handle add to cart button clicks
      $document.on('click', '.add_to_cart_button', function(e) {
          const $button = $(this);
          
          // Prevent double-clicking
          if ($button.hasClass('loading')) {
              e.preventDefault();
              return;
          }
      });
  
      // Handle cart updates
      $document.on('added_to_cart removed_from_cart updated_cart_totals', function(event, fragments, cart_hash, $button) {
          updateCartContents();
      });
  
      // Update cart contents via AJAX
      function updateCartContents() {
          $.ajax({
              url: pandaCart.ajaxurl,
              type: 'POST',
              data: {
                  action: 'update_cart_count',
                  nonce: pandaCart.update_cart_nonce
              },
              beforeSend: function() {
                  $('.panda-cart-wrapper').addClass('loading');
              },
              success: function(response) {
                  if (response.success) {
                      updateCartUI(response.data);
                  }
              },
              complete: function() {
                  $('.panda-cart-wrapper').removeClass('loading');
              }
          });
      }
  
      // Side panel functionality
      $('.panda-cart-icon-wrapper').on('click', function(e) {
          const $wrapper = $(this).closest('.panda-cart-wrapper');
          const $sidePanel = $wrapper.find('.panda-mini-cart-side-panel');
          
          if ($sidePanel.length) {
              e.preventDefault();
              $sidePanel.addClass('active');
              $('.panda-cart-overlay').addClass('active');
              $body.css('overflow', 'hidden');
          }
      });
  
      // Close side panel
      $('.panda-mini-cart-close, .panda-cart-overlay').on('click', function() {
          $('.panda-mini-cart-side-panel').removeClass('active');
          $('.panda-cart-overlay').removeClass('active');
          $body.css('overflow', '');
      });
  
      // Prevent closing when clicking inside the panel
      $('.panda-mini-cart-side-panel').on('click', function(e) {
          e.stopPropagation();
      });
  
      // Handle ESC key to close panel
      $(document).on('keyup', function(e) {
          if (e.key === 'Escape') {
              $('.panda-mini-cart-side-panel').removeClass('active');
              $('.panda-cart-overlay').removeClass('active');
              $body.css('overflow', '');
          }
      });
  
      // Handle quantity changes
      $(document).on('click', '.panda-mini-cart-item .quantity-btn', function(e) {
          e.preventDefault();
          const $btn = $(this);
          const $item = $btn.closest('.panda-mini-cart-item');
          const $input = $item.find('.qty');
          const currentVal = parseInt($input.val());
          
          if ($btn.hasClass('minus')) {
              if (currentVal > 0) {
                  $input.val(currentVal - 1).trigger('change');
              }
          } else {
              const max = parseInt($input.attr('max'));
              if (currentVal < max || !max) {
                  $input.val(currentVal + 1).trigger('change');
              }
          }
      });
  
      // Handle quantity input changes
      $(document).on('change', '.panda-mini-cart-item .qty', function() {
          const $input = $(this);
          const $item = $input.closest('.panda-mini-cart-item');
          const quantity = parseInt($input.val());
          const key = $item.data('key');
          
          updateCartItem(key, quantity);
      });
  
      // Handle item removal
      $(document).on('click', '.panda-mini-cart-item .remove-item', function(e) {
          e.preventDefault();
          const $item = $(this).closest('.panda-mini-cart-item');
          const key = $item.data('key');
          
          removeCartItem(key);
      });
  
      // Function to update cart item
      function updateCartItem(key, quantity) {
          console.log('Updating cart item:', { key, quantity }); // Debug log
          
          $.ajax({
              url: pandaCart.ajaxurl,
              type: 'POST',
              data: {
                  action: 'panda_update_cart_quantity',
                  nonce: pandaCart.cart_nonce,
                  key: key,
                  quantity: quantity
              },
              beforeSend: function() {
                  $('.panda-cart-wrapper').addClass('loading');
                  console.log('Sending cart update request...'); // Debug log
              },
              success: function(response) {
                  console.log('Cart update response:', response); // Debug log
                  
                  if (response.success) {
                      updateCartUI(response.data);
                  } else {
                      const errorMessage = response.data && response.data.message 
                          ? response.data.message 
                          : pandaCart.i18n.errorMessage;
                      alert(errorMessage);
                      
                      // Log debug information if available
                      if (response.data && response.data.debug) {
                          console.error('Debug:', response.data.debug);
                      }
                  }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  console.error('Cart update failed:', {
                      status: textStatus,
                      error: errorThrown,
                      response: jqXHR.responseText
                  });
                  alert(pandaCart.i18n.errorMessage);
              },
              complete: function() {
                  $('.panda-cart-wrapper').removeClass('loading');
              }
          });
      }
  
      // Function to remove cart item
      function removeCartItem(key) {
          $.ajax({
              url: pandaCart.ajaxurl,
              type: 'POST',
              data: {
                  action: 'panda_remove_cart_item',
                  nonce: pandaCart.cart_nonce,
                  key: key
              },
              beforeSend: function() {
                  $('.panda-cart-wrapper').addClass('loading');
              },
              success: function(response) {
                  if (response.success) {
                      updateCartUI(response.data);
                  } else if (response.data && response.data.message) {
                      alert(response.data.message);
                  }
              },
              error: function() {
                  alert(pandaCart.i18n.errorMessage);
              },
              complete: function() {
                  $('.panda-cart-wrapper').removeClass('loading');
              }
          });
      }
  
      // Function to update cart UI
      function updateCartUI(data) {
          console.log('Updating cart UI with:', data); // Debug log
          
          try {
              // Update mini cart content if available
              if (data.cart_content) {
                  $('.panda-mini-cart-content').html(data.cart_content);
              }
              
              // Update cart count with animation
              if (data.cart_count !== undefined) {
                  const $count = $('.panda-cart-count');
                  $count.addClass('updating').text(data.cart_count);
              }
              
              // Update total with animation
              if (data.cart_total) {
                  const $total = $('.panda-cart-total');
                  $total.addClass('updating').html(data.cart_total);
              }
              
              // Update any WooCommerce fragments if available
              if (data.fragments) {
                  $.each(data.fragments, function(key, value) {
                      $(key).replaceWith(value);
                  });
              }
              
              // Remove animation classes after transition
              setTimeout(() => {
                  $('.updating').removeClass('updating');
              }, 500);
              
              // Trigger custom event for other parts of the site
              $(document.body).trigger('panda_cart_updated', [data]);
              
          } catch (error) {
              console.error('Error updating cart UI:', error);
              alert(pandaCart.i18n.errorMessage);
          }
      }
  
      // Listen for cart updates from other sources
      $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function() {
          updateCartContents();
      });
  }); 