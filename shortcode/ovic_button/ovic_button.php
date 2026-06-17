<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Button extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_button';

    public function content($atts, $content = null)
    {
        $classes = array('wrap-ovic-button', "wrap-ovic-button--{$atts['style']}");
        $href    = $atts['href'] ? $atts['href'] : '#';
        ob_start(); ?>
        <div class="<?php echo implode(' ', $classes) ?>">
            <a class="btn ovic-button__btn" href="<?php echo esc_url($href) ?>"><?php echo esc_html($atts['text']) ?></a>
        </div>
        <?php
        return ob_get_clean();
    }
}