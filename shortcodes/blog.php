<?php
/**
 * Name: blog shortcode
 **/
function ictu_blog_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'classes'        => '',
        'style'          => 'style-01',
        'list_style'     => 'flex', // flex | grid | owl
        'target'         => 'recent_post', // recent_post | post
        'ids'            => '',
        'not_ids'        => '',
        'category'       => '',
        'limit'          => 6,
        'orderby'        => '', // date | ID | title | post__in
        'order'          => '', // DESC | ASC
        'width'          => 600,
        'height'         => 400,
        'excerpt_number' => 32,
    ), $atts, 'ictu_blog' );

    $classes = 'ovic-blog ' . $atts['classes'];

    $list_class = 'ovic-' . esc_attr( $atts['list_style'] ) . 'box';
//    if ( $atts['list_style'] == 'owl' ) {
//        $list_class .= ' swiper ovic-swiper';
//    }

    $post_item_class = array( 'blog-item', $atts['style'] );
    if ( $atts['style'] == 'style-04' ) {
        $post_item_class[] = 'style-03';
    }

    if ( !empty( $atts['ids'] ) ) {
        $atts['target'] = 'post';
    }
    $query = new WP_Query( theme_shortcode_posts_query( $atts ) );

    ob_start();
    ?>

    <div class="<?= esc_attr( $classes ) ?>">
        <?php if ( $query->have_posts() ) : ?>
            <div class="<?= esc_attr( $list_class ); ?>">
                <!--                --><?php //if ( $atts['list_style'] == 'ow;' ) echo '<div class="swiper-wrapper">' ?>
                <?php while ( $query->have_posts() ) :
                    $query->the_post();
//                    $index = $query->current_post;
//                    if ($index % 4 == 0 || $index % 4 == 1) {
//                        $post_item_class[] = 'bigger';
//                    }
                    ?>
                    <article <?php post_class( $post_item_class ); ?>>
                        <div class="post-inner">
                            <?php switch ( $atts['style'] ) {
                                case "style-05": ?>
                                    <div class="post-info">
                                        <div class="post-date">
                                            <span class="icon fa fa-calendar"></span>
                                            <?php echo get_the_date(); ?>
                                        </div>
                                        <?php theme_post_title(); ?>
                                    </div>
                                    <?php break;
                                case "style-04": ?>
                                    <?php theme_post_thumbnail( $atts['width'], $atts['height'] ); ?>
                                    <div class="post-info">
                                        <?php theme_post_title(); ?>
                                        <?php theme_post_excerpt( $atts['excerpt_number'] ); ?>
                                        <div class="post-date">
                                            <span class="icon fa fa-calendar"></span>
                                            <?php echo get_the_date(); ?>
                                        </div>
                                    </div>
                                    <?php break;
                                case "style-03": ?>
                                    <?php theme_post_thumbnail( $atts['width'], $atts['height'] ); ?>
                                    <div class="post-info">
                                        <?php theme_post_title(); ?>
                                        <div class="post-date">
                                            <span class="icon fa fa-calendar"></span>
                                            <?php echo get_the_date(); ?>
                                        </div>
                                    </div>
                                    <?php break;
                                case "style-02": ?>
                                    <div class="post-date">
                                        <span class="month"><?= __( 'tháng', 'ictu' ); ?> <?= get_post_time( 'm' ); ?></span>
                                        <span class="date"><?= get_post_time( 'd' ); ?></span>
                                    </div>
                                    <?php theme_post_title(); ?>
                                    <?php theme_post_excerpt( $atts['excerpt_number'] ); ?>
                                    <?php break;
                                default: ?>
                                    <?php theme_post_thumbnail( $atts['width'], $atts['height'] ); ?>
                                    <?php theme_post_title(); ?>
                                <?php
                            } ?>
                        </div>
                    </article>
                <?php endwhile; ?>
                <!--                --><?php //if ( $atts['list_style'] == 'ow;' ) echo '</div>' ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_blog', 'ictu_blog_shortcode' );