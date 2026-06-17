<div class="post-inner">
	<?php theme_post_thumbnail( 380, 250 ); ?>
    <div class="post-info equal-elem">
        <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
            <span class="day"><?php echo get_the_date( 'd' ); ?></span>
            <span class="month"><?php echo get_the_date( 'M' ); ?></span>
        </a>
		<?php
		theme_get_term_list();
		theme_post_title();
		?>
        <div class="post-meta">
			<?php theme_post_author(
				esc_html__( 'Post by', 'ictu' )
			); ?>
        </div>
    </div>
</div>