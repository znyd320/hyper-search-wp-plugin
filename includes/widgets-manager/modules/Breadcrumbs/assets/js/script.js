jQuery(document).ready(function($) {
    function initBreadcrumbs() {
        $('.panda-breadcrumbs-item').each(function() {
            var $item = $(this);
            var $text = $item.find('span');
            
            // Handle text truncation
            if ($item.hasClass('overflow')) {
                var maxWidth = $item.attr('panda-breadcrumbs-slide-max-width');
                if (maxWidth && $text.width() > maxWidth) {
                    $item.css('max-width', maxWidth + 'px');
                    $item.addClass('panda-slide-anim');
                }
            }
        });
    }

    // Initialize on page load
    initBreadcrumbs();

    // Re-initialize on AJAX content updates
    $(document).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/panda-breadcrumbs.default', function() {
            initBreadcrumbs();
        });
    });
});