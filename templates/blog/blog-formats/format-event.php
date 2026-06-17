<?php
/**
 * Template Format Event
 *
 * @return string
 * @var $args
 *
 */
?>
<article <?php post_class("article-in-loop post-item-type--{$args['type']}"); ?>>
    <div class="post-inner">
        <div class="post-inner__thumb">
            <a href="<?php echo get_permalink(); ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail(array(320, 320)); ?>
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
                <span class="single-post__date"><?php echo get_the_date('d/m/Y'); ?></span>
                <?php the_title('<h2 class="single-post__title"><a href="' . get_permalink() . '">', '</a></h1>'); ?>
            </div>
            <?php if ($args['place'] || $args['time'] || $args['participants']): ?>
                <ul class="single-post__event-logs">
                    <?php if ($args['place']): ?>
                        <li class="single-post__event-logs-elm">
                            <figure>
                                <img src="<?php echo esc_url(get_theme_file_uri('/assets/images/location.png')) ?>" alt="" width="17" height="24">
                            </figure>
                            <p><?php echo esc_html($args['place']) ?></p>
                        </li>
                    <?php endif; ?>
                    <?php if ($args['time']): ?>
                        <li class="single-post__event-logs-elm">
                            <figure>
                                <img src="<?php echo esc_url(get_theme_file_uri('/assets/images/alarm.png')) ?>" alt="" width="20" height="20">
                            </figure>
                            <p><?php echo esc_html($args['time']) ?></p>
                        </li>
                    <?php endif; ?>
                    <?php if ($args['participants']): ?>
                        <li class="single-post__event-logs-elm">
                            <figure>
                                <img src="<?php echo esc_url(get_theme_file_uri('/assets/images/meeting.png')) ?>" alt="" width="25" height="16">
                            </figure>
                            <p><?php echo esc_html($args['participants']) ?></p>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</article>
