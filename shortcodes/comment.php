<?php
/**
 * Name: comment shortcode
 **/
function ictu_comment_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'comment' => 'comment',
    ), $atts, 'ictu_comment' );

    $comment = get_theme_option( 'comment' );

    ob_start();
    ?>

    <div class="ovic-comment swiper">
        <?php if ( !empty( $comment ) ) { ?>
            <div class="swiper-wrapper">
                <?php foreach ( $comment as $item ) { ?>
                    <div class="item swiper-slide">
                        <div class="inner">
                            <div class="content">
                                <?php if ( !empty( $item['desc'] ) ) { ?>
                                    <div class="desc"><?= esc_html( $item['desc'] ) ?></div>
                                <?php } ?>
                                <?php if ( !empty( $item['name'] ) ) { ?>
                                    <h3 class="name"><?= esc_html( $item['name'] ) ?></h3>
                                <?php } ?>
                                <?php if ( !empty( $item['job'] ) ) { ?>
                                    <p class="job"><?= esc_html( $item['job'] ) ?></p>
                                <?php } ?>
                            </div>
                            <div class="avatar">
                                <?php if ( !empty( $item['avatar'] ) ) { ?>
                                    <?= wp_get_attachment_image( $item['avatar'], 'avatar' ) ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
<!--            <div class="swiper-pagination"></div>-->
        <?php } ?>
    </div>
    <script>
        window.addEventListener("load", function load() {
            /**
             * remove listener, no longer needed
             * */
            window.removeEventListener("load", load, false);

            new Swiper(".ovic-comment", {
                slidesPerView: 1,
                spaceBetween: 15,
                loop: true,
                speed: 1000,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: true,
                },
                effect: "fade",
            });
        });
    </script>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_comment', 'ictu_comment_shortcode' );