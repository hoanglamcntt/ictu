<?php
/**
 * Name: vision shortcode
 **/
function ictu_vision_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'vision' => 'vision',
    ), $atts, 'ictu_vision' );

    $vision = get_theme_option( $atts['vision'] );

    ob_start();
    ?>

    <div class="ovic-vision ovic-flexbox">
        <?php if ( !empty( $vision ) ) { ?>
            <?php foreach ( $vision as $item ) { ?>
                <div class="item">
                    <div class="thumb">
                        <?php if ( !empty( $item['image'] ) ) { ?>
                            <?= wp_get_attachment_image( $item['image'], 'full' ) ?>
                        <?php } ?>
                        <?php if ( !empty( $item['text'] ) ) { ?>
                            <ul class="content">
                                <?php foreach ( $item['text'] as $text ) { ?>
                                    <li><?= esc_html( $text['text'] ) ?></li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </div>
                    <h3 class="title"><?= !empty( $item['title'] ) ? esc_html( $item['title'] ) : '' ?></h3>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_vision', 'ictu_vision_shortcode' );