<?php defined( 'ABSPATH' ) || exit;

class Shortcode_Ovic_Slide extends Ovic_Addon_Shortcode
{

    public $shortcode = 'ovic_slide';

    public function content( $atts, $content = null )
    {
        $lazy_load = (bool)$atts['lazy_load'];
        $autoPlay  = false;
        if ( $atts['autoPlay'] ) {
            $autoPlay = $atts['autoPlayTime'] > 0 ? $atts['autoPlayTime'] : 3000;
        }
        $slide_attrs   = array(
            'autoPlay'        => $autoPlay,
            'freeScroll'      => (bool)$atts['freeScroll'],
            'wrapAround'      => (bool)$atts['wrapAround'],
            'prevNextButtons' => (bool)$atts['prevNextButtons'],
            'pageDots'        => (bool)$atts['pageDots'],
            'draggable'       => (bool)$atts['draggable'] ? '>1' : false,
            'imagesLoaded'    => true,
            'adaptiveHeight'  => (bool)$atts['adaptiveHeight'],
            'contain'         => true,
            'fade'            => (bool)$atts['fade'],
            'percentPosition' => false,
            'lazyLoad'        => $lazy_load,
            'arrowShape'      => [ 'x0' => 15, 'x1' => 60, 'y1' => 50, 'x2' => 65, 'y2' => 45, 'x3' => 25 ],
        );
        $slide_configs = json_encode( $slide_attrs );
        $class         = array( 'wrap-ovic-slide-block js_block_carousel', "ovic-slide-block--{$atts['layout']}" );
        $is_mobile     = wp_is_mobile();
        ob_start()
        ?>
        <div class="<?php echo implode( ' ', $class ) ?>" data-configs="<?php echo esc_js( $slide_configs ) ?>">
            <?php if ( !empty( $atts['galleries'] ) ): ?>
                <?php foreach ( $atts['galleries'] as $key => $image ): ?>
                    <div class="ovic-slide__item">
                        <?php if ( $lazy_load && $key > 0 ): ?>
                            <?php if ( !$is_mobile && !empty( $image['desktop'] ) ): ?>
                                <a href="<?php echo ( !empty( $image['link'] ) ) ? esc_url( $image['link'] ) : 'javascript:void(0)' ?>">
                                    <img class="ovic-slide__item--desktop" src="<?php echo esc_attr( theme_get_svg_image( 8000, 2709 ) ) ?>" data-flickity-lazyload="<?php echo esc_url( $image['desktop']['url'] ) ?>" alt="<?php echo esc_attr( $image['desktop']['alt'] ) ?>">
                                </a>
                            <?php endif; ?>
                            <?php if ( $is_mobile && !empty( $image['mobile'] ) ): ?>
                                <a href="<?php echo ( !empty( $image['link'] ) ) ? esc_url( $image['link'] ) : 'javascript:void(0)' ?>">
                                    <img class="ovic-slide__item--mobile" src="<?php echo esc_attr( theme_get_svg_image( 4876, 7042 ) ) ?>" data-flickity-lazyload="<?php echo esc_url( $image['mobile']['url'] ) ?>" alt="<?php echo esc_attr( $image['mobile']['alt'] ) ?>">
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ( !$is_mobile && !empty( $image['desktop'] ) ): ?>
                                <a href="<?php echo ( !empty( $image['link'] ) ) ? esc_url( $image['link'] ) : 'javascript:void(0)' ?>">
                                    <img class="ovic-slide__item--desktop" src="<?php echo esc_url( $image['desktop']['url'] ) ?>" alt="<?php echo esc_attr( $image['desktop']['alt'] ) ?>">
                                </a>
                            <?php endif; ?>
                            <?php if ( $is_mobile && !empty( $image['mobile'] ) ): ?>
                                <a href="<?php echo ( !empty( $image['link'] ) ) ? esc_url( $image['link'] ) : 'javascript:void(0)' ?>">
                                    <img class="ovic-slide__item--mobile" src="<?php echo esc_url( $image['mobile']['url'] ) ?>" alt="<?php echo esc_attr( $image['mobile']['alt'] ) ?>">
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}