<div class="post--inner">
    <div class="post--thumb-wrap">
        <?php
        if (!empty($atts['hover_animation']))
            theme_post_thumbnail( 502, 335, true, 'elementor-animation-'.$atts['hover_animation']);
        else
            theme_post_thumbnail( 502, 335, true);
        ?>
        <div class="post--time">
            <span class="day"><?php echo get_post_time('d');?></span>
            <span class="month"><?php echo get_post_time('M');?></span>
        </div>
    </div>
    <div class="post-info equal-elem">
		<?php theme_post_title(); ?>
        <div class="post--footer">
            <div class="post--excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 18, '...'); ?></div>
        </div>
    </div>
</div>