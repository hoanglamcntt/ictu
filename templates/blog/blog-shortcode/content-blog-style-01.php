<div class="post--inner">
    <div class="post--thumb-wrap">
        <?php
        if (!empty($atts['hover_animation']))
            theme_post_thumbnail( 370, 270, true, 'elementor-animation-'.$atts['hover_animation']);
        else
            theme_post_thumbnail( 370, 270, true);
        ?>
        <div class="post--time">
            <span class="day"><?php echo get_post_time('d');?></span>
            <span class="month"><?php echo get_post_time('M');?></span>
        </div>
    </div>
    <div class="post-info equal-elem">
		<?php theme_post_title(); ?>
        <div class="post-meta">
            <a class="post--author" href="<?php echo theme_post_link( 'auth' ); ?>">
                <span class="post--author-label"><?php esc_attr_e('Posted By:'); ?></span>&nbsp;<span class="post--author-name"><?php the_author(); ?></span>
            </a>
            <span class="post--comment">
                <span class="bio-icon14"></span>
                <span class="post--comment-count"><?php echo number_format_i18n(get_comments_number());?></span>
            </span>
        </div>
        <div class="post--footer">
            <div class="post--excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 12, '...'); ?></div>
            <a href="<?php echo theme_post_link(); ?>" class="post--readmore"><?php echo esc_html__( 'Continue Reading', 'ictu' ); ?></a>
        </div>
    </div>

</div>