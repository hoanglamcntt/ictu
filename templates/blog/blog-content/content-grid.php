<?php
/**
 * Name: Blog Grid
 **/

wp_enqueue_style( 'blog-archive' );
wp_enqueue_style( 'fancybox' );
wp_enqueue_script( 'fancybox' );
?>
<div class="blog-content blog-grid response-content">
    <?php theme_page_title(); ?>
    <div class="theme-grid-layout">
        <div class="ovic-flexbox">
            <?php while ( have_posts() ): the_post(); ?>
                <article <?php post_class( 'blog-item post-item' ); ?>>
                    <div class="post-inner">
                        <?php theme_post_thumbnail( 360, 240 ); ?>
                        <div class="post-info">
                            <?php theme_post_title(); ?>
                            <div class="post-date"><span class="icon fa fa-calendar"></span><?php echo get_the_date(); ?></div>
                            <?php theme_post_excerpt( 30 ); ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <?php theme_post_pagination(); ?>
    </div>
</div>
