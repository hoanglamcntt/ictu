<div class="post-inner">
	<?php theme_post_thumbnail( 330, 450 ); ?>
	<div class="post-info">
		<?php theme_post_title(); ?>
		<div class="post-meta">
			<a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
				<?php echo get_the_date( 'F d, Y' ); ?>
			</a>
			<?php theme_post_author(
				esc_html__( 'by', 'ictu' )
			); ?>
		</div>
		<?php
		theme_post_excerpt( 23 );
		theme_post_readmore();
		?>
	</div>
</div>