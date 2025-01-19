<?php

use Panda\Header_Footer\Template_Loader;

/**
 * Checks if the header is active.
 *
 * @return bool True if the header is active, false otherwise.
 */
function phf_header_enabled()
{
    $status = false;
    $instance = new Template_Loader();
    $status = $instance->isHeaderActive();
    return apply_filters('phf_header_enabled', $status);
}


/**
 * Checks if the footer is active.
 *
 * @return bool True if the footer is active, false otherwise.
 */
function phf_footer_enabled()
{
    $status = false;
    $instance = Template_Loader::get_instance();
    $status = $instance->isFooterActive();
    return apply_filters('phf_footer_enabled', $status);
}


/**
 * Checks if the "before footer" is active.
 *
 * @return bool True if the "before footer" is active, false otherwise.
 */
function phf_before_footer_enabled()
{
    $status = false;
    $instance = Template_Loader::get_instance();
    $status = $instance->isBeforeFooterActive();
    return apply_filters('phf_before_footer_enabled', $status);
}


/**
 * Renders the header content.
 *
 * This function checks if the "render header" feature is enabled and then
 * renders the header content using the Template_Loader instance.
 */
function phf_render_header()
{
    if (false === apply_filters('enable_phf_render_header', true)) {
        return;
    }
    $instance = Template_Loader::get_instance();
?>
    <header id="masthead" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
        <p class="main-title phf-hidden" itemprop="headline"><a href="<?php echo bloginfo('url'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
        <?php $instance->render_header(); ?>
    </header>

<?php
}


/**
 * Renders the footer content.
 *
 * This function checks if the "render footer" feature is enabled and then
 * renders the footer content using the Template_Loader instance.
 */
function phf_render_footer()
{
    if (false === apply_filters('enable_phf_render_footer', true)) {
        return;
    }
    $instance = Template_Loader::get_instance();

?>
    <footer itemtype="https://schema.org/WPFooter" itemscope="itemscope" id="colophon" role="contentinfo">
        <?php $instance->render_footer(); ?>
    </footer>

<?php

}

/**
 * Renders the content before the footer.
 *
 * This function checks if the "before footer" feature is enabled and then
 * renders the content before the footer using the Template_Loader instance.
 */
function phf_render_before_footer()
{
    if (false === apply_filters('enable_phf_render_before_footer', true)) {
        return;
    }
    $instance = Template_Loader::get_instance();

?>
    <div class="phf-before-footer-wrap">
        <?php $instance->render_before_footer(); ?>
    </div>

<?php

}


/**
 * Is elementor plugin installed.
 */
if (! function_exists('_is_elementor_installed')) {


    /**
     * Check if Elementor is installed
     *
     * @since 1.6.0
     *
     * @access public
     * @return bool
     */
    function _is_elementor_installed()
    {
        return (file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php')) ? true : false;
    }
}
