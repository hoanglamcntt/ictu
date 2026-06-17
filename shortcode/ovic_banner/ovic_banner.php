<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Banner extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_banner';

    public function content($atts, $content = null)
    {
        $classes = array('ovic-banner-block', "ovic-banner-block--{$atts['style']}");
        ob_start() ?>
        <div class="<?php echo implode(' ', $classes) ?>">
            <?php $this->get_template("templates/banner-{$atts['style']}.php", array('atts' => $atts, '_this' => $this,)); ?>
        </div>
        <?php
        return ob_get_clean();
    }
}