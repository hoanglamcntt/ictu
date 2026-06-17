<?php
/**
 * @var $atts
 * @var $_this
 */
?>
<?php if (!empty($atts['list'])): ?>
    <ul class="ovic-banner-block__list">
        <?php foreach ($atts['list'] as $banner): ?>
            <?php $link = $banner['link'] ? $banner['link'] : '#' ?>
            <li class="ovic-banner-block__elm">
                <div class="ovic-banner-block__content">
                    <?php if (!empty($banner['image'])): ?>
                        <div class="ovic-banner-block__content__head">
                            <a class="ovic-banner-block__banner-link" href="<?php echo esc_url($link) ?>">
                                <figure>
                                    <img src="<?php echo esc_url($banner['image']['url']) ?>" alt="<?php echo esc_url($banner['image']['alt']) ?>">
                                </figure>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="ovic-banner-block__content__body">
                        <h4 class="ovic-banner-block__banner-name">
                            <a class="ovic-banner-block__banner-link" href="<?php echo esc_url($link) ?>"><?php echo wp_specialchars_decode($banner['text']) ?></a>
                        </h4>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
