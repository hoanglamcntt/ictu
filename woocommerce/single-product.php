<?php
defined('ABSPATH') || exit;

get_header('shop'); ?>
<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>
    <div class="theme-single-product__container">
        <div class="theme-single-product__info-container">
            <div class="woocommerce">
                <div class="single-product">
                    <?php while (have_posts()) : ?>
                        <?php the_post(); ?>
                        <?php wc_get_template_part('content', 'single-product'); ?>
                    <?php endwhile; // end of the loop. ?>
                </div>
            </div>
        </div>
        <div class="theme-single-product__tab-content">
            <?php woocommerce_output_product_data_tabs(); ?>
        </div>
        <?php do_action('theme_output_related_products'); ?>
    </div>
<?php

/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

/**
 * woocommerce_sidebar hook.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');

get_footer('shop');