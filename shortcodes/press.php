<?php
/**
 * Name: press shortcode
 **/
function ictu_press_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'post_type'      => 'baochi',
        'post_status'    => 'publish',
        'posts_per_page' => 8,
        'paged'          => 1,
        'meta_key'       => 'custom_baochi_order',
        'orderby'        => [
            'meta_value_num' => 'ASC',
            'date'           => 'DESC',
        ],
    ), $atts, 'ictu_press' );

    $custom_query = new WP_Query( $atts );

    ob_start();
    ?>

    <div class="ovic-baochi">
        <?php if ( $custom_query->have_posts() ) : ?>
            <div class="ovic-document-list style-02">
                <?php while ( $custom_query->have_posts() ) : ?>
                    <?php $custom_query->the_post(); ?>
                    <?php
                    $baochi_order = get_post_meta( get_the_ID(), 'custom_baochi_order', true );
                    $order        = !empty( $baochi_order ) ? $baochi_order : 1;
                    $categories   = get_the_terms( get_the_ID(), 'loaibaochi' );
                    $baochi_link  = get_post_meta( get_the_ID(), 'custom_baochi_link', true );
                    if ( !empty( $baochi_link ) ) {
                        $link   = $baochi_link;
                        $target = '_blank';
                    } else {
                        $link   = '#';
                        $target = '_self';
                    }
                    ?>
                    <article <?php post_class( 'document-item style-02' ); ?> data-order="<?php echo esc_attr( $order ) ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumb">
                                <a href="<?php echo esc_url( $link ) ?>" target="<?php echo esc_attr( $target ) ?>" class="thumb-link">
                                    <?php
                                    $thumb = theme_resize_image( get_post_thumbnail_id(), 640, 360, true, true );
                                    echo wp_specialchars_decode( $thumb['img'] );
                                    ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="post-info">
                            <?php if ( !empty( $categories ) && !is_wp_error( $categories ) && !empty( $categories[0] ) ) : ?>
                                <div class="post-category">
                                    <?php echo !empty( $categories[0]->name ) ? esc_html( $categories[0]->name ) : '' ?>
                                </div>
                            <?php endif; ?>
                            <h2 class="post-title"><a href="<?php echo esc_url( $link ) ?>" target="<?php echo esc_attr( $target ) ?>"><?php echo get_the_title() ?></a></h2>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_press', 'ictu_press_shortcode' );