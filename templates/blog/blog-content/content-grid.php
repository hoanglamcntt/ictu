<?php
/**
 * Name: Blog Grid
 **/

wp_enqueue_style( 'blog-archive' );
wp_enqueue_style( 'fancybox' );
wp_enqueue_script( 'fancybox' );
?>
<div class="blog-content blog-standard response-content article-build-in-grid">
    <div class="theme-grid-layout">
        <div class="wrap-blogs">
            <?php while ( have_posts() ): the_post(); ?>
                <?php
                $value     = '';
                $format    = 'standard';
                $post_meta = get_post_meta( get_the_ID(), '_custom_metabox_post_options', true );
                if ( !empty( $post_meta['type'] ) && $post_meta['type'] != 'standard' ) {
                    $format = $post_meta['type'];
                    $value  = $post_meta[ $format ];
                }
                ?>
                <article <?php post_class( 'post-item elm-content-grid article-format--' . $format ); ?>>
                    <div class="post-inner">
                        <div class="thumb-wrap">
                            <div class="post-thumb standard">
                                <a href="<?php echo theme_post_link(); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'post-thumbnail' ); ?>
                                    <?php else: ?>
                                        <?php
                                        $placeholder = get_theme_file_uri( '/assets/images/logo_placeholder.png' );
                                        ?>
                                        <span class="bg-placeholder"></span>
                                        <img src="<?php echo esc_url( $placeholder ) ?>" class="image-placeholder" alt="">
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>
                        <div class="post-info">
                            <?php theme_post_title(); ?>
                        </div>
                    </div>
                    <?php //theme_check_content_media($format, $value); ?>
                </article>
            <?php endwhile; ?>
        </div>
        <?php theme_post_pagination(); ?>
    </div>
</div>
