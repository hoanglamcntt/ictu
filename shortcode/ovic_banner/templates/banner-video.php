<?php
/**
 * @var $atts
 * @var $_this
 */
?>
<?php
//wp_enqueue_script('fancybox');
//wp_enqueue_style('fancybox');
$src    = $atts['video_src'] ? $atts['video_src'] : '#';
$width  = $atts['video_width'] ? $atts['video_width'] : 1280;
$height = $atts['video_height'] ? $atts['video_height'] : 720;
$href   = $atts['banner_link'] ? $atts['banner_link'] : '#';
?>
<div class="ovic-banner-block__wrap-single ovic-banner-block__wrap-video">
    <a data-fancybox="video-gallery" data-src="<?php echo esc_url($src) ?>" data-width="<?php echo esc_attr($width) ?>" data-height="<?php echo esc_attr($height) ?>" class="ovic-banner-block__link">
        <figure>
            <img src="<?php echo esc_url($atts['banner_image']['url']) ?>" alt="<?php echo esc_attr($atts['banner_image']['alt']) ?>">
        </figure>
        <i class="fa fa-play" aria-hidden="true"></i>
    </a>
    <?php if ($atts['banner_label']): ?>
        <?php $view_more_link = $atts['view_more_link'] ? $atts['view_more_link'] : '#'; ?>
        <a href="<?php echo esc_url($view_more_link) ?>" class="ovic-banner-block__link-view-more"><?php echo esc_html($atts['banner_label']) ?><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
    <?php endif; ?>
</div>
