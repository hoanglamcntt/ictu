<?php
/**
 * @var $atts
 * @var $_this
 */
?>
<article <?php post_class("ovic-posts-block__article ovic-posts-block__article--{$atts['style']}"); ?> >
    <?php theme_post_thumbnail(600, 400, true); ?>
    <?php theme_post_title(); ?>
    <div class="ovic-posts-block__time">
        <span class="ovic-posts-block__time__date"><?php echo get_post_time('d/m/Y'); ?></span>
    </div>
</article>