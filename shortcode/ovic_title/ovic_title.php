<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Title extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_title';

    public function content($atts, $content = null)
    {
        $color = !empty($atts['color']) ? $atts['color'] : '#333333';
        ob_start() ?>
        <div class="ovic-title-block">
            <?php if ($atts['letters']): ?>
                <div class="ovic-title-block__inner" style="color:<?php echo esc_attr($color); ?>">
                    <h3 class="ovic-title-block__text"><?php echo esc_html($atts['letters']) ?></h3>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}