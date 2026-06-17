<?php
/**
 * @var $atts
 * @var $_this
 */
?>
<article <?php post_class("ovic-posts-block__article ovic-posts-block__article--{$atts['style']}"); ?> >
    <div class="ovic-posts-block__post-head">
        <div class="ovic-posts-block__time">
            <span class="ovic-posts-block__time__month"><?php echo __('tháng', 'ictu'); ?><?php echo get_post_time('m'); ?></span>
            <span class="ovic-posts-block__time_date"><?php echo get_post_time('d'); ?></span>
        </div>
        <?php theme_post_title(); ?>
    </div>
    <div class="ovic-posts-block__excerpt"><?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 32, '...'); ?></div>
</article>