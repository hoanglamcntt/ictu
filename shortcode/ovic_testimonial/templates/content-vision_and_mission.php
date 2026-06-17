<?php
/**
 * @var $atts
 * @var $_this
 */
$slide_attrs   = array(
    'cellAlign'       => 'left',
    'autoPlay'        => false,
    'freeScroll'      => false,
    'wrapAround'      => false,
    'prevNextButtons' => true,
    'pageDots'        => false,
    'draggable'       => false,
    'imagesLoaded'    => true,
    'adaptiveHeight'  => false,
    'contain'         => true,
    'fade'            => false,
    'lazyLoad'        => false,
    'percentPosition' => false,
    'arrowShape'      => ['x0' => 15, 'x1' => 60, 'y1' => 50, 'x2' => 65, 'y2' => 45, 'x3' => 25],
);
$slide_configs = json_encode($slide_attrs);
?>
<?php if (!empty($atts['visions_and_missions'])): ?>
    <ul class="ovic-testimonial-block__list ovic-testimonial-block__list-vision-and-mission js_block_carousel" data-configs="<?php echo esc_js($slide_configs) ?>">
        <?php foreach ($atts['visions_and_missions'] as $mission): ?>
            <li class="ovic-testimonial-block__elm ovic-testimonial-block__elm--layout-<?php echo esc_attr($mission['v_and_m_layout']) ?>">
                <?php if ($mission['subject']): ?>
                    <div class="ovic-title-block">
                        <div class="ovic-title-block__inner">
                            <h3 class="ovic-title-block__text"><?php echo $mission['subject']; ?></h3>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="ovic-testimonial-block__banner-contain equal-height-item">
                    <div class="ovic-testimonial-block__banner-content">
                        <?php if (!empty($mission['icon'])): ?>
                            <figure class="ovic-testimonial-block__banner-media">
                                <img src="<?php echo esc_url($mission['icon']['url']) ?>" alt="<?php echo esc_url($mission['icon']['alt']) ?>">
                            </figure>
                        <?php endif; ?>
                        <?php if ($mission['text']): ?>
                            <div class="ovic-testimonial-block__banner-text"><?php echo wp_specialchars_decode($mission['text']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($mission['v_and_m_layout'] === 'two'): ?>
                        <div class="ovic-testimonial-block__banner-content">
                            <?php if (!empty($mission['icon2'])): ?>
                                <figure class="ovic-testimonial-block__banner-media">
                                    <img src="<?php echo esc_url($mission['icon2']['url']) ?>" alt="<?php echo esc_url($mission['icon2']['alt']) ?>">
                                </figure>
                            <?php endif; ?>
                            <?php if ($mission['text2']): ?>
                                <div class="ovic-testimonial-block__banner-text"><?php echo wp_specialchars_decode($mission['text2']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>