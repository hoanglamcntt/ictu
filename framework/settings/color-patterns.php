<?php defined( 'ABSPATH' ) || exit;
if ( !function_exists( 'theme_enqueue_inline_css' ) ) {
    function theme_enqueue_inline_css()
    {
        $css                    = html_entity_decode( get_theme_option( 'ace_style', '' ) );
        $arr_option             = array(
            array( 'main_color', '#006cb5', '--main-cl', ';' ),
            array( 'main_color_hover', '#f7aa23', '--main-cl-hover', ';' ),
            array( 'text_color', '#333333', '--text-color', ';' ),
        );
        $container              = apply_filters( 'theme_main_container', get_theme_option( 'main_container', '1322' ) );
        $css                    .= 'body{';
        foreach ( $arr_option as $item ) {
            $option = get_theme_option( $item[0], $item[1] );
            if ( '' == $item[1] ) {
                if ( !empty( $option ) ) {
                    $css .= $item[2] . ':' . $option . $item[3];
                }
            } else if ( strtolower( $option ) != strtolower( $item[1] ) ) {
                $css .= $item[2] . ':' . $option . $item[3];
            }
            if ( !empty( $option ) && in_array( $item[0], array( 'main_color', 'main_color_2' ) ) && $option != 'transparent' ) {
                $css .=
                    $item[2] . '-r:' . Masino_Colors::hexToRgb( $option )['r'] . ';' .
                    $item[2] . '-g:' . Masino_Colors::hexToRgb( $option )['g'] . ';' .
                    $item[2] . '-b:' . Masino_Colors::hexToRgb( $option )['b'] . ';' .
                    $item[2] . '-h:' . Masino_Colors::hexToHsl( $option )['h'] . ';' .
                    $item[2] . '-s:' . Masino_Colors::hexToHsl( $option )['s'] . '%;' .
                    $item[2] . '-l:' . Masino_Colors::hexToHsl( $option )['l'] . '%;';
            }
        }
        $css .= '}';
        if ( $container ) {
            $css   .= '
            @media (min-width: 1200px){
                body{
                    --main-container: ' . $container . 'px;
                }
            }
            ';
        }

        $css = preg_replace( '/\s+/', ' ', $css );
        wp_add_inline_style( 'theme-main', apply_filters( 'theme_custom_inline_css', $css ) );
    }

    add_action( 'wp_enqueue_scripts', 'theme_enqueue_inline_css', 999 );
}