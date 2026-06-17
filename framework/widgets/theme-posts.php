<?php
defined( 'ABSPATH' ) || exit;

/**
 * Theme support system widget class.
 *
 * @extends WC_Widget
 */
if ( !class_exists( 'Theme_Widget_Posts' ) ) {
    class Theme_Widget_Posts extends OVIC_Widget
    {

        public function __construct()
        {
            $this->widget_cssclass    = 'theme-widget theme-widget-posts';
            $this->widget_description = __( 'Add a post list', 'ictu' );
            $this->widget_id          = 'theme-widget-posts';
            $this->widget_name        = __( 'Theme: Posts', 'ictu' );
            $this->settings           = [
                'type'           => array(
                    'type'    => 'select',
                    'title'   => __( 'Type', 'ictu' ),
                    'options' => [
                        'recent'   => __( 'Recent Posts', 'ictu' ),
                        'featured' => __( 'Featured Posts', 'ictu' ),
                        'related'  => __( 'Related Posts', 'ictu' ),
                    ],
                ),
                'title'          => array(
                    'type'  => 'text',
                    'title' => __( 'Title', 'ictu' )
                ),
                'posts_per_page' => array(
                    'type'    => 'text',
                    'title'   => __( 'Max Post Number', 'ictu' ),
                    'default' => '10'
                )
            ];
            parent::__construct();
        }

        /**
         * Output widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Widget instance.
         * @see WP_Widget
         */
        public function widget( $args, $instance )
        {
            $posts_per_page = is_numeric( $instance['posts_per_page'] ) && (int)$instance['posts_per_page'] >= 0 ? (int)$instance['posts_per_page'] : 5;
            $query_var      = array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'orderby'        => 'publish_date',
                'order'          => 'DESC',
                'posts_per_page' => $posts_per_page
            );

            if ( $instance['type'] == 'featured' ) {
                $query_var['meta_query'] = [
                    [ 'key' => '_is_feature', 'value' => '1', 'compare' => '=' ]
                ];
            }

            if ( $instance['type'] == 'related' ) {
                $post_categories = get_the_category();
                if ( !empty( $post_categories ) ) {
                    $cat_ids = array();
                    foreach ( $post_categories as $c ) {
                        $cat_ids[] = $c->term_id;
                    }
                    $query_var['category__in'] = $cat_ids;
                }
            }

            $posts = get_posts( $query_var );

            if ( empty( $posts ) ) {
                return;
            }

            $this->widget_start( $args, $instance );
            ob_start();
            ?>
            <ul class="blog-widget">
                <?php foreach ( $posts as $post ): ?>
                    <li class="blog-item">
                        <?= theme_post_item_thumbnail( $post->ID, 200, 168 ); ?>
                        <div class="post-info">
                            <span class="post-date"><span class="icon fa fa-calendar"></span><?= get_post_time( 'd/m/Y', false, $post ); ?></span>
                            <h4 class="post-title">
                                <a href="<?php echo esc_url( get_permalink( $post->ID ) ) ?>"><?= esc_html( get_the_title( $post->ID ) ) ?></a>
                            </h4>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php
            echo ob_get_clean();
            $this->widget_end( $args );
        }
    }

    /**
     * Register Widgets.
     *
     * @since 2.3.0
     */
    add_action( 'widgets_init',
        function () {
            register_widget( 'Theme_Widget_Posts' );
        }
    );
}