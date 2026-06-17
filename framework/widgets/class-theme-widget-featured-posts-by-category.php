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
                    'layout'         => array(
                        'id'      => 'layout',
                        'type'    => 'select',
                        'title'   => __('Layout', 'ictu'),
                        'options' => array(
                            'layout-01' => __('Layout 01', 'ictu'),
                            'layout-02' => __('Layout 02', 'ictu')
                        ),
                        'default' => 'layout-01'
                    ),
                    'category'       => array(
                        'id'         => 'category',
                        'type'       => 'select',
                        'title'      => __('Category', 'ictu'),
                        'options'    => 'categories',
                        'query_args' => ['categories'],
                        'default'    => ''
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
            $this->widget_start($args, $instance);
            $posts = [];
            if ($instance['category']) {
                $query_var = array(
                    'post_type'      => 'post',
                    'meta_query'     => array(
                        array('key' => '_is_feature', 'value' => '1', 'compare' => '=')
                    ),
                    'cat'            => $instance['category'],
                    'posts_per_page' => is_numeric($instance['posts_per_page']) && (int)$instance['posts_per_page'] >= 0 ? (int)$instance['posts_per_page'] : -1
                );
                $posts     = get_posts($query_var);
            }
            $inner_classes = "theme-widget-featured-posts__inner theme-widget-featured-posts__inner--{$instance['layout']}";
            $content_inner = "theme-widget-featured-posts__post-wrap theme-widget-featured-posts__post-wrap--{$instance['layout']}";
            ob_start();
            ?>
            <div class="<?php echo esc_attr($inner_classes) ?>">
                <h3 class="theme-widget-featured-posts__title"><?php esc_html_e('Hot News', 'ictu'); ?></h3>
                <div class="theme-widget-featured-posts__content">
                    <?php if (!empty($posts)): ?>
                        <ul class="theme-widget-featured-posts__list">
                            <?php if ($instance['layout'] === 'layout-01'): ?>
                                <?php foreach ($posts as $post): ?>
                                    <?php $attachment_id = get_post_thumbnail_id($post->ID); ?>
                                    <li class="theme-widget-featured-posts__post-elm">
                                        <a href="<?php echo esc_url(get_permalink($post->ID)) ?>" title="<?php echo esc_attr(get_the_title($post->ID)) ?>">
                                            <div class="<?php echo esc_attr($content_inner) ?>">
                                                <figure class="theme-widget-featured-posts__wrap-media">
                                                    <?php if ($attachment_id): ?>
                                                        <?php echo wp_get_attachment_image($attachment_id, [100, 100], false, array('class' => 'theme-widget-featured-posts__thumbnail')) ?>
                                                    <?php else: ?>
                                                        <img src="https://via.placeholder.com/300" class="theme-widget-featured-posts__thumbnail" alt="" width="300" height="300">
                                                    <?php endif; ?>
                                                </figure>
                                                <div class="theme-widget-featured-posts__wrap-info">
                                                    <h4 class="theme-widget-featured-posts__post-title"><?php echo esc_attr(get_the_title($post->ID)) ?></h4>
                                                    <span class="theme-widget-featured-posts__post-date"><i class="glyphicon glyphicon-time"></i><?php echo esc_html(get_post_modified_time('d/m/Y', false, $post)); ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php foreach ($posts as $post): ?>
                                    <?php $attachment_id = get_post_thumbnail_id($post->ID); ?>
                                    <li class="theme-widget-featured-posts__post-elm">
                                        <a href="<?php echo esc_url(get_permalink($post->ID)) ?>" title="<?php echo esc_attr(get_the_title($post->ID)) ?>">
                                            <div class="<?php echo esc_attr($content_inner) ?>">
                                                <figure class="theme-widget-featured-posts__wrap-media">
                                                    <?php if ($attachment_id): ?>
                                                        <?php echo wp_get_attachment_image($attachment_id, [100, 100], false, array('class' => 'theme-widget-featured-posts__thumbnail')) ?>
                                                    <?php else: ?>
                                                        <img src="https://via.placeholder.com/300" class="theme-widget-featured-posts__thumbnail" alt="" width="300" height="300">
                                                    <?php endif; ?>
                                                </figure>
                                                <div class="theme-widget-featured-posts__wrap-info">
                                                    <h4 class="theme-widget-featured-posts__post-title"><?php echo esc_attr(get_the_title($post->ID)) ?></h4>
                                                    <p class="theme-widget-featured-posts__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt($post->ID), 8, '...')) ?></p>
<!--                                                    <span class="theme-widget-featured-posts__post-date"><i class="glyphicon glyphicon-time"></i>--><?php //echo esc_html(get_post_modified_time('d/m/Y', false, $post)); ?><!--</span>-->
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
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