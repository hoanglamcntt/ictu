<?php defined('ABSPATH') || exit;

if (!function_exists('theme_mini_cart')) {
    function theme_mini_cart()
    {
        ?>
        <div class="block-mini-cart">
            <a class="woo-cart-link icon-link" href="<?php echo esc_url(wc_get_cart_url()); ?>">
                <span class="icon">
                    <img class="in-tn-icon-cart" src="<?php echo esc_url(get_theme_file_uri('/assets/images/mini-cart.png')); ?>" alt="in thai nguyen mini cart">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
                <span class="text">Giỏ hàng</span>
            </a>
            <?php //the_widget('WC_Widget_Cart', 'title=giỏ hàng');
            ?>
        </div>
        <?php
    }
}

if (!function_exists('theme_template_product_category_banner')) {
    function theme_template_product_category_banner($term)
    {
        if (!$term) {
            return;
        }
        $cat_name     = $term->name;
        $bg           = '#ececec no-repeat scroll center';
        $thumbnail_id = absint(get_term_meta($term->term_id, 'banner_id', true));
        if ($thumbnail_id) {
            $img      = wp_get_attachment_image_src($thumbnail_id, 'full');
            $bg_url   = $img[0] ? $img[0] : wc_placeholder_img_src('full');
            $bg       = "#ececec url({$bg_url}) no-repeat scroll center";
            $cat_name = '';
        }
        ?>
        <div class="theme-product-category-banner" style="background: <?php echo esc_attr($bg) ?>">
            <?php if ($cat_name) : ?>
                <h2 class="term-name"><?php echo esc_html($cat_name) ?></h2>
            <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('theme_single_product_cat_template')) {
    function theme_single_product_cat_template()
    {
        global $post;
        $terms = get_the_terms($post->ID, 'product_cat');
        if ($terms && !is_wp_error($terms)) { ?>
            <a href="<?php echo esc_url(get_term_link($terms[0])) ?>" class="theme-single-product-cat__link"><?php echo esc_html($terms[0]->name) ?></a>
            <?php
        }
    }
}

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
/************************************
 * overwrite single product tabs
 ***********************************/
add_filter('woocommerce_product_tabs', 'theme_overwrite_single_product_tabs', 99999);
add_filter('woocommerce_product_description_heading', '__return_null', 9999999);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

if (!function_exists('theme_overwrite_single_product_tabs')) {
    function theme_overwrite_single_product_tabs($tabs)
    {
        if (isset($tabs['additional_information'])) {
            unset($tabs['additional_information']);
        }

        $tabs['theme_product_specifications'] = [
            'title'    => __('Specifications', 'ictu'),
            'priority' => 20,
            'callback' => 'theme_product_specifications_template',
        ];

        $tabs['theme_product_catalogue'] = [
            'title'    => __('Download catalogue', 'ictu'),
            'priority' => 30,
            'callback' => 'theme_product_catalogue_template',
        ];

        if ($tabs['reviews']) {
            $cache = $tabs['reviews'];
            unset($tabs['reviews']);
            $tabs['reviews'] = [
                'title'    => $cache['title'],
                'priority' => 40,
                'callback' => $cache['callback'],
            ];
        }

        return $tabs;
    }
}

/*Tab bảng giá */
if (!function_exists('theme_product_catalogue_template')) {
    function theme_product_catalogue_template()
    {
        echo wp_specialchars_decode(theme_get_product_meta_by_name('link_catalogue'));
    }
}

/*Tab đặc tính kỹ thuật*/
if (!function_exists('theme_product_specifications_template')) {
    function theme_product_specifications_template()
    {
        echo wp_specialchars_decode(theme_get_product_meta_by_name('specifications'));
    }
}

add_action('woocommerce_before_single_product', function () {
    wp_enqueue_style('theme-product');
    wp_enqueue_style('theme-single-product');
},         10);

if (!function_exists('theme_display_review_average')) {
    function theme_display_review_average()
    {
        global $product, $comment;
        $args         = array(
            'post_type'   => 'product',
            'post_status' => 'publish',
            'post_id'     => $product->get_id(),
        );
        $comments     = get_comments($args);
        $average      = $product->get_average_rating();
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $stars        = array('5' => 0, '4' => 0, '3' => 0, '2' => 0, '1' => 0,);
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
                if ($rating && '0' != $comment->comment_approved) {
                    $stars[$rating]++;
                }
            }
        }
        ?>
        <div class="review--average">
            <div class="average">
                <div class="average__head">
                    <h5 class="average__title">Đánh Giá Trung Bình</h5>
                </div>
                <div class="average__body">
                    <span class="average-val"><?php echo esc_html($average); ?>/5</span>
                    <div class="rating--inner">
                        <div class="star-rating">
                            <?php echo wc_get_star_rating_html($average, $rating_count); ?>
                        </div>
                    </div>
                </div>
                <div class="average__foot">
                    <p class="average__review-counter"><?php echo sprintf('(%s nhận xét)', $review_count) ?></p>
                </div>
            </div>
            <div class="review__average-detail">
                <ul class="detail">
                    <?php foreach ($stars as $key => $rating): ?>
                        <?php $process = $rating > 0 ? (($rating / $rating_count) * 100) : 0; ?>
                        <li>
                            <span class="star"><?php echo esc_html($key); ?><i class="fa fa-star"></i></span>
                            <span class="process"><span class="process-bar" style="width:<?php echo esc_attr($process); ?>%"></span></span>
                            <span class="count"><?php echo number_format($process, 2, '.', ''); ?>%</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="review__toggle-from">
                <h5 class="review__toggle-from__title">Chia sẻ nhận xét về sản phẩm</h5>
                <button class="review__toggle-from__button theme_js_toggle_form_review" type="button">Viết nhận xét của bạn</button>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('theme_get_product_meta')) {
    function theme_get_product_meta($product = null)
    {
        if (!$product) {
            global $product;
        }
        $data = $product->get_meta_data();

        return is_array($data) && !empty($data)
            ? array_filter($data, function ($object, $key) {
                return $object->key === '_custom_metabox_extra_specifications';
            },             ARRAY_FILTER_USE_BOTH) : null;
    }
}

if (!function_exists('theme_get_product_meta_by_name')) {
    function theme_get_product_meta_by_name($name)
    {
        global $product;
        $meta = get_post_meta($product->get_id(),'_custom_metabox_extra_specifications',true);
        return is_array($meta) && array_key_exists($name, $meta) ? $meta[$name] : '';
    }
}

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);


add_action('woocommerce_before_main_content', 'theme_template_woocommerce_before_main_content', 10);
add_action('woocommerce_after_main_content', function () {
    echo '</main></div>';
},         10);
add_action('woocommerce_sidebar', function () {
    echo '</div>';
},         20);
if (!function_exists('theme_template_woocommerce_before_main_content')) {
    function theme_template_woocommerce_before_main_content()
    {
        $page_layout = theme_page_layout();
        $classes     = $page_layout['layout'] != 'full' ? "container site-content use-page-head--1 woocommerce-archive-page sidebar-{$page_layout['layout']}" : 'container site-content use-page-head--1 woocommerce-archive-page no-sidebar';
        ob_start();
        theme_page_banner_template(); ?>
        <div id="content" class="<?php echo esc_attr($classes) ?>">
        <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
        <?php theme_breadcrumb(); ?>
        <?php
        echo ob_get_clean();
    }
}

//if (!function_exists('theme_template_woocommerce_after_main_content')) {
//    function theme_template_woocommerce_after_main_content()
//    {
//        echo '</main></div></div>';
//    }
//}


/**
 * Hook: woocommerce_before_shop_loop.
 *
 * @hooked woocommerce_output_all_notices - 10
 * @hooked woocommerce_result_count - 20
 * @hooked woocommerce_catalog_ordering - 30
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

if (!function_exists('theme_label_get_detail_in_loop')) {
    function theme_label_get_detail_in_loop()
    {
        global $product;
        $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
        ?>
        <h4 class="theme_product_title_in_loop">
            <a href="<?php echo esc_url($link) ?>" class="theme_product_title_in_loop__link"><?php echo get_the_title() ?></a>
        </h4>
        <a href="<?php echo esc_url($link) ?>" class="theme_link_get_detail_in_loop"><i class="fa fa-caret-right" aria-hidden="true"></i><?php echo __('Get details', 'ictu') ?>
        </a>
        <?php
    }

    add_action('woocommerce_after_shop_loop_item', 'theme_label_get_detail_in_loop', 20);
}

if (!function_exists('theme_set_column_before_shop_loop')) {
    function theme_set_column_before_shop_loop()
    {
        $columns = get_theme_option('product_loop_columns', '4');
        wc_set_loop_prop('columns', $columns);
    }

    add_action('woocommerce_before_main_content', 'theme_set_column_before_shop_loop', 5);
}

if (!function_exists('theme_single_related_products')) {
    function theme_single_related_products()
    {
        if (get_theme_option('woo_related_enable', 'enable') === 'enable') {
            $args = array(
                'posts_per_page' => get_theme_option('woo_related_perpage', '6'),
                'columns'        => 4,
                'orderby'        => 'rand', // @codingStandardsIgnoreLine.
            );
            woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
        }
    }

    add_action('theme_output_related_products', 'theme_single_related_products');
}

add_filter('woocommerce_product_related_products_heading', function () {
    return get_theme_option('woo_related_title', 'enable') === 'enable' ? __('Related products', 'ictu') : '';
},         10);


if (!function_exists('theme_structured_woo_data_breadcrumblist')) {
    function theme_structured_woo_data_breadcrumblist( $crumbs)
    {
        $result = [];
        if((is_product() || is_product_category()) && !empty($crumbs)){
            foreach ($crumbs as $key => $value){
                if($key==0){
                    $result[] = $value;
                }else if($key==1){
                    $result[] = [__( 'Products','ictu'),get_permalink( wc_get_page_id( 'shop' ) )];
                    $result[] = $crumbs[1];
                }else{
                    $result[] = $crumbs[$key];
                }
            }
        }else{
            $result = $crumbs;
        }
        return $result;
    }
}
add_filter('woocommerce_get_breadcrumb','theme_structured_woo_data_breadcrumblist',20);