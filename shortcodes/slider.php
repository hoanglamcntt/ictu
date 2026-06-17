<?php
/**
 * Name: Slider Shortcode
 **/
function ictu_slider_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'slideshow'     => 'slideshow',
        'swiper_effect' => 'swiper_creative3',
    ), $atts, 'ictu_slider' );

    $slideshow = get_theme_option( $atts['slideshow'] );

    ob_start();
    ?>

    <div class="ovic-slider">
        <?php if ( !empty( $slideshow ) ) { ?>
            <div class="swiper <?php echo esc_attr( $atts['swiper_effect'] ) ?>">
                <div class="swiper-wrapper">
                    <?php foreach ( $slideshow as $key => $item ) {
                        if ( !empty( $item['desktop'] ) ) {
                            $image = wp_get_attachment_image_url( $item['desktop'], 'full' );
                            if ( wp_is_mobile() && !empty( $item['mobile'] ) ) {
                                $image = wp_get_attachment_image_url( $item['mobile'], 'full' );
                            }
                            ?>
                            <div class="swiper-slide">
                                <div class="item item-<?php echo esc_attr( $key ) ?>">
                                    <img src="<?= esc_url( $image ) ?>" loading="lazy" alt="">
                                </div>
                            </div>
                            <?php
                        }
                    } ?>
                </div>
                <!--                    <div class="swiper-button-prev"></div>-->
                <!--                    <div class="swiper-button-next"></div>-->
                <div class="swiper-pagination"></div>
            </div>
        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_slider', 'ictu_slider_shortcode' );