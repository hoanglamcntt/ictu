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
    'draggable'       => true,
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
<?php if (!empty($atts['testimonials'])): ?>
    <ul class="ovic-testimonial-block__list js_block_carousel" data-configs="<?php echo esc_js($slide_configs) ?>">
        <?php foreach ($atts['testimonials'] as $testimonial): ?>
            <li class="ovic-testimonial-block__elm">
                <div class="ovic-testimonial-block__testimonial__container equal-height-item">
                    <div class="ovic-testimonial-block__person-info">
                        <?php if (!empty($testimonial['avatar'])): ?>
                            <figure class="ovic-testimonial-block__avatar">
                                <img src="<?php echo esc_url($testimonial['avatar']['url']) ?>" alt="<?php echo esc_url($testimonial['avatar']['alt']) ?>">
                            </figure>
                        <?php endif; ?>
                        <?php if ($testimonial['title']): ?>
                            <b class="ovic-testimonial-block__job-title"><?php echo esc_html($testimonial['title']) ?></b>
                        <?php endif; ?>
                        <?php if ($testimonial['name']): ?>
                            <b class="ovic-testimonial-block__person-name"><?php echo esc_html($testimonial['name']) ?></b>
                        <?php endif; ?>
                    </div>
                    <?php if ($testimonial['desc']): ?>
                        <p class="ovic-testimonial-block__testimonial__description"><?php echo esc_html($testimonial['desc']) ?></p>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>