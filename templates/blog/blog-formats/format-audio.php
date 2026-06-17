<?php
/**
 * Template Format Audio
 *
 * @param $value
 *
 * @return string
 */
?>
<?php if ( !empty( $value ) ): ?>
    <div class="post-thumb audio">
        <div class="feature-image"><?php the_post_thumbnail( 'full' ); ?></div>
		<?php
		if ( strpos( $value, '<iframe' ) === false ) {
			$instance = array(
				'src'     => $value,
				'preload' => 'none',
			);
			echo wp_audio_shortcode(
				array_merge(
					$instance,
					compact( 'src' )
				),
				''
			);
		} else {
			echo wp_specialchars_decode( $value );
		}
		?>
    </div>
<?php endif; ?>