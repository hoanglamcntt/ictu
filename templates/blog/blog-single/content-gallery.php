<?php
/**
 * Name: Blog Gallery
 **/
?>
<?php
$post_meta = get_post_meta(get_the_ID(), '_custom_metabox_post_options', true);
$type      = $post_meta['type'];
$value     = $post_meta[$type];
$galleries = !empty($value) ? explode(',', $value) : array();
?>
<?php wp_enqueue_style('theme-post-single'); ?>
<?php theme_breadcrumb(); ?>
<div class="single-blog-content single-blog-standard response-content">
    <?php theme_post_title(false); ?>
    <?php if (!empty($galleries)): ?>
        <div class="row row--content-item-gallery">
            <?php foreach ($galleries as $img): ?>
                <article <?php post_class('post-item style-01 col-xs-6 col-sm-4 col-md-3 --content-item--gallery'); ?>>
                    <figure>
                        <?php echo wp_get_attachment_image($img, 'full'); ?>
                    </figure>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>