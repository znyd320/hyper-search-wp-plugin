<?php if (!defined('ABSPATH')) exit; ?>
<div class="panda-template-settings">
    <div class="panda-template-type">
        <h3>Template Type</h3>
        <select name="template_type">
            <option value="--" <?php selected($template_type, '--'); ?>>Select One</option>
            <option value="header" <?php selected($template_type, 'header'); ?>>Header</option>
            <option value="footer" <?php selected($template_type, 'footer'); ?>>Footer</option>
            <option value="before_footer" <?php selected($template_type, 'before_footer'); ?>>Before Footer</option>
            <option value="shortcode" <?php selected($template_type, 'shortcode'); ?>>Shortcode</option>
            <option value="hook" <?php selected($template_type, 'hook'); ?>>Hook</option>
        </select>
    </div>

    <div class="card panda-shortcode-display">
        <div class="section-header">
            <h3>Shortcode</h3>
        </div>
        <div class="shortcode-wrapper">
            <code id="shortcode">[panda_template id="<?php echo esc_attr($post_id); ?>"]</code>
            <span class="copied">Copied!</span>
        </div>
        <p class="description">Copy this shortcode and paste it where you want to display this template.</p>
    </div>

    <div class="card panda-display-locations">
        <div class="section-header">
            <h3>Display Location</h3>
        </div>
        <select name="display_location" class="enhanced-select">
            <option value="--" <?php selected($display_location, '--'); ?>>Select One</option>
            <optgroup label="Single Pages">
                <?php foreach ($location_options_singulars as $key => $value): ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($display_location, $key); ?>><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </optgroup>
            
            <?php if (phf_is_woocommerce_active()): ?>
            <optgroup label="WooCommerce">
                <option value="woocommerce_before_main_content" <?php selected($display_location, 'woocommerce_before_main_content'); ?>>Before Main Content</option>
                <option value="woocommerce_after_main_content" <?php selected($display_location, 'woocommerce_after_main_content'); ?>>After Main Content</option>
                <option value="woocommerce_before_shop_loop" <?php selected($display_location, 'woocommerce_before_shop_loop'); ?>>Before Shop Loop</option>
                <option value="woocommerce_after_shop_loop" <?php selected($display_location, 'woocommerce_after_shop_loop'); ?>>After Shop Loop</option>
                <option value="woocommerce_before_single_product" <?php selected($display_location, 'woocommerce_before_single_product'); ?>>Before Single Product</option>
                <option value="woocommerce_after_single_product" <?php selected($display_location, 'woocommerce_after_single_product'); ?>>After Single Product</option>
                <option value="woocommerce_before_cart" <?php selected($display_location, 'woocommerce_before_cart'); ?>>Before Cart</option>
                <option value="woocommerce_after_cart" <?php selected($display_location, 'woocommerce_after_cart'); ?>>After Cart</option>
                <option value="woocommerce_before_checkout_form" <?php selected($display_location, 'woocommerce_before_checkout_form'); ?>>Before Checkout Form</option>
                <option value="woocommerce_after_checkout_form" <?php selected($display_location, 'woocommerce_after_checkout_form'); ?>>After Checkout Form</option>
            </optgroup>
            <?php endif; ?>
        </select>
    </div>

    <div class="card panda-display-conditions">
        <div class="section-header">
            <h3>Display Condition</h3>
        </div>
        <select name="display_condition" class="enhanced-select">
            <option value="--" <?php selected($display_condition, '--' && !empty($display_condition)); ?>>Select One</option>
            <optgroup label="Basic">
                <option value="entire_website" <?php selected($display_condition, 'entire_website', true); ?> <?php echo (empty($display_condition) ? 'selected' : ''); ?>>Entire Website</option>
                <option value="all_singulars" <?php selected($display_condition, 'all_singulars'); ?>>All Singulars</option>
                <option value="all_archives" <?php selected($display_condition, 'all_archives'); ?>>All Archives</option>
            </optgroup>

            <optgroup label="Special Pages">
                <option value="404" <?php selected($display_condition, '404'); ?>>404 Page</option>
                <option value="search" <?php selected($display_condition, 'search'); ?>>Search Page</option>
                <option value="blog" <?php selected($display_condition, 'blog'); ?>>Blog / Posts Page</option>
                <option value="front_page" <?php selected($display_condition, 'front_page'); ?>>Front Page</option>
                <option value="date_archive" <?php selected($display_condition, 'date_archive'); ?>>Date Archive</option>
                <option value="author_archive" <?php selected($display_condition, 'author_archive'); ?>>Author Archive</option>
            </optgroup>

            <optgroup label="Posts">
                <option value="all_posts" <?php selected($display_condition, 'all_posts'); ?>>All Posts</option>
                <option value="posts_archive" <?php selected($display_condition, 'posts_archive'); ?>>All Posts Archive</option>
                <option value="category_archive" <?php selected($display_condition, 'category_archive'); ?>>All Categories Archive</option>
                <option value="tag_archive" <?php selected($display_condition, 'tag_archive'); ?>>All Tags Archive</option>
            </optgroup>

            <optgroup label="Pages">
                <option value="all_pages" <?php selected($display_condition, 'all_pages'); ?>>All Pages</option>
            </optgroup>

            <optgroup label="Specific Target">
                <option value="specific" <?php selected($display_condition, 'specific'); ?>>Specific Pages / Posts / Taxonomies, etc.</option>
            </optgroup>
        </select>
    </div>


    <div class="card panda-user-roles">
        <div class="section-header">
            <h3>User Roles</h3>
        </div>
        <select name="user_roles" class="enhanced-select">
            <option value="--" <?php selected($user_roles, '--'); ?>>Select One</option>
            <optgroup label="Basic">
                <option value="all" <?php selected($user_roles, 'all'); ?>>All</option>
                <option value="logged_in" <?php selected($user_roles, 'logged_in'); ?>>Logged In</option>
                <option value="logged_out" <?php selected($user_roles, 'logged_out'); ?>>Logged Out</option>
            </optgroup>
            <optgroup label="Advanced">
                <?php foreach (wp_roles()->get_names() as $role_value => $role_label): ?>
                    <option value="<?php echo esc_attr($role_value); ?>" <?php selected($user_roles, $role_value); ?>><?php echo esc_html($role_label); ?></option>
                <?php endforeach; ?>
            </optgroup>
        </select>
    </div>
</div>