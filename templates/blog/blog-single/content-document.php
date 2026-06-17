<?php
/**
 * Name: Document
 * @var $args
 */
?>
<?php wp_enqueue_style('theme-post-single'); ?>
<?php
$post_banner     = $args['post_banner'] ? wp_get_attachment_image_src($args['post_banner'], 'full') : '';
$link_google_doc = '';
$classes         = array('single-blog-content', 'single-blog-standard', 'response-content', 'single-blog-content--calendar');
if ((isset($args['google_doc']) && $args['google_doc'])) {
    $link_google_doc = $args['google_doc'];
    $classes[]       = 'single-blog-content--has-iframe';
} ?>
<div class="<?php echo implode(' ', $classes) ?>">
    <?php while (have_posts()): the_post(); ?>
        <article <?php post_class('post-item style-01 article-style-calendar'); ?>>
            <div class="single-post-content">
                <div class="single-post__head">
                    <?php the_title( '<h1 class="single-post__title">', '</h1>' ); ?>
                    <span class="post-date"><span class="icon fa fa-calendar"></span><?php echo get_the_date( 'd/m/Y' ); ?></span>
                </div>
                <?php if ('' !== get_post()->post_content): ?>
                    <div class="single-post__body">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>
                <?php if ($link_google_doc) : ?>
                    <iframe src="<?php echo esc_url($args['google_doc']) ?>" frameborder="0"></iframe>
                <?php endif; ?>
            </div>
        </article>
        <div class="single-article__section-related-posts">
            <div class="single-article__section-related-posts__element single-article__section-related-posts__element--left-position"><?php previous_post_link('%link', '&laquo; %title', true); ?></div>
            <div class="single-article__section-related-posts__element single-article__section-related-posts__element--right-position"><?php next_post_link('%link', '%title &raquo;', true); ?></div>
        </div>
    <?php endwhile; ?>
</div>