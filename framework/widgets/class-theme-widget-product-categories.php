<?php
/**
 * Theme Product Categories Widget
 *
 * @package Theme\framework\Widgets
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Theme product categories widget class.
 *
 * @extends WC_Widget
 */
class Theme_Widget_Product_Categories extends WC_Widget {

    /**
     * Category ancestors.
     *
     * @var array
     */
    public $cat_ancestors;

    /**
     * Current Category.
     *
     * @var bool
     */
    public $current_cat;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->widget_cssclass    = 'woocommerce widget_product_categories theme_widget_product_categories';
        $this->widget_description = __('A list or dropdown of product categories.', 'ictu');
        $this->widget_id          = 'theme_product_categories';
        $this->widget_name        = __('Theme: Product Categories', 'ictu');
        $this->settings           = array(
            'orderby'            => array(
                'type'    => 'select',
                'std'     => 'name',
                'label'   => __('Order by', 'ictu'),
                'options' => array(
                    'order' => __('Category order', 'ictu'),
                    'name'  => __('Name', 'ictu'),
                ),
            ),
            'count'              => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => __('Show product counts', 'ictu'),
            ),
            'hierarchical'       => array(
                'type'  => 'checkbox',
                'std'   => 1,
                'label' => __('Show hierarchy', 'ictu'),
            ),
            'show_children_only' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => __('Only show children of the current category', 'ictu'),
            ),
            'hide_empty'         => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => __('Hide empty categories', 'ictu'),
            ),
            'max_depth'          => array(
                'type'  => 'text',
                'std'   => '',
                'label' => __('Maximum depth', 'ictu'),
            ),
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
        global $wp_query, $post;

        $count              = isset($instance['count']) ? $instance['count'] : $this->settings['count']['std'];
        $hierarchical       = isset($instance['hierarchical']) ? $instance['hierarchical'] : $this->settings['hierarchical']['std'];
        $show_children_only = isset($instance['show_children_only']) ? $instance['show_children_only'] : $this->settings['show_children_only']['std'];
        $orderby            = isset($instance['orderby']) ? $instance['orderby'] : $this->settings['orderby']['std'];
        $hide_empty         = isset($instance['hide_empty']) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
        $list_args          = array(
            'show_count'   => $count,
            'hierarchical' => $hierarchical,
            'taxonomy'     => 'product_cat',
            'hide_empty'   => $hide_empty,
        );
        $max_depth          = absint(isset($instance['max_depth']) ? $instance['max_depth'] : $this->settings['max_depth']['std']);

        $list_args['menu_order'] = false;
        $list_args['depth']      = $max_depth;

        if ('order' === $orderby) {
            $list_args['orderby']  = 'meta_value_num';
            $list_args['meta_key'] = 'order';
        }

        $this->current_cat   = false;
        $this->cat_ancestors = array();

        if (is_tax('product_cat')) {
            $this->current_cat   = $wp_query->queried_object;
            $this->cat_ancestors = get_ancestors($this->current_cat->term_id, 'product_cat');

        } elseif (is_singular('product')) {
            $terms = wc_get_product_terms(
                $post->ID,
                'product_cat',
                apply_filters(
                    'theme_product_categories_widget_product_terms_args',
                    array(
                        'orderby' => 'parent',
                        'order'   => 'DESC',
                    )
                )
            );

            if ($terms) {
                $main_term           = apply_filters('theme_product_categories_widget_main_term', $terms[0], $terms);
                $this->current_cat   = $main_term;
                $this->cat_ancestors = get_ancestors($main_term->term_id, 'product_cat');
            }
        }

        // Show Siblings and Children Only.
        if ($show_children_only && $this->current_cat) {
            if ($hierarchical) {
                $include = array_merge(
                    $this->cat_ancestors,
                    array($this->current_cat->term_id),
                    get_terms(
                        'product_cat',
                        array(
                            'fields'       => 'ids',
                            'parent'       => 0,
                            'hierarchical' => true,
                            'hide_empty'   => false,
                        )
                    ),
                    get_terms(
                        'product_cat',
                        array(
                            'fields'       => 'ids',
                            'parent'       => $this->current_cat->term_id,
                            'hierarchical' => true,
                            'hide_empty'   => false,
                        )
                    )
                );
                // Gather siblings of ancestors.
                if ($this->cat_ancestors) {
                    foreach ($this->cat_ancestors as $ancestor) {
                        $include = array_merge(
                            $include,
                            get_terms(
                                'product_cat',
                                array(
                                    'fields'       => 'ids',
                                    'parent'       => $ancestor,
                                    'hierarchical' => false,
                                    'hide_empty'   => false,
                                )
                            )
                        );
                    }
                }
            } else {
                // Direct children.
                $include = get_terms(
                    'product_cat',
                    array(
                        'fields'       => 'ids',
                        'parent'       => $this->current_cat->term_id,
                        'hierarchical' => true,
                        'hide_empty'   => false,
                    )
                );
            }

            $list_args['include'] = implode(',', $include);

            if (empty($include)) {
                return;
            }
        } elseif ($show_children_only) {
            $list_args['depth']        = 1;
            $list_args['child_of']     = 0;
            $list_args['hierarchical'] = 1;
        }

        $instance['title'] = __('Products', 'ictu');

        $this->widget_start($args, $instance);

        include_once WC()->plugin_path() . '/includes/walkers/class-wc-product-cat-list-walker.php';

        $list_args['walker']                     = new WC_Product_Cat_List_Walker();
        $list_args['title_li']                   = '';
        $list_args['pad_counts']                 = 1;
        $list_args['show_option_none']           = __('No product categories exist.', 'ictu');
        $list_args['current_category']           = ($this->current_cat) ? $this->current_cat->term_id : '';
        $list_args['current_category_ancestors'] = $this->cat_ancestors;
        $list_args['max_depth']                  = $max_depth;

        echo '<ul class="product-categories">';

        wp_list_categories(apply_filters('theme_product_categories_widget_args', $list_args));

        echo '</ul>';

        $this->widget_end($args);
    }
}

/**
 * Register Widgets.
 *
 * @since 2.3.0
 */
add_action('widgets_init',
    function () {
        register_widget('Theme_Widget_Product_Categories');
    }
);