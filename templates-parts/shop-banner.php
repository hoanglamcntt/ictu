<?php
/**
 * Template Shop Banner
 *
 * @return string
 */
?>
<?php
$banner = get_theme_option('shop_banner');
if (is_product_category()) {
    $cate         = get_queried_object();
    $term_options = get_term_meta($cate->term_id, '_custom_taxonomy_options', true);
    if (!empty($term_options['shop_banner'])) {
        $banner = $term_options['shop_banner'];
    } else {
        $banner = 0;
    }
} elseif (is_product()) {
    $id              = get_the_ID();
    $product_options = get_post_meta(get_the_ID(), '_custom_metabox_post_options', true);
    if (!empty($product_options['shop_banner'])) {
        $banner = $product_options['shop_banner'];
    } else {
        $banner = 0;
    }
}
if (!empty($banner)):
    ?>
    <div class="shop-banner">
        <div class="container">
            <?php theme_edit_link($banner); ?>
            <?php
            if (class_exists('\Elementor\Plugin')) {
                echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($banner);
            } else {
                $post_id = get_post($banner);
                $content = $post_id->post_content;
                $content = apply_filters('the_content', $content);
                $content = str_replace(']]>', ']]>', $content);
                echo wp_specialchars_decode($content);
            }
            ?>
        </div>
    </div>
<?php endif; ?>
