<?php
/**
 * @var $atts
 * @var $_this
 */
?>
<?php $href = $atts['banner_link'] ? $atts['banner_link'] : '#'; ?>
<div class="ovic-banner-block__wrap-facebook">
    <a href="<?php echo esc_url($href) ?>" class="ovic-banner-block__link">
        <figure>
            <img src="<?php echo esc_url($atts['banner_image']['url']) ?>" alt="<?php echo esc_attr($atts['banner_image']['alt']) ?>">
        </figure>
    </a>
    <?php if ($atts['banner_label']): ?>
        <?php $view_more_link = $atts['view_more_link'] ? $atts['view_more_link'] : '#'; ?>
        <a href="<?php echo esc_url($view_more_link) ?>" class="ovic-banner-block__link-view-more"><?php echo esc_html($atts['banner_label']) ?><i class="fa fa-facebook"></i></a>
    <?php endif; ?>
</div>