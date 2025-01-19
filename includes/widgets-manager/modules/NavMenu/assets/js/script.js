(function($) {
    'use strict';

    // Initialize menu functionality
    function initPandaMenu() {
        $('.panda-nav-menu').each(function() {
            const $menu = $(this);
            const $toggle = $menu.find('.panda-nav-menu-toggle');
            const $menuList = $menu.find('.panda-nav-menu-ul');
            const breakpoint = $menu.data('mobile-breakpoint') || 768;
            
            // Enhanced mobile menu toggle
            $toggle.on('click', function(e) {
                e.preventDefault();
                $menu.toggleClass('mobile-active');
                $menuList.slideToggle(300);
                
                // Prevent body scroll when menu is open
                $('body').toggleClass('menu-open');
            });

            // Enhanced mobile submenu handling
            $menuList.find('li.menu-item-has-children').each(function() {
                const $parentItem = $(this);
                // Create mobile dropdown toggle button
                const $dropdownToggle = $('<span class="mobile-dropdown-toggle">+</span>');
                $parentItem.append($dropdownToggle);

                $dropdownToggle.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const $submenu = $parentItem.find('> ul.sub-menu');
                    $dropdownToggle.text($parentItem.hasClass('submenu-active') ? '+' : '-');
                    $submenu.slideToggle(300);
                    $parentItem.toggleClass('submenu-active');
                });
            });

            // Prevent parent link click on mobile
            $menuList.find('li.menu-item-has-children > a').on('click', function(e) {
                if (window.innerWidth <= breakpoint) {
                    e.preventDefault();
                }
            });

            // Enhanced resize handler with debounce
            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > breakpoint) {
                        $menuList.show();
                        $('.sub-menu').removeAttr('style');
                        $('body').removeClass('menu-open');
                    } else {
                        if (!$menu.hasClass('mobile-active')) {
                            $menuList.hide();
                        }
                    }
                }, 250);
            });

            // Close menu when clicking outside
            $(document).on('click touchend', function(e) {
                if (!$(e.target).closest('.panda-nav-menu').length && window.innerWidth <= breakpoint) {
                    $menu.removeClass('mobile-active');
                    $menuList.slideUp(300);
                    $('body').removeClass('menu-open');
                }
            });
        });
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        initPandaMenu();
        handleSubmenuPosition();
    });

    // Add this function inside your existing jQuery wrapper
    function handleSubmenuPosition() {
        $('.panda-nav-menu-ul li.menu-item-has-children').each(function() {
            const $menuItem = $(this);
            const $submenu = $menuItem.children('.sub-menu');
        
            $menuItem.on('mouseenter focus', function() {
                const submenuRect = $submenu[0].getBoundingClientRect();
                const parentRect = $menuItem[0].getBoundingClientRect();
                const windowWidth = window.innerWidth;
            
                // Reset previous position
                $submenu.removeClass('submenu-left submenu-right');
            
                // For first level submenu
                if ($menuItem.parent().hasClass('panda-nav-menu-ul')) {
                    return; // First level always drops down
                }
            
                // Check if submenu would overflow on right side
                if (parentRect.right + submenuRect.width > windowWidth) {
                    $submenu.addClass('submenu-left');
                } else {
                    $submenu.addClass('submenu-right');
                }
            });
        });
    }

    // Initialize for Elementor frontend
    $(window).on('elementor/frontend/init', function() {
        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction('frontend/element_ready/panda-nav-menu.default', function() {
                initPandaMenu();
            });
        }
    });

})(jQuery);
