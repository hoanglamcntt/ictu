<?php
/**
 * Template Popup Newsletter
 *
 * @return string
 */
?>
<?php
$title       = get_theme_option( 'popup_title' );
$desc        = get_theme_option( 'popup_desc' );
$placeholder = get_theme_option( 'input_placeholder' );
$button      = get_theme_option( 'popup_button' );
$background  = get_theme_option( 'popup_bg', '' );
$delay       = get_theme_option( 'popup_delay', 1000 );
$effect      = get_theme_option( 'popup_effect', 'mfp-zoom-in' );
$link        = get_theme_option( 'link', "#" );
$popup_type  = get_theme_option( 'popup_type', 'registered_form' );

$classes   = array( 'ictu-popup-newsletter white-popup mfp-with-anim mfp-hide' );
$classes[] = 'popup-layout--' . $popup_type
?>
<div id="ictu-popup-newsletter" class="<?php echo implode( ' ', $classes ) ?>"
     data-effect="<?php echo esc_attr( $effect ); ?>" data-delay="<?php echo esc_attr( $delay ); ?>">
    <div class="popup-inner">
		<?php if ( $background ): ?>
            <div class="popup-thumb">
				<?php echo wp_get_attachment_image( $background, 'full' ); ?>
            </div>
		<?php endif; ?>
		<?php if ( $popup_type === 'registered_form' ): ?>
            <div class="popup-content">
				<?php if ( $title ) : ?>
                    <h2 class="title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( $desc ) : ?>
                    <p class="desc"><?php echo esc_html( $desc ); ?></p>
				<?php endif; ?>
				<?php echo theme_do_shortcode( 'ovic_newsletter',
					array(
						'title'       => '',
						'subtitle'    => '',
						'desc'        => '',
						'style'       => 'style-01',
						'placeholder' => $placeholder,
						'button_text' => $button,
					)
				); ?>
                <label for="theme_disabled_popup_by_user" class="theme_disabled_popup_by_user">
                    <input id="theme_disabled_popup_by_user" name="theme_disabled_popup_by_user" type="checkbox">
                    <span><?php echo esc_html__( 'PREVENT THIS POP-UP', 'ictu' ); ?></span>
                </label>
            </div>
		<?php else: ?>
            <a href="<?php echo esc_url( $link ) ?>" class="permalink"></a>
		<?php endif; ?>
    </div>
</div>