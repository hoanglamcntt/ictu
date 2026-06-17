<?php
/**
 * Name: banner shortcode
 **/
function ictu_banner_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'banner' => 'banner',
    ), $atts, 'ictu_banner' );

    $banner = get_theme_option( $atts['banner'] );

    ob_start();
    ?>

    <div class="ovic-banner ovic-flexbox">
        <?php if ( !empty( $banner ) ) { ?>
            <?php foreach ( $banner as $item ) { ?>
                <?php if ( !empty( $item['image'] ) ) { ?>
                    <a class="item" href="<?= !empty( $item['link'] ) ? esc_url($item['link']) : '#' ?>">
                        <?= wp_get_attachment_image( $item['image'], 'full' ) ?>
                    </a>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_banner', 'ictu_banner_shortcode' );