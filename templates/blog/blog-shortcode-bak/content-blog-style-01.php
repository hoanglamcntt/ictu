<div class="post-inner">
	<?php theme_post_thumbnail( 440, 280 ); ?>
    <div class="post-info">
		<?php
		theme_get_term_list();
        theme_post_title();
		?>
        <div class="post-meta">
			<?php theme_post_author(); ?>
            <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
                <i class="icon fa fa-clock-o" aria-hidden="true"></i>
				<?php echo get_the_date( 'Y-M-d' ); ?>
            </a>
        </div>
    </div>
</div>