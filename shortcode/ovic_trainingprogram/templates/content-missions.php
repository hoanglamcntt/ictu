<?php
/**
 * @var $atts
 * @var $_this
 */
?>
<div class="ovic-vision-and-missions-section" data-showOn="scrollTo">
    <div class="ovic-vision-and-missions-section__head">
        <?php if ($atts['image']): ?>
            <figure>
                <img src="<?php echo esc_url($atts['image']['url']) ?>" alt="<?php echo esc_url($atts['image']['alt']) ?>">
            </figure>
        <?php endif; ?>
        <?php if (!empty($atts['missions'])): ?>
            <ul class="ovic-vision-and-missions__list">
                <?php foreach ($atts['missions'] as $field): ?>
                    <li class="ovic-vision-and-missions__elm intro-y">
                        <span class="ovic-vision-and-missions__text"><?php echo esc_html($field['text']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php if ($atts['title']): ?>
        <h4 class="ovic-vision-and-missions-section__title"><?php echo esc_html($atts['title']) ?></h4>
    <?php endif; ?>
</div>