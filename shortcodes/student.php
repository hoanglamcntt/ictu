<?php
/**
 * Name: student shortcode
 **/
function ictu_student_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'student' => 'student',
        'title'   => 'student_title',
        'gallery' => 'student_gallery',
    ), $atts, 'ictu_student' );

    $student = get_theme_option( $atts['student'] );
    $gallery = get_theme_option( $atts['gallery'] );
    ob_start();
    ?>

    <div class="ovic-student">
        <div class="student">
            <?= do_shortcode( '[ictu_heading heading="' . $atts['title'] . '"]' ); ?>
            <?php if ( !empty( $student ) ) { ?>
                <?php foreach ( $student as $item ) { ?>
                    <?php if ( !empty( $item['title'] ) ) { ?>
                        <a class="item" href="<?= !empty( $item['link'] ) ? esc_url( $item['link'] ) : 'javascript:void(0)' ?>">
                            <div class="thumb">
                                <?php if ( !empty( $item['image'] ) ) {
                                    echo wp_get_attachment_image( $item['image'], 'full' );
                                } elseif ( !empty( $item['icon'] ) ) {
                                    echo '<span class="' . $item['icon'] . '"></span>';
                                } ?>
                            </div>
                            <?= esc_attr( $item['title'] ) ?>
                        </a>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
        <?php if ( !empty( $gallery ) ) { ?>
            <div class="student-gallery swiper swiper_creative3">
                <div class="swiper-wrapper">
                    <?php foreach ( explode( ",", $gallery ) as $item ) { ?>
                        <?php if ( get_post_type( $item ) === 'attachment' ) { ?>
                            <div class="swiper-slide item"><?= wp_get_attachment_image( $item, 'full' ); ?></div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <!--        <div class="swiper-pagination"></div>-->
            </div>
        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_student', 'ictu_student_shortcode' );