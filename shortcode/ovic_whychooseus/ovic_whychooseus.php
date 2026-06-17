<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Whychooseus extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_whychooseus';

    public function content($atts, $content = null)
    {
        ob_start() ?>
        <div class="ovic-why-choose-us-block --wcs-bg-color">
            <?php if ($atts['block_title']): ?>
                <div class="ovic-title-block ovic-why-choose-us-block__head">
                    <div class="ovic-title-block__inner --wcs-text-color">
                        <h3 class="ovic-title-block__text"><?php echo esc_html($atts['block_title']) ?></h3>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($atts['list'])): ?>
                <div class="ovic-why-choose-us-block__body">
                    <div class="container">
                        <div class="ovic-why-choose-us-block__container">
                            <ul class="ovic-why-choose-us-block__list">
                                <?php foreach ($atts['list'] as $banner): ?>
                                    <li class="ovic-why-choose-us-block__elm">
                                        <div class="ovic-why-choose-us-block__content ovic-why-choose-us-block__content--<?php echo esc_attr($banner['suffix_style']) ?>">
                                            <b class="ovic-why-choose-us-block__content__head">
                                                <span class="ovic-why-choose-us-block__number js_counter_up --wcs-text-color"><?php echo esc_html($banner['number']) ?></span>
                                                <?php if ($banner['suffix_style'] === 'custom' && $banner['suffix']): ?>
                                                    <span class="ovic-why-choose-us-block__suffix --wcs-text-color"><?php echo wp_specialchars_decode($banner['suffix']) ?></span>
                                                <?php endif; ?>
                                            </b>
                                            <?php if ($banner['desc']): ?>
                                                <p class="ovic-why-choose-us-block__content__desc --wcs-text-color"><?php echo wp_specialchars_decode($banner['desc']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}