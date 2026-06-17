<?php
/**
 * Name: Blog Standard
 * @var $args
 */
?>
<?php wp_enqueue_style( 'theme-post-single' ); ?>
<?php $post_banner = ( !empty( $args['post_banner'] ) ) ? wp_get_attachment_image_src( $args['post_banner'], 'full' ) : ''; ?>
<div class="single-blog-content single-blog-standard response-content">
    <?php while ( have_posts() ): the_post(); ?>
        <article <?php post_class( 'post-item style-01' ); ?>>
            <?php if ( !empty( $post_banner ) ): ?>
                <div class="single-post-banner-head">
                    <figure>
                        <img class="lazy" src="<?php echo theme_get_svg_image( $post_banner[1], $post_banner[2] ) ?>" data-src="<?php echo esc_url( $post_banner[0] ) ?>" width="<?php echo esc_attr( $post_banner[1] ) ?>" height="<?php echo esc_attr( $post_banner[2] ) ?>" alt="">
                    </figure>
                </div>
            <?php endif; ?>
            <!--            --><?php //= theme_post_thumbnail_single( 1620 ); ?>
            <div class="single-post-content">
                <div class="single-post__head">
                    <?php the_title( '<h1 class="single-post__title">', '</h1>' ); ?>
                    <span class="post-date"><span class="icon fa fa-calendar"></span><?php echo get_the_date( 'd/m/Y' ); ?></span>
                </div>
                <div class="single-post__body">
                    <?php the_content(); ?>
                </div>
                <div class="single-post__foot">
                    <?= theme_post_share(); ?>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</div>