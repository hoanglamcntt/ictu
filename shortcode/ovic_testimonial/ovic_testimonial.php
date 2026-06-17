<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Testimonial extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_testimonial';

    public function content($atts, $content = null)
    {
        $classes = array('ovic-testimonial-block', "ovic-testimonial-block--{$atts['style']}");
        ob_start() ?>
        <div class="<?php echo implode(' ', $classes) ?>">
            <?php $this->get_template("templates/content-{$atts['style']}.php", array('atts' => $atts, '_this' => $this,)); ?>
        </div>
        <?php
        return ob_get_clean();
    }
}