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
                <?php
                $author_id = get_the_author_meta( 'ID' );
                ?>
                <?php echo get_avatar( $author_id, 40 ); ?><span class="post--author-name"><?php the_author(); ?></span>
            </a>
            <a href="<?php echo theme_post_link( 'date' ); ?>#comments" class="post--comment">
                <span class="post--comment-count"><?php echo number_format_i18n(get_comments_number());?></span>
                <span class="bio-icon31"></span>
            </a>
            <div class="post--share share-post">
                <a href="javascript:void(0)" class="toggle">
                    <span class="icon fa fa-share-alt"></span>
                </a>
                <?php theme_share_social( get_the_ID() ); ?>
            </div>
        </div>
        <div class="post--footer">
            <a href="<?php echo theme_post_link(); ?>" class="post--readmore"><?php echo esc_html__( 'Continue Reading', 'ictu' ); ?></a>
        </div>
    </div>

</div>