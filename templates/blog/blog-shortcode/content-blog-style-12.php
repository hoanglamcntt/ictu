<div class="post--inner">
    <div class="post--thumb-wrap">
        <?php
        if (!empty($atts['hover_animation']))
            theme_post_thumbnail( 390, 260, true, 'elementor-animation-'.$atts['hover_animation']);
        else
            theme_post_thumbnail( 390, 260, true);
        ?>
    </div>
    <div class="post-info">
		<?php
            theme_post_category();
            theme_post_title();
        ?>
        <div class="post-meta">
            <a class="post--author" href="<?php echo theme_post_link( 'auth' ); ?>">
                <span class="post--author-label"><?php esc_attr_e('by'); ?></span>&nbsp;<span class="post--author-name"><?php the_author(); ?></span>
            </a>
            <span class="post--comment">
                <?php
                $approved_comments = get_comments_number();
                echo sprintf(_n( '%s '.__('comment', 'ictu'), '%s '.__('comments', 'ictu'), $approved_comments ), number_format_i18n($approved_comments));
                ?>
            </span>
        </div>
    </div>
</div>