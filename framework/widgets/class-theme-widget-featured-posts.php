<?php
defined('ABSPATH') || exit;

/**
 * Theme support system widget class.
 *
 * @extends WC_Widget
 */
if (!class_exists('Theme_Widget_Featured_Posts')) {
    class Theme_Widget_Featured_Posts extends OVIC_Widget {

        public function __construct()
        {
            $this->widget_cssclass    = 'theme-widget theme-widget-featured-posts';
            $this->widget_description = __('List featured posts in category', 'ictu');
            $this->widget_id          = 'theme-widget-featured-posts';
            $this->widget_name        = __('Theme: Featured Posts', 'ictu');
            $this->settings           = apply_filters(
                'theme-widget-featured-posts-settings',
                array(
                    'title'          => array(
                        'id'    => 'title',
                        'type'  => 'text',
                        'title' => __('Title', 'ictu')
                    ),
                    'posts_per_page' => array(
                        'type'    => 'text',
                        'title'   => __('Max Post Number', 'ictu'),
                        'default' => '10'
                    )
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
            $posts           = [];
            $post_categories = get_the_category();
            if (!empty($post_categories)) {
                $cat_ids        = array();
                $posts_per_page = is_numeric($instance['posts_per_page']) && (int)$instance['posts_per_page'] >= 0 ? (int)$instance['posts_per_page'] : -1;
                foreach ($post_categories as $c) {
                    $cat_ids[] = $c->term_id;
                }
                $query_var = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'orderby'        => 'publish_date',
                    'order'          => 'DESC',
                    'category__in'   => $cat_ids,
                    'meta_query'     => array(
                        array('key' => '_is_feature', 'value' => '1', 'compare' => '=')
                    ),
                    'posts_per_page' => $posts_per_page
                );
                $posts     = get_posts($query_var);
            }
            if (empty($posts)) {
                return;
            }
            $this->widget_start($args, $instance);
            ob_start();
            ?>
            <div class="theme-widget-related-posts__content">
                <?php if (!empty($posts)): ?>
                    <ul class="theme-widget-related-posts__list">
                        <?php foreach ($posts as $post): ?>
                            <li class="theme-widget-related-posts__post-elm">
                                <span class="theme-widget-featured-posts__post-date"><?php echo get_post_time('d/m/Y', false, $post); ?></span>
                                <h4 class="theme-widget-featured-posts__post-title">
                                    <a href="<?php echo esc_url(get_permalink($post->ID)) ?>" title="<?php echo esc_attr(get_the_title($post->ID)) ?>"><?php echo esc_html(get_the_title($post->ID)) ?></a>
                                </h4>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php
            echo apply_filters('theme-widget-featured-posts', ob_get_clean(), $instance);
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
            register_widget('Theme_Widget_Featured_Posts');
        }
    );
}