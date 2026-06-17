<div class="post--inner">
	<?php
        if (!empty($atts['hover_animation']))
            theme_post_thumbnail( 100, 73, true, 'elementor-animation-'.$atts['hover_animation']);
        else
            theme_post_thumbnail( 100, 73, true);
    ?>
    <div class="post-info">
		<?php theme_post_title(); ?>
        <div class="post-meta">
            <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
				<?php echo get_the_date( 'M d, Y' ); ?>
            </a>
            <a href="<?php echo theme_post_link( 'date' ); ?>#comments" class="comment">
                <?php
                $approved_comments = get_comments_number();
                echo sprintf(_n( '%s '.__('comment', 'ictu'), '%s '.__('comments', 'ictu'), $approved_comments ), number_format_i18n($approved_comments));
                ?>
            </a>
        </div>
    </div>
</div>