<?php
/**
 * Name: Blog Standard
 * @var $args
 */
?>
<?php wp_enqueue_style( 'theme-post-single' ); ?>
<?php $post_banner = ( !empty( $args['post_banner'] ) ) ? wp_get_attachment_image_src( $args['post_banner'], 'full' ) : ''; ?>
<div class="single-blog-content single-blog-standard response-content">
    <div class="single-post-head-block">
        <h2 class="single-post-head-block__title"><?php echo apply_filters( 'first_category_name_of_post', '' ) ?></h2>
    </div>
    <?php while ( have_posts() ): the_post(); ?>
        <article <?php post_class( 'post-item style-01' ); ?>>
            <?php if ( !empty( $post_banner ) ): ?>
                <div class="single-post-banner-head">
                    <figure>
                        <img class="lazy" src="<?php echo theme_get_svg_image( $post_banner[1], $post_banner[2] ) ?>" data-src="<?php echo esc_url( $post_banner[0] ) ?>" width="<?php echo esc_attr( $post_banner[1] ) ?>" height="<?php echo esc_attr( $post_banner[2] ) ?>" alt="">
                    </figure>
                </div>
            <?php endif; ?>
            <div class="single-post-content">
                <div class="single-post__head">
                    <span class="single-post__date"><?php echo get_the_date( 'd/m/Y' ); ?></span>
                    <?php the_title( '<h1 class="single-post__title">', '</h1>' ); ?>
                </div>
                <div class="single-post__body">
                    <?php the_content(); ?>
                </div>
                <div class="single-post__foot">
<!--                    <p><i class="fa fa-heart" aria-hidden="true"></i>2021</p>-->
                    <div class="single-post__foot-share">
                        <span><?php esc_html_e( 'Chia sẻ:' ) ?></span>
                        <ul class="single-post__foot-shared-list">
                            <!-- Your share button code -->
                            <!--<li>
                                <div class="fb-share-button" data-href="<?php /*echo esc_url(get_permalink()) */ ?>" data-layout="button_count"></div>
                            </li>-->
                            <li><a href="#" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</div>