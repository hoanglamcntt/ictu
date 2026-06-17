<div class="post--inner">
    <div class="post--thumb-wrap">
        <?php
        if (!empty($atts['hover_animation']))
            theme_post_thumbnail( 680, 376, true, 'elementor-animation-'.$atts['hover_animation']);
        else
            theme_post_thumbnail( 680, 376, true);
        ?>
    </div>
    <div class="post-info equal-elem">
		<?php
            theme_post_category();
            theme_post_title();
        ?>
        <div class="post-meta">
            <a class="post--author" href="<?php echo theme_post_link( 'auth' ); ?>">
                <i class="fa fa-user" aria-hidden="true"></i><span class="post--author-label"><?php esc_attr_e('by:'); ?></span>&nbsp;<span class="post--author-name"><?php the_author(); ?></span>
            </a>
            <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                <?php echo get_the_date( 'M d, Y' ); ?>
            </a>
            <span class="post--comment">
                <i class="fa fa-comment" aria-hidden="true"></i>
                <span class="post--comment-count"><?php echo zeroise(number_format_i18n(get_comments_number()), 2);?></span>
            </span>
        </div>
        <div class="post--excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 18, '...'); ?></div>
        <div class="post--footer">
            <a href="<?php echo theme_post_link(); ?>" class="post--readmore"><?php echo esc_html__( 'Read more', 'ictu' ); ?></a>
            <?php theme_share_social2( get_the_ID() ); ?>
        </div>
    </div>
</div>