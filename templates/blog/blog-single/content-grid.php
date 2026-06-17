<?php
/**
 * Name: Blog Standard
 **/
?>
<?php wp_enqueue_style('theme-post-single'); ?>
<?php theme_breadcrumb(); ?>
<div class="single-blog-content single-blog-standard response-content">
    <?php while (have_posts()): the_post(); ?>
        <article <?php post_class('post-item style-01'); ?>>
            <?php theme_post_title(false); ?>
            <div class="single-post-content">
                <div class="single-post__head">
                    <i class="fa fa-calendar" aria-hidden="true"></i><span class="name"><?php echo get_the_date('d/m/Y H:i:s'); ?></span>
                </div>
                <div class="single-post__body">
                    <?php the_content(); ?>
                </div>
                <div class="single-post__foot">
                    <?php theme_related_articles(get_the_ID()); ?>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</div>