<div class="post--inner">
    <div class="post--thumb-wrap">
        <?php
        if (!empty($atts['hover_animation']))
            theme_post_thumbnail( 400, 545, true, 'elementor-animation-'.$atts['hover_animation']);
        else
            theme_post_thumbnail( 400, 545, true);
        ?>
    </div>
    <div class="post-info">
		<?php theme_post_title();?>
        <div class="post-meta">
            <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
                <?php echo get_the_date( 'M d, Y' ); ?>
            </a>
            <a class="post--author" href="<?php echo theme_post_link( 'auth' ); ?>">
                <span class="post--author-label"><?php esc_attr_e('by'); ?></span>&nbsp;<span class="post--author-name"><?php the_author(); ?></span>
            </a>
        </div>
        <div class="post--excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 18, '...'); ?></div>
        <div class="post--footer">
            <a href="<?php echo theme_post_link(); ?>" class="post--readmore"><?php echo esc_html__( 'Read more', 'ictu' ); ?></a>
        </div>
    </div>
</div>