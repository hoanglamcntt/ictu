<?php
/**
 * Template Page banner
 */
?>
<?php
$enable_page_banner = 0;
$page_banner_id     = null;
global $post;
if (class_exists('WooCommerce') && is_woocommerce()) {
    $enable_page_banner = 1;
    if (is_shop()) {
        $page_banner_id = get_theme_option('shop_banner', '');
    } else if (is_product_category()) {
        $term           = get_queried_object();
        $banner         = absint(get_term_meta($term->term_id, 'banner_id', true));
        $page_banner_id = $banner ? $banner : get_theme_option('shop_banner', '');
    } else if (is_product()) {
        $terms  = get_the_terms($post->ID, 'product_cat');
        $banner = '';
        if (!empty($terms)) {
            $term   = $terms[0];
            $banner = absint(get_term_meta($term->term_id, 'banner_id', true));
        }
        $page_banner_id = $banner ? $banner : get_theme_option('shop_banner', '');
    }
} else if (is_page()) {
    $enable_page_banner = theme_option_meta('_custom_page_banner', null, 'enable_page_banner');
    $custom_banner      = theme_option_meta('_custom_page_banner', null, 'page_banner');
    if ($enable_page_banner == 1) {
        $page_banner_id = $custom_banner ? $custom_banner : get_theme_option('page_banner', '');
    }
} else if (is_single()) {
    $enable_page_banner = 1;
    $categories         = get_the_category($post->ID);
    $banner             = '';
    if (!empty($categories)) {
        $term   = $categories[0];
        $banner = absint(get_term_meta($term->term_id, 'banner_id', true));
    }
    $page_banner_id = $banner ? $banner : get_theme_option('page_banner', '');
} else {
    $enable_page_banner = 1;
    $page_banner_id     = get_theme_option('page_banner', '');
    $term               = get_queried_object();
    if ($term) {
        $banner         = absint(get_term_meta($term->term_id, 'banner_id', true));
        $page_banner_id = $banner ? $banner : get_theme_option('page_banner', '');
    }
}
$banner_src = $enable_page_banner == 1 && $page_banner_id ? wp_get_attachment_image_url($page_banner_id, 'full') : null;
if ($banner_src): ?>
    <div class="page-banner">
        <figure class="page-banner__media">
            <img src="<?php echo esc_url($banner_src); ?>" alt="pb-img">
        </figure>
        <div class="page-banner__head-text">
            <?php theme_title(); ?>
            <?php theme_breadcrumb(); ?>
        </div>
    </div>
<?php endif; ?>