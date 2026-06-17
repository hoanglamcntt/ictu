<div class="post-inner">
	<?php theme_post_thumbnail( 443, 295 ); ?>
	<div class="post-info">
		<?php
		theme_get_term_list();
		theme_post_title();
		?>
		<div class="post-meta">
			<?php theme_post_author(
				esc_html__( 'by', 'ictu' )
			); ?>
			<a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
				<?php echo get_the_date( 'M d, Y' ); ?>
			</a>
		</div>
	</div>
</div>