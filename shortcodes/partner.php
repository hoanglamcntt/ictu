<?php
/**
 * Name: partner shortcode
 **/
function ictu_partner_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'partner' => 'partner',
        'title'   => 'partner_title',
        'image'   => 'partner_image',
    ), $atts, 'ictu_partner' );

    $partner = get_theme_option( $atts['partner'] );
    $image   = get_theme_option( $atts['image'] );

    ob_start();
    ?>

    <div class="ovic-partner">
        <div class="partner">
            <?= do_shortcode( '[ictu_heading heading="' . $atts['title'] . '"]' ); ?>
            <div class="ovic-flexbox">
                <?php if ( !empty( $partner ) ) { ?>
                    <?php foreach ( $partner as $item ) { ?>
                        <?php if ( !empty( $item['logo'] ) ) { ?>
                            <a class="item" href="<?= !empty( $item['link'] ) ? esc_url( $item['link'] ) : 'javascript:void(0)' ?>">
                                <?= wp_get_attachment_image( $item['logo'], 'full', false, array( 'alt' => !empty( $item['name'] ) ? esc_attr( $item['name'] ) : '' ) ) ?>
                                <span class="name"><?= esc_attr( $item['name'] ) ?></span>
                            </a>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php if ( !empty( $image ) ) { ?>
            <div class="partner-image">
                <?= wp_get_attachment_image( $image, 'full' ); ?>
            </div>
        <?php } ?>
    </div>
    <!--    <script>-->
    <!--        window.addEventListener("load", function load() {-->
    <!--            /**-->
    <!--             * remove listener, no longer needed-->
    <!--             * */-->
    <!--            window.removeEventListener("load", load, false);-->
    <!---->
    <!--            new Swiper(".ovic-partner", {-->
    <!--                loop: true,-->
    <!--                slidesPerView: 3,-->
    <!--                spaceBetween: 10,-->
    <!--                speed: 600,-->
    <!--                autoplay: {-->
    <!--                    delay: 3000,-->
    <!--                    disableOnInteraction: false,-->
    <!--                },-->
    <!--                navigation: {-->
    <!--                    nextEl: ".swiper-button-next",-->
    <!--                    prevEl: ".swiper-button-prev",-->
    <!--                },-->
    <!--                breakpoints: {-->
    <!--                    480: {-->
    <!--                        slidesPerView: 4,-->
    <!--                    },-->
    <!--                    768: {-->
    <!--                        slidesPerView: 5,-->
    <!--                        spaceBetween: 20,-->
    <!--                    },-->
    <!--                    1200: {-->
    <!--                        slidesPerView: 6,-->
    <!--                        spaceBetween: 30,-->
    <!--                    },-->
    <!--                },-->
    <!--            });-->
    <!--        });-->
    <!--    </script>-->

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_partner', 'ictu_partner_shortcode' );