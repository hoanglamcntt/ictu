<?php
/**
 * Name: heading shortcode
 **/
function ictu_heading_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'style'   => 'style-01',
        'heading' => '',
    ), $atts, 'ictu_heading' );

    $heading = get_theme_option( $atts['heading'] );

    ob_start();
    ?>

    <div class="ovic-heading">
        <?php if ( !empty( $heading ) ) { ?>
            <h2 class="heading"><?= esc_html( $heading ); ?></h2>
        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_heading', 'ictu_heading_shortcode' );