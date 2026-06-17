<?php
/**
 * Name: Blog Standard
 * @var $args
 */
?>
<?php wp_enqueue_style( 'theme-post-single' ); ?>
<?php $post_banner = ( in_array( 'post_banner', $args ) && $args['post_banner'] ) ? wp_get_attachment_image_src( $args['post_banner'], 'full' ) : ''; ?>
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
            <div class="single-post-content">
                <div class="single-post__head">
                    <?php the_title( '<h1 class="single-post__title">', '</h1>' ); ?>
                    <span class="post-date"><span class="icon fa fa-calendar"></span><?php echo get_the_date( 'd/m/Y' ); ?></span>
                </div>
                <?php if ( $args['place'] || $args['time'] || $args['participants'] ): ?>
                    <ul class="single-post__event-logs">
                        <?php if ( $args['place'] ): ?>
                            <li class="single-post__event-logs-elm">
                                <figure>
                                    <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/location.png' ) ) ?>" alt="" width="17" height="24">
                                </figure>
                                <p><?php echo esc_html( $args['place'] ) ?></p>
                            </li>
                        <?php endif; ?>
                        <?php if ( $args['time'] ): ?>
                            <li class="single-post__event-logs-elm">
                                <figure>
                                    <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/alarm.png' ) ) ?>" alt="" width="20" height="20">
                                </figure>
                                <p><?php echo esc_html( $args['time'] ) ?></p>
                            </li>
                        <?php endif; ?>
                        <?php if ( $args['participants'] ): ?>
                            <li class="single-post__event-logs-elm">
                                <figure>
                                    <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/meeting.png' ) ) ?>" alt="" width="25" height="16">
                                </figure>
                                <p><?php echo esc_html( $args['participants'] ) ?></p>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
                <div class="single-post__body">
                    <?php the_content(); ?>
                </div>
                <div class="single-post__foot">
                    <!--                    <p><i class="fa fa-heart" aria-hidden="true"></i>2021</p>-->
                    <div class="single-post__foot-share">
                        <span><?php esc_html_e( 'Chia sẻ:', 'ictu' ) ?></span>
                        <ul class="single-post__foot-shared-list">
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