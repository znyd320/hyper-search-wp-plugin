<?php
/**
 * Search Form Template
 *
 * @package Hyper_Search
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}
?>

<div class="hyper-search-form" data-form-id="<?php echo esc_attr( isset( $settings['form_id'] ) ? $settings['form_id'] : $post->ID ); ?>">
    <div class="hyper-search-container">
        <input autocomplete="false" aria-autocomplete="none" type="text" 
               class="hyper-search-input" 
               placeholder="<?php esc_attr_e( 'Type to search...', 'hyper-search' ); ?>"
               aria-label="<?php esc_attr_e( 'Search', 'hyper-search' ); ?>">
        <div class="hyper-search-results"></div>
    </div>
</div>

<?php if ( is_admin() ) : ?>
    <div class="hyper-search-admin-settings">
        <p>
            <label for="hyper_search_post_types">
                <?php esc_html_e( 'Post Types to Search (comma-separated)', 'hyper-search' ); ?>
            </label>
            <input type="text" 
                   id="hyper_search_post_types"
                   name="hyper_search_post_types" 
                   value="<?php echo esc_attr( $post_types ); ?>" 
                   class="widefat">
            <span class="description">
                <?php esc_html_e( 'Example: post, page, product', 'hyper-search' ); ?>
            </span>
        </p>
        <p>
            <label for="hyper_search_meta_keys">
                <?php esc_html_e( 'Meta Keys to Include (comma-separated)', 'hyper-search' ); ?>
            </label>
            <input type="text" 
                   id="hyper_search_meta_keys"
                   name="hyper_search_meta_keys" 
                   value="<?php echo esc_attr( $meta_keys ); ?>" 
                   class="widefat">
            <span class="description">
                <?php esc_html_e( 'Example: _sku, _price', 'hyper-search' ); ?>
            </span>
        </p>
    </div>
<?php endif; ?>
