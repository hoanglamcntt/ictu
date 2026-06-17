<?php
/**
 * Name: Blog Video
 **/
?>
<?php
$post_meta = get_post_meta(get_the_ID(), '_custom_metabox_post_options', true);
$type      = $post_meta['type'];
$value     = $post_meta[$type];
?>
<?php wp_enqueue_style('theme-post-single'); ?>
<?php theme_breadcrumb(); ?>
<div class="single-blog-content single-blog-standard response-content">
    <?php theme_post_title(false); ?>
    <?php if (!empty($value)): ?>
        <article <?php post_class('post-item style-01'); ?>>
            <div class="post-thumb video">
                <?php
                $instance = array(
                    'src'     => $value,
                    'poster'  => wp_get_attachment_image_url(get_post_thumbnail_id(), 'full'),
                    //                        'width'   => 1400,
                    //                        'height'  => 930,
                    'preload' => 'none',
                );
                echo wp_video_shortcode(array_merge($instance), '');
                ?>
            </div>
        </article>
    <?php endif; ?>
</div>