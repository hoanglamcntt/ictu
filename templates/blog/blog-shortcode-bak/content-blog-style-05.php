<div class="post-inner">
	<?php theme_post_thumbnail( 430, 290, true, '', true ); ?>
    <div class="post-info equal-elem">
        <div class="post-meta">
            <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
				<?php echo get_the_date( 'F d, Y' ); ?>
            </a>
			<?php theme_post_author(
				esc_html__( 'by', 'ictu' )
			); ?>
        </div>
		<?php
		theme_post_title();
		theme_post_excerpt( 12 );
		theme_post_readmore();
		?>
    </div>
</div>