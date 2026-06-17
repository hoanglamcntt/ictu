<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Trainingprogram extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_trainingprogram';

    public function content($atts, $content = null)
    {
        $this->get_template("templates/content-{$atts['type']}.php", array('atts' => $atts, '_this' => $this,));
    }
}