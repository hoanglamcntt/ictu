<div class="post--inner">
    <div class="post--thumb-wrap">
        <?php
        if (!empty($atts['hover_animation']))
            theme_post_thumbnail( 600, 360, true, 'elementor-animation-'.$atts['hover_animation']);
        else
            theme_post_thumbnail( 600, 360, true);
        ?>
    </div>
    <div class="post-info">
		<?php theme_post_title(); ?>
        <div class="post-meta">
            <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
                <i class="icofont-calendar"></i>
                <span class="text"><?php echo get_the_date( 'M d, Y' ); ?></span>
            </a>
        </div>
        <div class="post--footer">
            <div class="post--excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 18, '...'); ?></div>
            <a href="<?php echo theme_post_link(); ?>" class="post--readmore"><?php echo esc_html__( 'Continue Reading', 'ictu' ); ?></a>
        </div>
    </div>
</div>