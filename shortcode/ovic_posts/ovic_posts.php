<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Posts extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_posts';

    public function content($atts, $content = null)
    {
        $css_class     = array(
            'ovic-posts-block',
            "ovic-posts-block--{$atts['style']}",
        );
        $ul_classes    = array('ovic-posts-block__list');
        $slide_configs = '';
        $button_link   = $atts['button_link'] ? $atts['button_link'] : '#';
        $button_label  = $atts['button_label'];
        if ($atts['style'] === 'style-03') {
            $ul_classes[]  = 'js_block_carousel wrap-ovic-slide-block';
            $slide_attrs   = array(
                'autoPlay'        => false,
                'freeScroll'      => false,
                'cellAlign'       => 'left',
                'wrapAround'      => false,
                'prevNextButtons' => (bool)$atts['prevNextButtons'],
                'pageDots'        => (bool)$atts['pageDots'],
                'draggable'       => true,
                'imagesLoaded'    => true,
                'adaptiveHeight'  => false,
                'contain'         => true,
                'fade'            => false,
                'percentPosition' => false,
                'lazyLoad'        => false,
                'arrowShape'      => ['x0' => 15, 'x1' => 60, 'y1' => 50, 'x2' => 65, 'y2' => 45, 'x3' => 25],
            );
            $slide_configs = json_encode($slide_attrs);
        }
        $query = new WP_Query(theme_shortcode_posts_query($atts));
        ob_start(); ?>
        <div class="<?php echo implode(' ', $css_class); ?>">
            <?php if ($query->have_posts()) : ?>
                <ul class="<?php echo implode(' ', $ul_classes); ?>" data-configs="<?php echo esc_js($slide_configs) ?>">
                    <?php while ($query->have_posts()) : ?>
                        <?php $query->the_post(); ?>
                        <li class="ovic-posts-block__elm">
                            <?php $this->get_template("templates/{$atts['style']}.php", array('atts' => $atts, '_this' => $this,)); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
            <?php if ($atts['style'] === 'style-03' && $button_label): ?>
                <div class="ovic-posts-block__buttons">
                    <a class="btn ovic-posts-block__btn" href="<?php echo esc_url($button_link) ?>"><?php echo esc_html($button_label) ?></a>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}