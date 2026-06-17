<?php
/**
 *
 * Name: Product Style List
 * Shortcode: false
 * Theme Option: false
 **/
?>
<?php
global $product;

$functions = array(
    array('add_action', 'woocommerce_shop_loop_item_title', 'theme_get_categories', 3),
    array('remove_action', 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10),
    array('add_action', 'woocommerce_after_shop_loop_item_title', 'theme_get_custom_attributes', 15),
    array('remove_action', 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5),
);
theme_add_action($functions);
?>
    <div class="product-inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>
        <div class="product-thumb images">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
        </div>
        <div class="product-info">
            <div class="product-info--inner">
                <div class="product-title--wrap">
                    <?php
                    /**
                     * Hook: woocommerce_shop_loop_item_title.
                     *
                     * @hooked woocommerce_template_loop_product_title - 10
                     */
                    do_action('woocommerce_shop_loop_item_title');
//                    theme_product_short_description();
                    /**
                     * Hook: woocommerce_after_shop_loop_item_title.
                     *
                     * @hooked woocommerce_template_loop_rating - 5
                     * @hooked woocommerce_template_loop_price - 10
                     */
                    do_action('woocommerce_after_shop_loop_item_title');
                    ?>
                    <?php if (!theme_is_mobile()) : ?>
                        <div class="group-button">
                            <?php
                            /**
                             * Hook: woocommerce_after_shop_loop_item.
                             *
                             * @hooked woocommerce_template_loop_product_link_close - 5
                             * @hooked woocommerce_template_loop_add_to_cart - 10
                             */
                            do_action('woocommerce_after_shop_loop_item');
                            ?>
                            <?php
                            do_action('theme_function_shop_loop_item_wishlist');
                            do_action('theme_function_shop_loop_item_compare');
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                /**
                 * Hook: theme_loop_extra_content.
                 *
                 * @hooked theme_display_loop_extra_content - 10
                 */
                do_action('theme_loop_extra_content');
                ?>
            </div>
        </div>

    </div>
<?php
theme_add_action($functions, true);
