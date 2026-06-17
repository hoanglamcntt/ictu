<?php
/**
 * Name: discover shortcode
 **/
function ictu_discover_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'discover' => 'discover',
    ), $atts, 'ictu_discover' );

    $discover = get_theme_option( $atts['discover'] );

    ob_start();
    ?>

    <div class="ovic-discover ovic-gridbox">
        <?php if ( !empty( $discover ) ) { ?>
            <?php foreach ( $discover as $item ) { ?>
                <a href="<?= !empty( $item['link'] ) ? esc_url($item['link']) : '#' ?>" class="item <?= esc_attr( $item['type'] ) . ' ' . esc_attr( $item['size'] ) ?>">
                    <?php if ( !empty( $item['image'] ) ) { ?>
                        <?= wp_get_attachment_image( $item['image'], 'full' ) ?>
                    <?php } ?>
                    <?php if ( $item['type'] == 'facebook' || $item['type'] == 'video' ) {
                        echo '<span>Xem thêm tại</span>';
                    } ?>
                </a>
            <?php } ?>
        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_discover', 'ictu_discover_shortcode' );