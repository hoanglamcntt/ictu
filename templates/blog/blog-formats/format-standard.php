<?php
/**
 * Template Format Standard
 *
 * @return string
 * @var  $args
 *
 */
?>
<?php
$arg_type = !empty( $args['type'] ) ? $args['type'] : 'standard';
?>
<article <?php post_class( "article-in-loop post-item-type--{$arg_type}" ); ?>>
    <div class="post-inner">
        <div class="post-inner__thumb">
            <a href="<?php echo get_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( array( 320, 213 ) ); ?>
                <?php else: ?>
                    <?php if ( has_category( 4 ) ) { ?>
                        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/thongbao-img.png' ) ) ?>" class="wp-post-image" alt="">
                    <?php } else { ?>
                        <span class="bg-placeholder"></span>
                        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/logo_placeholder.png' ) ) ?>" class="image-placeholder" alt="">
                    <?php } ?>
                <?php endif; ?>
            </a>
        </div>
        <div class="post-inner__info">
            <div class="single-post__head">
                <span class="single-post__date"><?php echo get_the_date( 'd/m/Y' ); ?></span>
                <?php the_title( '<h2 class="single-post__title"><a href="' . get_permalink() . '">', '</a></h1>' ); ?>
            </div>
            <?php theme_post_excerpt( 45 ); ?>
        </div>
    </div>
</article>
