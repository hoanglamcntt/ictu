<?php
/**
 * Name: Blog Standard
 **/
wp_enqueue_style('blog-archive');
?>
<div class="blog-content blog-standard response-content article-build-in-list">
    <div class="single-post-head-block">
        <h2 class="single-post-head-block__title"><?php echo apply_filters('first_category_name_of_post', '') ?></h2>
    </div>
    <div class="theme-list-layout">
        <div class="wrap-blogs">
            <?php while (have_posts()): the_post(); ?>
                <?php $post_options = get_post_meta(get_the_ID(), '_custom_metabox_post_options', true); ?>
                <?php $post_type = !empty($post_options) ? $post_options['type'] : 'standard'; ?>
                <?php get_template_part("templates/blog/blog-formats/format", $post_type, $post_options); ?>
            <?php endwhile; ?>
        </div>
        <?php theme_post_pagination(); ?>
    </div>
</div>