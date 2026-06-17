<div class="post--inner">
    <div class="post--thumb-wrap">
        <?php
        if (!empty($atts['hover_animation']))
            theme_post_thumbnail( 380, 250, true, 'elementor-animation-'.$atts['hover_animation']);
        else
            theme_post_thumbnail( 380, 250, true);
        ?>
        <div class="post--time">
            <span class="day"><?php echo get_post_time('d');?></span>
            <span class="month"><?php echo get_post_time('M');?></span>
        </div>
    </div>
    <div class="post-info equal-elem">
		<?php
            theme_post_category();
            theme_post_title();
        ?>
        <div class="post-meta">
            <a class="post--author" href="<?php echo theme_post_link( 'auth' ); ?>">
                <span class="post--author-label"><?php esc_attr_e('Post by:'); ?></span> <span class="post--author-name"><?php the_author(); ?></span>
            </a>
        </div>
    </div>
</div>