<?php
/**
 * @var $atts
 * @var $_this
 */
?>
<div class="ovic-training-program-section" data-showOn="scrollTo">
    <div class="ovic-training-program-section__head">
        <?php if ($atts['image']): ?>
            <figure>
                <img src="<?php echo esc_url($atts['image']['url']) ?>" alt="<?php echo esc_url($atts['image']['alt']) ?>">
            </figure>
        <?php endif; ?>
        <?php if (!empty($atts['fields'])): ?>
            <ul class="ovic-training-program__list">
                <?php foreach ($atts['fields'] as $field): ?>
                    <li class="ovic-training-program__elm intro-y">
                        <a href="<?php echo esc_url($field['link']) ?>" class="ovic-training-program__link"><?php echo esc_html($field['name']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php if ($atts['title']): ?>
        <h4 class="ovic-training-program-section__title"><?php echo esc_html($atts['title']) ?></h4>
    <?php endif; ?>
</div>