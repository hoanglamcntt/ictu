<div class="post-inner">
	<?php theme_post_thumbnail( 270, 180 ); ?>
    <div class="post-info">
		<?php
		theme_get_term_list();
		theme_post_title();
		?>
        <div class="post-meta">
	        <?php theme_post_author(
		        esc_html__( 'by', 'ictu' )
	        ); ?>
            <a href="<?php echo theme_post_link( 'date' ); ?>#comments" class="comment">
				<?php comments_number(
					esc_html__( '0 Comment(s)', 'ictu' ),
					esc_html__( '1 Comment(s)', 'ictu' ),
					esc_html__( '% Comment(s)', 'ictu' )
				); ?>
            </a>
        </div>
    </div>
</div>