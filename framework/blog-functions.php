<?php defined( 'ABSPATH' ) || exit;
/**
 *
 * TEMPLATES FUNCTION
 **/
if ( !function_exists( 'theme_post_thumbnail' ) ) {
    function theme_post_thumbnail( $width, $height, $placeholder = true, $effect = '', $category = false, $class = '' )
    {
        $width  = apply_filters( 'theme_post_thumbnail_width', $width );
        $height = apply_filters( 'theme_post_thumbnail_height', $height );
        ?>
        <div class="post-thumb">
            <?php
            if ( $category ) {
                theme_get_term_list();
            }
            ?>
            <a href="<?php echo theme_post_link(); ?>" class="thumb-link <?php echo esc_attr( $effect ); ?>">
                <figure>
                    <?php
                    $thumb = theme_resize_image( get_post_thumbnail_id(), $width, $height, true, true, $placeholder, $class );
                    echo wp_specialchars_decode( $thumb['img'] );
                    ?>
                </figure>
            </a>
            <?php do_action( 'theme_post_thumbnail_inner' ); ?>
        </div>
        <?php
    }
}
if ( !function_exists( 'theme_post_item_thumbnail' ) ) {
    function theme_post_item_thumbnail( $post_id, $width, $height, $placeholder = true )
    {
        $width  = apply_filters( 'theme_post_thumbnail_width', $width );
        $height = apply_filters( 'theme_post_thumbnail_height', $height );
        ?>
        <div class="post-thumb">
            <a href="<?php echo theme_post_link(); ?>" class="thumb-link">
                <figure>
                    <?php
                    $thumb = theme_resize_image( get_post_thumbnail_id( $post_id ), $width, $height, true, true, $placeholder );
                    echo wp_specialchars_decode( $thumb['img'] );
                    ?>
                </figure>
            </a>
        </div>
        <?php
    }
}
if ( !function_exists( 'theme_post_thumbnail_single' ) ) {
    function theme_post_thumbnail_single( $width = false, $height = false, $placeholder = true, $class = '' )
    {
        ?>
        <div class="post-thumb">
            <figure>
                <?php
                $thumb = theme_resize_image( get_post_thumbnail_id(), $width, $height, true, true, $placeholder, $class );
                echo wp_specialchars_decode( $thumb['img'] );
                ?>
            </figure>
        </div>
        <?php
    }
}
if ( !function_exists( 'theme_post_thumbnail_standard' ) ) {
    function theme_post_thumbnail_standard( $width, $height )
    {
        $width  = apply_filters( 'theme_post_thumbnail_width', $width );
        $height = apply_filters( 'theme_post_thumbnail_height', $height );
        ?>
        <div class="post-thumb">
            <a href="<?php echo theme_post_link(); ?>" class="thumb-link">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( array( $width, $height ) ); ?>
                <?php else: ?>
                    <?php if ( has_category( 4 ) ) { ?>
                        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/thongbao-img.png' ) ) ?>" class="wp-post-image" alt="">
                    <?php } else { ?>
                        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/placeholder-img.png' ) ) ?>" class="placeholder-img" alt="">
                    <?php } ?>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }
}
if ( !function_exists( 'theme_post_author' ) ) {
    function theme_post_author( $text = false )
    {
        ?>
        <a class="author" href="<?php echo theme_post_link( 'auth' ); ?>">
            <?php if ( $text ): ?>
                <span class="text"><?php echo esc_html( $text ); ?></span>
            <?php else: ?>
                <i class="icon fa fa-pencil-square-o" aria-hidden="true"></i>
            <?php endif; ?>
            <span class="name"><?php the_author(); ?></span>
        </a>
        <?php
    }
}
if ( !function_exists( 'theme_get_term_list' ) ) {
    function theme_get_term_list( $taxonomy = 'category' )
    {
        echo get_the_term_list( get_the_ID(), $taxonomy, '<div class="cat-list">', '', '</div>' );
    }
}
if ( !function_exists( 'theme_post_link' ) ) {
    function theme_post_link( $type = 'post', $id = 0 )
    {
        global $post;

        switch ( $type ) {
            case 'date':
                $archive_year  = get_the_time( 'Y' );
                $archive_month = get_the_time( 'm' );
                $archive_day   = get_the_time( 'd' );
                $permalink     = get_day_link( $archive_year, $archive_month, $archive_day );
                break;
            case 'auth':
                if ( $id == 0 ) {
                    $id = get_the_author_meta( 'ID' );
                }
                $permalink = get_author_posts_url( $id );
                break;
            default:
                if ( $id == 0 ) {
                    $id = get_the_ID();
                }
                $permalink = get_the_permalink( $id );
                break;
        }

        return apply_filters( 'ovic_loop_post_link', esc_url( $permalink ), $post );
    }
}
if ( !function_exists( 'theme_post_formats' ) ) {
    function theme_post_formats()
    {
        $value     = '';
        $format    = 'standard';
        $post_meta = get_post_meta( get_the_ID(), '_custom_metabox_post_options', true );
        if ( !empty( $post_meta['type'] ) && $post_meta['type'] != 'standard' ) {
            $format = $post_meta['type'];
            $value  = $post_meta[ $format ];
        }
        theme_get_template( "templates/blog/blog-formats/format-{$format}.php", array( 'value' => $value ) );
    }
}
if ( !function_exists( 'theme_post_pagination' ) ) {
    function theme_post_pagination()
    {
        $args = array(
            // WPCS: XSS ok.
            'screen_reader_text' => '&nbsp;',
            'before_page_number' => '',
            'prev_text'          => esc_html__( 'Prev', 'ictu' ),
            'next_text'          => esc_html__( 'Next', 'ictu' ),
            'type'               => 'list',
        );

        $pagination = get_theme_option( 'blog_pagination', 'pagination' );
        $animate    = 'fadeInUp';
        if ( function_exists( 'ovic_custom_pagination' ) ) : ?>
            <div class="pagination-wrap">
                <?php
                ovic_custom_pagination(
                    array(
                        'pagination'    => $pagination,
                        'class'         => 'button',
                        'animate'       => $animate,
                        'text_loadmore' => esc_html__( 'Load more', 'ictu' ),
                        'text_infinite' => esc_html__( 'Loading', 'ictu' ),
                    ),
                    $args );
                ?>
            </div>
        <?php else: ?>
            <div class="pagination-wrap">
                <nav class="woocommerce-pagination">
                    <?php echo paginate_links( $args ); ?>
                </nav>
            </div>
        <?php endif;
    }
}
if ( !function_exists( 'theme_pagination_post' ) ) {
    function theme_pagination_post()
    {
        $nav_button = get_theme_option( 'enable_nav_button' );
        $prev_post  = get_previous_post();
        $next_post  = get_next_post();
        if ( $nav_button == 1 ):
            ?>
            <nav class="pagination-thumb">
                <?php if ( !empty( $prev_post ) ): ?>
                    <div class="other-post prev">
                        <a class="link" href="<?php echo theme_post_link( 'post', $prev_post->ID ); ?>">
                            <span class="icon"></span>
                            <span class="content">
                                <span class="text"><?php echo esc_html__( 'Previous Post', 'ictu' ); ?></span>
                                <span class="name"><?php echo esc_html( $prev_post->post_title ) ?></span>
                            </span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ( !empty( $next_post ) ): ?>
                    <div class="other-post next">
                        <a class="link" href="<?php echo theme_post_link( 'post', $next_post->ID ); ?>">
                            <span class="icon"></span>
                            <span class="content">
                                <span class="text"><?php echo esc_html__( 'Next Post', 'ictu' ); ?></span>
                                <span class="name"><?php echo esc_html( $next_post->post_title ) ?></span>
                            </span>
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
        <?php
        endif;
    }
}
if ( !function_exists( 'theme_latest_post' ) ) {
    function theme_latest_post()
    {
        $enable = get_theme_option( 'enable_related' );
        if ( $enable == 1 ) {
            echo theme_do_shortcode(
                'ovic_blog',
                apply_filters(
                    'theme_latest_post_args',
                    array(
                        'title'    => esc_html__( 'Related Posts', 'ictu' ),
                        'target'   => 'date',
                        'orderby'  => 'rand',
                        'limit'    => '6',
                        'order'    => '',
                        'carousel' => array(
                            'slidesToShow' => 3,
                            'slidesMargin' => 0,
                            'arrows'       => true,
                            'infinite'     => false,
                            'responsive'   => array(
                                array(
                                    'breakpoint' => 992,
                                    'settings'   => array(
                                        'slidesToShow' => 2,
                                    ),
                                ),
                                array(
                                    'breakpoint' => 480,
                                    'settings'   => array(
                                        'slidesToShow' => 1,
                                    ),
                                ),
                            ),
                        ),
                    )
                )
            );
        }
    }
}
if ( !function_exists( 'theme_about_author' ) ) {
    function theme_about_author()
    {
        $enable    = get_theme_option( 'enable_about_author' );
        $author_id = get_the_author_meta( 'ID' );
        if ( get_the_author_meta( 'description' ) != '' && $enable == 1 ):
            ?>
            <div class="author-content">
                <div class="author-avatar">
                    <?php echo get_avatar( $author_id, 90 ); ?>
                </div>
                <div class="author-info">
                    <h3 class="name">
                        <?php the_author(); ?>
                    </h3>
                    <p class="desc"><?php echo nl2br( get_the_author_meta( 'description' ) ); ?></p>
                    <a class="link" href="<?php echo theme_post_link( 'auth' ); ?>">
                        <?php echo sprintf( '%s %s', esc_html__( 'View all post by', 'ictu' ), get_the_author() ) ?>
                    </a>
                </div>
            </div>
        <?php
        endif;
    }
}
if ( !function_exists( 'theme_post_title' ) ) {
    function theme_post_title( $link = true )
    {
        if ( get_the_title() ) {
            if ( $link == true ) {
                echo '<h3 class="post-title"><a href="' . theme_post_link() . '">' . get_the_title() . '</a></h3>';
            } else {
                echo '<h3 class="post-title"><span>' . get_the_title() . '</span></h3>';
            }
        }
    }
}
if ( !function_exists( 'theme_post_readmore' ) ) {
    function theme_post_readmore( $text = '' )
    {
        ?>
        <div class="post-readmore">
            <a href="<?php echo theme_post_link(); ?>" class="button">
                <?php echo !empty( $text ) ? esc_html( $text ) : esc_html__( 'Read More', 'ictu' ); ?>
            </a>
        </div>
        <?php
    }
}
if ( !function_exists( 'theme_callback_comment' ) ) {
    /**
     * Ocolus comment template
     *
     * @param array $comment the comment array.
     * @param array $args the comment args.
     * @param int $depth the comment depth.
     *
     * @since 1.0.0
     */
    function theme_callback_comment( $comment, $args, $depth )
    {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

        $commenter = wp_get_current_commenter();
        if ( $commenter['comment_author_email'] ) {
            $moderation_note = esc_html__( 'Your comment is awaiting moderation.', 'ictu' );
        } else {
            $moderation_note = esc_html__( 'Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.', 'ictu' );
        }
        ?>
        <<?php echo wp_specialchars_decode( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? 'parent' : '', $comment ); ?>>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <?php if ( 0 != $args['avatar_size'] ): ?>
                <div class="comment-avatar">
                    <figure><?php echo get_avatar( $comment, $args['avatar_size'] ); ?></figure>
                </div>
            <?php endif; ?>
            <div class="comment-info">
                <div class="comment-meta">
                    <div class="comment-author vcard">
                        <?php
                        /* translators: %s: comment author link */
                        printf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) );
                        ?>
                    </div>
                    <div class="comment-date">
                        <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                            <time datetime="<?php comment_time( 'c' ); ?>">
                                <?php
                                /* translators: 1: comment date, 2: comment time */
                                printf( esc_html__( '%1$s at %2$s', 'ictu' ),
                                    get_comment_date( '', $comment ),
                                    get_comment_time() );
                                ?>
                            </time>
                        </a>
                    </div>
                    <?php
                    edit_comment_link( esc_html__( 'Edit', 'ictu' ), '<span class="edit-link">', '</span>' );
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'add_below' => 'div-comment',
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth'],
                                'before'    => '<div class="reply">',
                                'after'     => '</div>',
                            )
                        )
                    );
                    ?>
                </div><!-- .comment-meta -->
                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em>
                <?php endif; ?>
                <div class="comment-text">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->
            </div>
        </div><!-- .comment-body -->
        <?php
    }
}
if ( !function_exists( 'theme_post_excerpt' ) ) {
    function theme_post_excerpt( $count = 35 )
    {
        if ( !empty( get_the_excerpt() ) ) { ?>
            <div class="post-excerpt">
                <?php
                if ( $count === null ) {
                    echo apply_filters( 'the_excerpt', get_the_excerpt() );
                } else {
                    echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), $count, esc_html__( '...', 'ictu' ) );
                }
                ?>
            </div>
        <?php }
    }
}
if ( !function_exists( 'theme_post_content' ) ) {
    function theme_post_content()
    {
        if ( !is_search() ):
            ?>
            <div class="post-content">
                <?php
                /* translators: %s: Name of current post */
                the_content( sprintf( esc_html__( 'Continue reading %s', 'ictu' ), the_title( '<span class="screen-reader-text">', '</span>', false ) ) );
                wp_link_pages(
                    array(
                        'before'      => '<div class="post-pagination"><span class="title">' . __( 'Pages:', 'ictu' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                    )
                );
                ?>
            </div>
        <?php
        endif;
    }
}
if ( !function_exists( 'theme_post_meta' ) ) {
    function theme_post_meta( $date = false )
    {
        $share = get_theme_option( 'enable_share_post' );
        ?>
        <div class="post-meta">
            <?php theme_post_author(); ?>
            <?php if ( $date == true ) : ?>
                <a href="<?php echo theme_post_link( 'date' ); ?>" class="post-date">
                    <span class="icon fa fa-calendar"></span>
                    <?php echo get_the_date( 'd M' ); ?>
                </a>
            <?php endif; ?>
            <a href="<?php echo theme_post_link( 'date' ); ?>#comments" class="comment">
                <span class="icon fa fa-comment-o"></span>
                (<?php comments_number( '0', '1', '%' ); ?>)
            </a>
            <?php if ( $share == 1 ): ?>
                <div class="share-post">
                    <a href="javascript:void(0)" class="toggle">
                        <span class="icon fa fa-share-alt"></span>
                    </a>
                    <?php theme_share_social( get_the_ID() ); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
if ( !function_exists( 'theme_post_category' ) ) {
    function theme_post_category()
    {
        $get_term_cat = get_the_terms( get_the_ID(), 'category' );
        if ( !is_wp_error( $get_term_cat ) && !empty( $get_term_cat ) ) : ?>
            <div class="post--categories">
                <?php the_category( ', ' ); ?>
            </div>
        <?php endif;
    }
}
if ( !function_exists( 'theme_related_articles' ) ) {
    function theme_related_articles( $post_id )
    {
        if ( !$post_id ) {
            return;
        }
        $cats = wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) );
        if ( !empty( $cats ) ) {
            return;
        }
        $my_query = new WP_Query( array( 'cat' => implode( ',', $cats ), 'posts_per_page' => 10, 'post__not_in' => [ get_the_ID() ] ) );
        if ( $my_query->have_posts() ) : ?>
            <div class="theme-cat-posts">
                <h3 class="theme-cat-posts__title"><?php esc_html_e( 'Bài viết cùng chuyên mục', 'ictu' ); ?></h3>
                <ul class="theme-cat-posts__list">
                    <?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
                        <li class="theme-cat-posts__elm">
                            <a href="<?php echo get_the_permalink(); ?>" class="theme-cat-posts__post-name"><i class="fa fa-angle-right" aria-hidden="true"></i><?php echo get_the_title() ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif;
        wp_reset_postdata();
    }
}

if ( !function_exists( 'theme_check_content_media' ) ) {
    function theme_check_content_media( $format, $value )
    {
        $html            = '';
        $hidden_contents = '';
        switch ( $format ) {
            case 'gallery':
                $unique    = wp_unique_id( get_the_ID() );
                $galleries = !empty( $value ) ? explode( ',', $value ) : array();
                if ( !empty( $galleries ) ) {
                    foreach ( $galleries as $id ) {
                        $gallery = wp_get_attachment_image_src( $id, 'full' );
                        if ( $html === '' && $gallery ) {
                            $html = '<a class="js-btn-start-gallery" data-fancybox="gallery-' . $unique . '" href="' . $gallery[0] . '" ><img src="' . $gallery[0] . '" alt="" width="' . $gallery[1] . '" height="' . $gallery[2] . '"></a>';
                        } else if ( $gallery ) {
                            $hidden_contents .= '<a data-fancybox="gallery-' . $unique . '" href="' . $gallery[0] . '" ><img src="' . $gallery[0] . '" alt="" width="' . $gallery[1] . '" height="' . $gallery[2] . '"></a>';
                        }
                    }
                    $html .= '<div style="display: none">' . $hidden_contents . '</div>';
                }
                break;
            case 'video':
                $html = '<a class="js-btn-start-gallery" data-fancybox="video-gallery" data-src="' . esc_url( $value ) . '"></a>';
                break;
            default :
                $html = '';
                break;
        }
        echo esc_html( $html );
    }
}

if ( !function_exists( 'ictu_get_first_category_name_of_post' ) ) {
    function ictu_get_first_category_name_of_post() : string
    {
        $title = '';
        if ( is_single() ) {
            if ( get_post_type() == 'baochi' ) {
                $categories = get_the_terms( get_the_ID(), 'loaibaochi' );
            } else {
                $categories = get_the_category();
            }
            $title = ( !empty( $categories ) && !is_wp_error( $categories ) ) ? $categories[0]->name : __( 'No category', 'ictu' );
        } else {
            $term  = get_queried_object();
            $title = $term ? $term->name : '';
        }
        return $title;
    }

    add_filter( 'first_category_name_of_post', 'ictu_get_first_category_name_of_post', 10 );
}

if ( !function_exists( 'ictu_next_and_previous_post_cat' ) ) {
    function ictu_next_and_previous_post_cat()
    {
        global $post;
        $post_id        = $post->ID; // current post ID
        $cat            = get_the_category();
        $current_cat_id = $cat[0]->cat_ID; // current category ID
        $args           = array(
            'category' => $current_cat_id,
            'orderby'  => 'post_date',
            'order'    => 'DESC'
        );
        $posts          = get_posts( $args );
        // get IDs of posts retrieved from get_posts
        $ids = array();
        foreach ( $posts as $p ) {
            $ids[] = $p->ID;
        }
        // get and echo previous and next post in the same category
        $this_index = array_search( $post_id, $ids );
        $prev_id    = $ids[ $this_index - 1 ] ?? false;
        $next_id    = $ids[ $this_index + 1 ] ?? false;
        ?>
        <div class="ovic-previous-next-post-on-cate">
            <?php if ( false !== $next_id ): ?>
                <a rel="prev" href="<?php echo get_permalink( $next_id ) ?>"><?php echo esc_html( get_the_title( $next_id ) ) ?></a>
            <?php endif; ?>
            <?php if ( false !== $prev_id ): ?>
                <a rel="prev" href="<?php echo get_permalink( $prev_id ) ?>"><?php echo esc_html( get_the_title( $prev_id ) ) ?></a>
            <?php endif; ?>
        </div>
        <?php
    }

    add_action( 'theme-next-and-previous-post-cat-template', 'ictu_next_and_previous_post_cat', 10 );
}

if ( !function_exists( 'theme_post_share' ) ) {
    function theme_post_share()
    {
        $share_image_url  = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'full' );
        $share_link_url   = get_permalink( get_the_ID() );
        $share_link_title = get_the_title();
        $share_summary    = get_the_excerpt();

        $facebook  = 'https://www.facebook.com/sharer.php?u=' . $share_link_url;
        $x         = 'https://twitter.com/intent/tweet?url=' . $share_link_url . '&text=' . $share_summary;
        $reddit    = 'https://www.reddit.com/submit?url=' . $share_link_url . '&title=' . $share_link_title;
        $linkedin  = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $share_link_url;
        $pinterest = 'https://pinterest.com/pin/create/button/?url=' . $share_link_url . '&description=' . $share_summary . '&media=' . urlencode( $share_image_url );
        $whatsapp  = 'https://api.whatsapp.com/send?text=' . $share_summary . '%20' . $share_link_url;
        ?>
        <div class="post-share">
            <span class="title"><span><?php echo esc_html__( 'Chia sẻ bài viết', 'ictu' ); ?>: </span></span>
            <div class="ovic-share-socials">
                <div class="inner">
                    <a class="button facebook"
                       href="<?php echo esc_url( $facebook ); ?>"
                       onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                        <span class="icon fa fa-facebook-f"></span>
                        <span class="text">Facebook</span>
                    </a>
                    <a class="button pinterest"
                       href="<?php echo esc_url( $pinterest ); ?>"
                       onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                        <span class="icon fa fa-pinterest-p"></span>
                        <span class="text">Pinterest</span>
                    </a>
                    <a class="button twitter"
                       href="<?php echo esc_url( $x ); ?>"
                       onclick='window.open(this.href,"","menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                        <span class="icon fa fa-twitter"></span>
                        <span class="text">X</span>
                    </a>
                    <a class="button reddit"
                       href="<?php echo esc_url( $reddit ); ?>"
                       onclick='window.open(this.href,"","menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                        <span class="icon fa fa-reddit"></span>
                        <span class="text">Reddit</span>
                    </a>
                    <a class="button linkedin"
                       href="<?php echo esc_url( $linkedin ); ?>"
                       onclick='window.open(this.href,"","menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                        <span class="icon fa fa-linkedin"></span>
                        <span class="text">LinkedIn</span>
                    </a>
                    <a class="button whatsapp"
                       href="<?php echo esc_url( $whatsapp ); ?>"
                       target="_blank">
                        <span class="icon fa fa-whatsapp"></span>
                        <span class="text">WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}