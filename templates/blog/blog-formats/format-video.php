<?php
/**
 * Template Format Video
 *
 * @param $args
 *
 * @return string
 */
?>
<?php if (!empty($args)): ?>
    <div class="post-thumb video">
        <?php
        $instance = array(
            'src'     => $args['src'],
            'poster'  => wp_get_attachment_image_url(get_post_thumbnail_id(), 'full'),
            'width'   => 1400,
            'height'  => 930,
            'preload' => 'none',
        );
        echo wp_video_shortcode(array_merge($instance, compact($args['src'])), '');
        ?>
    </div>
<?php endif; ?>