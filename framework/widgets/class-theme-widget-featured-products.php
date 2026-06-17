<?php
defined('ABSPATH') || exit;

/**
 * Theme support system widget class.
 *
 * @extends WC_Widget
 */
if (!class_exists('Theme_Widget_Featured_Products')) {
    class Theme_Widget_Featured_Products extends OVIC_Widget {

        public function __construct()
        {
            $this->widget_cssclass    = 'theme-widget theme-widget-featured-products';
            $this->widget_description = __('List featured products', 'ictu');
            $this->widget_id          = 'theme-widget-featured-products';
            $this->widget_name        = __('Theme: Featured Products', 'ictu');
            $this->settings           = apply_filters(
                'theme-widget-featured-products-settings',
                array(
                    'number' => array(
                        'type'    => 'number',
                        'title'   => __('Max Post Number', 'ictu'),
                        'default' => 10,
                    ),
                    'order'  => array(
                        'type'    => 'select',
                        'default' => 'desc',
                        'title'   => __('Order', 'ictu'),
                        'options' => array(
                            'asc'  => __('ASC', 'ictu'),
                            'desc' => __('DESC', 'ictu')
                        ),
                    ),
                )
            );
            parent::__construct();
        }

        /**
         * Output widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Widget instance.
         * @see WP_Widget
         */
        public function widget($args, $instance)
        {
            ob_start();
            $products = $this->get_products($args, $instance);
            ?>
            <div class="theme-widget-featured-products__inner">
                <h3 class="theme-widget-featured-products__title"><?php esc_html_e('Featured Products', 'ictu'); ?></h3>
                <div class="theme-widget-featured-products__content">
                    <?php if ($products && $products->have_posts()): ?>
                        <?php $this->widget_start($args, $instance); ?>
                        <ul class="theme-widget-featured-products__list">
                            <?php while ($products->have_posts()): ?>
                                <?php $products->the_post(); ?>
                                <?php $attachment_id = get_post_thumbnail_id(get_the_ID()); ?>
                                <li class="theme-widget-featured-products__post-elm">
                                    <div class="theme-widget-featured-products__post-wrap">
                                        <figure class="theme-widget-featured-products__wrap-media">
                                            <a href="<?php echo esc_url(get_permalink()) ?>" title="<?php echo esc_attr(get_the_title()) ?>">
                                                <?php if ($attachment_id): ?>
                                                    <?php echo wp_get_attachment_image($attachment_id, [100, 100], false, array('class' => 'theme-widget-featured-products__thumbnail')) ?>
                                                <?php else: ?>
                                                    <img src="https://via.placeholder.com/300" class="theme-widget-featured-products__thumbnail" alt="" width="300" height="300">
                                                <?php endif; ?>
                                            </a>
                                        </figure>
                                        <div class="theme-widget-featured-products__wrap-info">
                                            <h4 class="theme-widget-featured-products__post-title">
                                                <a href="<?php echo esc_url(get_permalink()) ?>" title="<?php echo esc_attr(get_the_title()) ?>">
                                                    <?php echo esc_attr(get_the_title()) ?>
                                                </a>
                                            </h4>
                                            <p class="theme-widget-featured-products__get-detail">
                                                <a href="<?php echo esc_url(get_permalink()) ?>" title="<?php echo esc_attr(get_the_title()) ?>"><i class="fa fa-caret-right" aria-hidden="true"></i><?php echo __('Get details', 'ictu'); ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <?php $this->widget_end($args); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
            echo apply_filters('theme-widget-featured-products', ob_get_clean(), $instance);
        }

        /**
         * Query the products and return them.
         *
         * @param array $args Arguments.
         * @param array $instance Widget instance.
         *
         * @return WP_Query
         */
        public function get_products($args, $instance)
        {
            $number                      = !empty($instance['number']) ? absint($instance['number']) : 10;
            $order                       = !empty($instance['order']) ? sanitize_title($instance['order']) : 'desc';
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();

            $query_args = array(
                'posts_per_page' => $number,
                'post_status'    => 'publish',
                'post_type'      => 'product',
                'no_found_rows'  => 1,
                'order'          => $order,
                'meta_query'     => array(),
                'tax_query'      => array(
                    'relation' => 'AND',
                ),
            ); // WPCS: slow query ok.

            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
                'operator' => 'NOT IN',
            );
            $query_args['post_parent'] = 0;

            if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
                $query_args['tax_query'][] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_taxonomy_id',
                        'terms'    => $product_visibility_term_ids['outofstock'],
                        'operator' => 'NOT IN',
                    ),
                ); // WPCS: slow query ok.
            }

            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $product_visibility_term_ids['featured'],
            );
            $query_args['orderby']     = 'date';

            return new WP_Query(apply_filters('theme_featured_product_query_args', $query_args));
        }
    }

    /**
     * Register Widgets.
     *
     * @since 2.3.0
     */
    add_action('widgets_init',
        function () {
            register_widget('Theme_Widget_Featured_Products');
        }
    );
}