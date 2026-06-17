<div class="post-inner">
	<?php theme_post_thumbnail( 430, 300 ); ?>
    <div class="post-info">
		<?php
		theme_get_term_list();
		theme_post_title();
		?>
        <div class="post-meta">
            <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
                <?php echo get_the_date( 'dS M, Y' ); ?>
            </a>
            <?php theme_post_author(
                esc_html__( 'by', 'ictu' )
            ); ?>
        </div>
    </div>
</div>