<?php defined('ABSPATH') || exit;
if (!function_exists('theme_enqueue_inline_css')) {
    function theme_enqueue_inline_css()
    {
        $css                    = get_theme_option('ace_style', '');
        $main_color             = apply_filters('theme_main_color', get_theme_option('main_color', '#006cb5'));
        $main_color_hover       = apply_filters('theme_main_color_hover', get_theme_option('main_color_hover', '#f7aa23'));
        $text_color             = apply_filters('theme_text_color', get_theme_option('text_color', '#333333'));
        $container              = apply_filters('theme_main_container', get_theme_option('main_container', '1322'));
        $container_with_padding = $container ? $container + 30 : 0;
        $css                    .= '
        body{ 
            --main-color: ' . $main_color . '; 
            --main-color-hover: ' . $main_color_hover . '; 
            --text-color: ' . $text_color . '; 
            --container-width: ' . $container . 'px; 
            --container-with-padding: ' . $container_with_padding . 'px; 
        } ';
        if ($container_with_padding) {
            $media = $container_with_padding < 1200 ? 1200 : $container_with_padding;
            $css   .= '
            @media (min-width: ' . $media . 'px){
                body{
                    --main-container: ' . $container . 'px;
                }
                .elementor-section-stretched.elementor-section-boxed:not(.elementor-has-width) > .elementor-container,
                .site > .elementor > .elementor-inner,
                .container{
                    width: ' . $container_with_padding . 'px;
                }
                .box-nav-vertical .vertical-menu > .menu-item > .megamenu{
                    max-width: ' . ($container - 210) . 'px !important;
                }
            }
            ';
        }

        $css = preg_replace('/\s+/', ' ', $css);
        wp_add_inline_style('theme-main', apply_filters('theme_custom_inline_css', $css, $main_color, $text_color, $container));
    }

    add_action('wp_enqueue_scripts', 'theme_enqueue_inline_css', 999);
}