<?php
/**
 * @var $atts
 * @var $_this
 */
?>
<?php $href = $atts['banner_link'] ? $atts['banner_link'] : '#'; ?>
<div class="ovic-banner-block__wrap-single">
    <a href="<?php echo esc_url($href) ?>" class="ovic-banner-block__link">
        <figure>
            <img src="<?php echo esc_url($atts['banner_image']['url']) ?>" alt="<?php echo esc_attr($atts['banner_image']['alt']) ?>">
        </figure>
    </a>
</div>
