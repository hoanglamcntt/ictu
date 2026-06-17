<?php if ( !defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.
/**
 *
 * ADD CLASS NAV
 **/
add_filter( 'navigation_markup_template',
    function ( $template, $class ) {
        return '<nav class="navigation woocommerce-pagination %1$s" role="navigation"><h2 class="screen-reader-text">%2$s</h2><div class="nav-links">%3$s</div></nav>';
    }, 10, 2
);
/**
 *
 * VERSION MOBILE OPTION
 **/
if ( !function_exists( 'theme_is_mobile' ) ) {
    function theme_is_mobile() : bool
    {
        $mobile_enable = theme_option_meta(
            '_custom_metabox_theme_options',
            'mobile_enable',
            'metabox_mobile_enable',
            ''
        );
        return $mobile_enable == 1;
    }
}
if ( !function_exists( 'theme_is_elementor' ) ) {
    function theme_is_elementor() : bool
    {
        if ( class_exists( '\Elementor\Plugin' ) && Elementor\Plugin::$instance->editor ) {
            return true;
        }
        return false;
    }
}

/**
 *
 * GET OPTION
 **/
if ( !function_exists( 'get_theme_option' ) ) {
    function get_theme_option( $option_name = '', $default = '' )
    {
        $cs_option = array();
        if ( get_option( '_ovic_customize_options' ) !== false ) {
            $cs_option = get_option( '_ovic_customize_options' );
        }
        if ( isset( $_GET[ $option_name ] ) ) {
            $default                   = wp_unslash( $_GET[ $option_name ] );
            $cs_option[ $option_name ] = wp_unslash( $_GET[ $option_name ] );
        }
        $options = apply_filters( 'theme_get_customize_option', $cs_option, $option_name, $default );
        if ( !empty( $options ) && isset( $options[ $option_name ] ) ) {
            $option = $options[ $option_name ];
            if ( is_array( $option ) && isset( $option['multilang'] ) && $option['multilang'] == true ) {
                if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
                    if ( isset( $option[ ICL_LANGUAGE_CODE ] ) ) {
                        return $option[ ICL_LANGUAGE_CODE ];
                    }
                } else {
                    $option = reset( $option );
                }
            }

            return $option;
        } else {
            return $default;
        }
    }
}

if ( !function_exists( 'theme_preview_options' ) ) {
    function theme_preview_options( $name )
    {
        $preview_options = array();
        $path            = trailingslashit( get_template_directory() ) . "shortcode/{$name}/layout/";
        // Check if Elementor installed and activated
        if ( !did_action( 'elementor/loaded' ) ) {
            return array();
        }
        if ( is_dir( $path ) ) {
            $files = scandir( $path );
            if ( $files && is_array( $files ) ) {
                foreach ( $files as $file ) {
                    if ( $file != '.' && $file != '..' ) {
                        $fileInfo = pathinfo( $file );
                        if ( $fileInfo['extension'] == 'jpg' ) {
                            $fileName = str_replace( array( '_', '-' ),
                                array( ' ', ' ' ),
                                $fileInfo['filename'] );
                            /* PRINT OPTION */
                            $preview_options[ $fileInfo['filename'] ] = ucwords( $fileName );
                        }
                    }
                }
            }
        }

        return $preview_options;
    }
}

if ( !function_exists( 'theme_get_header' ) ) {
    function theme_get_header()
    {
        return theme_option_meta(
            '_custom_metabox_theme_options',
            'header_template',
            'metabox_header_template',
            'style-08'
        );
    }
}
if ( !function_exists( 'theme_get_footer' ) ) {
    function theme_get_footer()
    {
        $mobile_footer = theme_option_meta(
            '_custom_metabox_theme_options',
            'mobile_footer',
            'metabox_mobile_footer',
            'none'
        );
        $footer_option = 'style-01';
        if ( theme_is_mobile() ) {
            $footer_option = $mobile_footer;
        }

        return $footer_option;
    }
}
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
if ( !function_exists( 'theme_edit_link' ) ) {
    function theme_edit_link( $id = 0, $text = null )
    {
        if ( !$post = get_post( $id ) ) {
            return;
        }
        if ( !$url = get_edit_post_link( $post->ID ) ) {
            return;
        }
        if ( null === $text ) {
            $text = esc_html__( 'Edit This', 'ictu' );
        }
        /**
         * Filters the post edit link anchor tag.
         *
         * @param string $link Anchor tag for the edit link.
         * @param int $post_id Post ID.
         * @param string $text Anchor text.
         *
         * @since 2.3.0
         *
         */ ?>
        <span class="edit-link ovic-edit-link">
            <a class="post-edit-link" href="<?php echo esc_url( $url ); ?>">
                <span class="dashicons dashicons-edit"></span>
                <?php echo esc_html( $text ); ?>
            </a>
        </span>
        <?php
    }
}
if ( !function_exists( 'theme_option_meta' ) ) {
    function theme_option_meta( $meta_id, $option_key, $key_meta = '', $default = '' )
    {
        $ID = get_the_ID();
//
//        if (!isset($_GET[$option_key]) && !empty($_GET['demo']) && $meta_id == '_custom_metabox_theme_options') {
//            $ID = $_GET['demo'];
//        }

        $data_meta = get_post_meta( $ID, $meta_id, true );

        if ( $option_key == null ) {
            $enable_options = 1;
            $theme_options  = $default;
        } else {
            $enable_options = 0;
            if ( !empty( $data_meta['enable_metabox_options'] ) ) {
                $enable_options = $data_meta['enable_metabox_options'];
            }
            $theme_options = get_theme_option( $option_key, $default );
        }
        if ( $key_meta == '' || $key_meta == null ) {
            $key_meta = "metabox_{$option_key}";
        }

        if ( $enable_options == 1 && isset( $data_meta[ $key_meta ] ) ) {
            $theme_options = $data_meta[ $key_meta ];
        }
        if ( $default != '' && $theme_options == '' ) {
            $theme_options = $default;
        }

        return $theme_options;
    }
}
/**
 * Call a shortcode function by tag name.
 *
 * @param string $tag The shortcode whose function to call.
 * @param array $atts The attributes to pass to the shortcode function. Optional.
 * @param array $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 * @since  1.4.6
 *
 */
if ( !function_exists( 'theme_do_shortcode' ) ) {
    function theme_do_shortcode( $tag, array $atts = array(), $content = null )
    {
        global $shortcode_tags;
        if ( !isset( $shortcode_tags[ $tag ] ) ) {
            return false;
        }
        return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
    }
}
if ( !function_exists( 'theme_locate_template' ) ) {
    function theme_locate_template( $template_name, $template_path = '', $default_path = '' )
    {
        if ( !$template_path ) {
            $template_path = get_template_directory();
        }
        if ( !$default_path ) {
            $default_path = get_template_directory();
        }
        // Look within passed path within the theme - this is priority.
        $template = locate_template( array( trailingslashit( $template_path ) . $template_name, $template_name, ) );
        // Get default template/.
        if ( !$template ) {
            $template = $default_path . $template_name;
        }

        // Return what we found.
        return apply_filters( 'theme_locate_template', $template, $template_name, $template_path );
    }
}
if ( !function_exists( 'theme_get_template' ) ) {
    function theme_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' )
    {
        if ( !empty( $args ) && is_array( $args ) ) {
            extract( $args ); // @codingStandardsIgnoreLine
        }
        $located = theme_locate_template( $template_name, $template_path, $default_path );
        if ( !file_exists( $located ) ) {
            return;
        }
        // Allow 3rd party plugin filter template file from their plugin.
        $located = apply_filters( 'theme_get_template', $located, $template_name, $args, $template_path, $default_path );
        do_action( 'theme_before_template_part', $template_name, $template_path, $located, $args );
        include $located;
        do_action( 'theme_after_template_part', $template_name, $template_path, $located, $args );
    }
}
if ( !function_exists( 'theme_pinmap_options' ) ) {
    function theme_pinmap_options()
    {
        $args           = array(
            'post_type'      => 'ovic_pinmap',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );
        $pinmap_options = array();
        $posts          = get_posts( $args );
        if ( !empty( $posts ) ) {
            foreach ( $posts as $post ) {
                setup_postdata( $post );
                $attachment_id               = get_post_meta( $post->ID, 'ovic_pinmap_image', true );
                $pinmap_options[ $post->ID ] = array(
                    'title'   => $post->post_title,
                    'preview' => wp_get_attachment_image_url( $attachment_id, 'medium' ),
                );
            }
        }
        wp_reset_postdata();

        return $pinmap_options;
    }
}
/**
 *
 * QUERY POSTS
 **/
if ( !function_exists( 'theme_shortcode_posts_query' ) ) {
    function theme_shortcode_posts_query( $atts, $post_type = 'post' )
    {
        global $post;

        $args                 = array(
            'post_type'           => $post_type,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'suppress_filter'     => true,
            'posts_per_page'      => $atts['limit'] ?? -1,
        );
        $post__not_in         = is_array( $atts['not_ids'] ) ? $atts['not_ids'] : explode( ',', $atts['not_ids'] );
        $args['post__not_in'] = array_map( 'trim', $post__not_in );

        if ( isset( $atts['order'] ) ) {
            $args['order'] = $atts['order'];
        }

        if ( isset( $atts['orderby'] ) ) {
            $args['orderby'] = $atts['orderby'];
        }

        if ( !empty( $atts['category'] ) ) {
            $args['cat'] = $atts['category'];
        }


        if ( isset( $atts['target'] ) ) {
            switch ( $atts['target'] ) {
                case 'popularity':
                    $args['meta_key'] = 'tuongnam_post_views_count';
                    $args['orderby']  = 'meta_value_num';
                    break;
                case 'related':
                    $categories = get_the_category( $post->ID );
                    $ids        = array();
                    if ( !empty( $categories ) ) {
                        foreach ( $categories as $category ) {
                            $ids[] = $category->term_id;
                        }
                    }
                    $args['category__in']   = $ids;
                    $args['post__not_in'][] = $post->ID;
                    break;
                case 'title':
                    $args['orderby'] = 'title';
                    $args['order']   = 'ASC';
                    break;
                case 'date':
                    $args['orderby'] = array(
                        'post_date' => 'DESC',
                        'title'     => 'ASC',
                    );
                    break;
                case 'random':
                    $args['orderby'] = 'rand';
                    break;
                case 'post':
                    $post__in               = is_array( $atts['ids'] ) ? $atts['ids'] : explode( ',', $atts['ids'] );
                    $final_ids              = array_diff( $post__in, $post__not_in );
                    $args['post__in']       = $final_ids;
                    $args['posts_per_page'] = -1;
                    unset( $args['cat'] );
                    break;
            }
        }
        return $args;
    }
}
/**
 *    RESIZE IMAGE
 *
 *    Enable Lazy     : enable_lazy_load
 *    Disable Crop    : disable_crop_image
 **/
if ( !function_exists( 'theme_resize_image' ) ) {
    function theme_resize_image(
        $attachment_id,
        $width,
        $height,
        $crop = false,
        $use_lazy = false,
        $placeholder = true,
        $class = ''
    )
    {
        if ( function_exists( 'ovic_resize_image' ) ) {
            return ovic_resize_image( $attachment_id, $width, $height, $crop, $use_lazy, $placeholder, $class );
        } else {
            $size_class = $width . 'x' . $height;
            $image_alt  = '';
            if ( !empty( $attachment_id ) ) {
                $image_src = wp_get_attachment_image_src( $attachment_id, $size_class );
                $image_alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
                $vt_image  = array(
                    'url'    => $image_src[0],
                    'width'  => $image_src[1],
                    'height' => $image_src[2],
                    'img'    => '<img class="attachment-' . esc_attr( $size_class ) . ' size-' . esc_attr( $size_class ) . ' ' . esc_attr( $class ) . '" src="' . esc_url( $image_src[0] ) . '" ' . image_hwstring( $image_src[1], $image_src[2] ) . ' alt="' . esc_attr( $image_alt ) . '">',
                );
            } else {
                $placeholder = get_theme_file_uri( '/assets/images/placeholder-img.jpg' );
                $vt_image    = array(
                    'url'    => $placeholder,
                    'width'  => $width,
                    'height' => $height,
                    'img'    => '<img class="attachment-' . esc_attr( $size_class ) . ' size-' . esc_attr( $size_class ) . ' ' . esc_attr( $class ) . '" src="' . esc_url( $placeholder ) . '" ' . image_hwstring( $width, $height ) . ' alt="' . esc_attr( $image_alt ) . '">',
                );
            }

            return $vt_image;
        }
    }
}
/**
 *
 * GET OPTIONS
 **/
if ( !function_exists( 'theme_file_options' ) ) {
    function theme_file_options( $path, $name, $is_block = false )
    {
        $layoutDir      = get_template_directory() . $path;
        $header_options = array();
        if ( is_dir( $layoutDir ) ) {
            $files = scandir( $layoutDir );
            if ( $files && is_array( $files ) ) {
                foreach ( $files as $file ) {
                    if ( $file != '.' && $file != '..' && $file != 'style' ) {
                        $fileInfo  = pathinfo( $file );
                        $file_data = get_file_data( $layoutDir . $file,
                            array(
                                'Name' => 'Name',
                            ) );
                        if ( isset( $fileInfo['extension'] ) && $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
                            if ( $file_data['Name'] != '' ) {
                                $file_name = $file_data['Name'];
                            } else {
                                $file_name = str_replace( array( '_', '-', 'content' ),
                                    array(
                                        ' ',
                                        ' ',
                                        '',
                                    ),
                                    $fileInfo['filename'] );
                            }
                            $preview = get_theme_file_uri( '/assets/images/placeholder.jpg' );
                            $file_id = $name != '' ? str_replace( "{$name}-", '', $fileInfo['filename'] ) : $fileInfo['filename'];
                            if ( is_file( get_template_directory() . "{$path}{$fileInfo['filename']}.jpg" ) ) {
                                $preview = get_theme_file_uri( "{$path}{$fileInfo['filename']}.jpg" );
                            }
                            if ( $is_block == true ) {
                                $header_options[ $file_id ] = ucwords( $file_name );
                            } else {
                                $header_options[ $file_id ] = array(
                                    'title'   => ucwords( $file_name ),
                                    'preview' => $preview,
                                );
                            }
                        }
                    }
                }
            }
        }

        return $header_options;
    }
}
if ( !function_exists( 'theme_footer_preview' ) ) {
    function theme_footer_preview()
    {
        $footer_preview = array(
            'none' => array(
                'title'   => esc_html__( 'None', 'ictu' ),
                'preview' => get_theme_file_uri( '/assets/images/placeholder.jpg' ),
            ),
        );
        $args           = array(
            'post_type'      => 'ovic_footer',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        );
        $posts          = get_posts( $args );
        if ( !empty( $posts ) ) {
            foreach ( $posts as $post ) {
                setup_postdata( $post );
                $url     = get_edit_post_link( $post->ID );
                $preview = get_theme_file_uri( '/assets/images/placeholder.jpg' );
                if ( has_post_thumbnail( $post ) ) {
                    $preview = wp_get_attachment_image_url( get_post_thumbnail_id( $post->ID ), 'full' );
                }
                $footer_preview[ $post->post_name ] = array(
                    'title'   => $post->post_title,
                    'preview' => $preview,
                    'url'     => $url,
                );
            }
        }
        wp_reset_postdata();

        return $footer_preview;
    }
}
if ( !function_exists( 'theme_social_option' ) ) {
    function theme_social_option( $reverse = false )
    {
        $socials     = array();
        $all_socials = get_theme_option( 'user_all_social' );
        if ( !empty( $all_socials ) ) {
            foreach ( $all_socials as $key => $social ) {
                if ( $reverse ) {
                    $socials[ $social['title_social'] ] = $key;
                } else {
                    $socials[ $key ] = $social['title_social'];
                }
            }
        }

        return $socials;
    }
}
if ( !function_exists( 'theme_page_layout' ) ) {
    function theme_page_layout()
    {
        if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
            $sidebar_name   = 'dokan-no-sidebar';
            $sidebar_layout = 'full';
        } elseif ( is_page() ) {
            $sidebar_name   = theme_option_meta( '_custom_page_side_options', null, 'page_sidebar', 'widget-area' );
            $sidebar_layout = theme_option_meta( '_custom_page_side_options', null, 'sidebar_page_layout', 'right' );
            if ( !is_active_sidebar( $sidebar_name ) ) {
                $sidebar_layout = 'full';
            }
        } else {
            $sidebar_layout = get_theme_option( 'sidebar_blog_layout', 'right' );
            $sidebar_name   = get_theme_option( 'blog_used_sidebar', 'widget-area' );
            if ( is_single() ) {
                $sidebar_layout = get_theme_option( 'sidebar_single_layout', 'right' );
                $sidebar_name   = get_theme_option( 'single_used_sidebar', 'widget-area' );
            } else {
                $term = get_queried_object();
                if ( $term ) {
                    $need_overwrite_sidebar = absint( get_term_meta( $term->term_id, 'accept_overwrite_sidebar', true ) );
                    $custom_sidebar         = get_term_meta( $term->term_id, 'custom_sidebar', true );
                    if ( $need_overwrite_sidebar === 1 && $custom_sidebar ) {
                        $sidebar_name = $custom_sidebar;
                    }
                }
            }
            if ( !is_active_sidebar( $sidebar_name ) ) {
                $sidebar_layout = 'full';
            }
        }

        return array(
            'sidebar' => $sidebar_name,
            'layout'  => $sidebar_layout,
        );
    }
}
if ( !function_exists( 'theme_breadcrumb' ) ) {
    function theme_breadcrumb()
    {
        do_action(
            'ovic_breadcrumb',
            array(
                'before' => '<div class="breadcrumb-wrap">',
                'after'  => '</div>',
            )
        );
    }
}

if ( !function_exists( 'theme_page_title' ) ) {
    function theme_page_title()
    {
        if ( is_home() ) : ?>
            <?php if ( is_front_page() ) : ?>
                <h1 class="page-title entry-title">
                    <span><?php esc_html_e( 'Latest Posts', 'ictu' ); ?></span>
                </h1>
            <?php else: ?>
                <h1 class="page-title entry-title">
                    <span><?php single_post_title(); ?></span>
                </h1>
            <?php endif; ?>
        <?php elseif ( is_page() ) : ?>
            <h1 class="page-title entry-title">
                <span><?php single_post_title(); ?></span>
            </h1>
        <?php elseif ( is_single() ) : ?>
            <h1 class="page-title entry-title">
                <span><?php single_post_title(); ?></span>
            </h1>
        <?php else: ?>
            <?php if ( is_search() ) : ?>
                <h1 class="page-title entry-title">
                    <span><?php printf( esc_html__( 'Search Results for: "%s"', 'ictu' ), '<span class="search-results">' . get_search_query() . '</span>' ); ?></span>
                </h1>
            <?php else: ?>
                <h1 class="page-title entry-title">
                    <span><?php the_archive_title( '', '' ); ?></span>
                </h1>
                <?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
            <?php endif; ?>
        <?php endif;
    }
}

if ( !function_exists( 'theme_title' ) ) {
    function theme_title()
    {
        if ( is_home() ) : ?>
            <?php if ( is_front_page() ) : ?>
                <h1 class="theme-title title">
                    <span><?php esc_html_e( 'Latest Posts', 'ictu' ); ?></span>
                </h1>
            <?php else: ?>
                <h1 class="theme-title title">
                    <span><?php single_post_title(); ?></span>
                </h1>
            <?php endif; ?>
        <?php elseif ( is_page() ) : ?>
            <h1 class="theme-title title">
                <span><?php single_post_title(); ?></span>
            </h1>
        <?php elseif ( is_single() ) : ?>
            <h2 class="theme-title theme-title__single title">
                <span><?php echo esc_html( apply_filters( 'first_category_name_of_post', '' ) ); ?></span>
            </h2>
        <?php else: ?>
            <?php if ( is_search() ) : ?>
                <h1 class="theme-title title">
                    <!-- <span>--><?php //printf(__('Search Results for: "%s"', 'ictu'), '<span class="search-results">' . get_search_query() . '</span>'); ?><!--</span>-->
                    <span><span><?php _e( 'Search Results', 'ictu' ) ?></span></span>
                </h1>
            <?php else: ?>
                <h1 class="theme-title theme-title__archive title">
                    <span><?php the_archive_title( '', '' ); ?></span>
                </h1>
            <?php endif; ?>
        <?php endif;
    }
}
if ( !function_exists( 'theme_share_social' ) ) {
    function theme_share_social( $id = null )
    {
        theme_get_template( "templates-parts/share-button.php", array( 'id' => $id, ) );
    }
}
if ( !function_exists( 'theme_share_social2' ) ) {
    function theme_share_social2( $id = null )
    {
        get_template_part( "templates-parts/share-button2", null, array( 'id' => $id ) );
    }
}
if ( !function_exists( 'theme_wordpress_menus' ) ) {
    function theme_wordpress_menus( $return = 'name' )
    {
        $locations = array();
        $menus     = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
        if ( !empty( $menus ) ) {
            foreach ( $menus as $menu ) {
                if ( $return == 'name' ) {
                    $locations[ $menu->name ] = $menu->name;
                } elseif ( $return == 'slug' ) {
                    $locations[ $menu->slug ] = $menu->name;
                } else {
                    $locations[ $menu->name ] = $menu->slug;
                }
            }
        }

        return $locations;
    }
}
if ( !function_exists( 'theme_author_social' ) ) {
    function theme_author_social( $author_id )
    {
        theme_get_template( "templates-parts/author-social.php", array( 'author_id' => $author_id, ) );
    }
}
/**
 *
 * ACTION
 * $functions = array(
 *      array( {action},{tag}, {callback},{priority}, {arg} ),
 *      array( {action},{tag}, {callback},{priority}, {arg} ),
 * );
 */
if ( !function_exists( 'theme_add_action' ) ) {
    function theme_add_action( $functions, $reverse = false )
    {
        if ( !empty( $functions ) ) {
            foreach ( $functions as $function ) {
                $actions  = $function[0];
                $priority = isset( $function[3] ) ? $function[3] : 10;
                $args     = isset( $function[4] ) ? $function[4] : 1;
                if ( $reverse ) {
                    $search  = 'add_';
                    $replace = 'remove_';
                    if ( strpos( $actions, 'add_' ) === false ) {
                        $search  = 'remove_';
                        $replace = 'add_';
                    }
                    $actions = str_replace( $search, $replace, $actions );
                }
                call_user_func( $actions, $function[1], $function[2], $priority, $args );
            }
        }
    }
}
if ( !function_exists( 'theme_set_post_views' ) ) {
    function theme_set_post_views( $postID = false, $post_type = 'post' )
    {
        if ( !$postID ) {
            $postID = get_the_ID();
        }
        if ( get_post_type( $postID ) === $post_type ) {
            $count_key = "theme_{$post_type}_views_count";
            $count     = get_post_meta( $postID, $count_key, true );
            if ( $count == '' ) {
                delete_post_meta( $postID, $count_key );
                add_post_meta( $postID, $count_key, '0' );
            } else {
                $count++;
                update_post_meta( $postID, $count_key, $count );
            }
        }
    }
}
if ( !function_exists( 'theme_get_post_views' ) ) {
    function theme_get_post_views( $postID = false, $post_type = 'post' )
    {
        if ( !$postID ) {
            $postID = get_the_ID();
        }
        $count_key = "theme_{$post_type}_views_count";
        $count     = get_post_meta( $postID, $count_key, true );
        if ( $count == '' ) {
            delete_post_meta( $postID, $count_key );
            add_post_meta( $postID, $count_key, '0' );
            echo '0';
        }
        echo theme_number_format_short( $count );
    }
}
if ( !function_exists( 'theme_track_post_views' ) ) {
    function theme_track_post_views( $post_id )
    {
        if ( is_single() ) {
            if ( empty ( $post_id ) ) {
                global $post;
                $post_id = $post->ID;
            }
            theme_set_post_views( $post_id );
        }
    }

    add_action( 'wp_head', 'theme_track_post_views' );
}
/**
 * @param $n
 *
 * @return string
 * Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
 */
if ( !function_exists( 'theme_number_format_short' ) ) {
    function theme_number_format_short( $n )
    {
        if ( $n >= 0 && $n < 1000 ) {
            // 1 - 999
            $n_format = floor( $n );
            $suffix   = '';
        } elseif ( $n >= 1000 && $n < 1000000 ) {
            // 1k-999k
            $n_format = floor( $n / 1000 );
            $suffix   = 'K+';
        } elseif ( $n >= 1000000 && $n < 1000000000 ) {
            // 1m-999m
            $n_format = floor( $n / 1000000 );
            $suffix   = 'M+';
        } elseif ( $n >= 1000000000 && $n < 1000000000000 ) {
            // 1b-999b
            $n_format = floor( $n / 1000000000 );
            $suffix   = 'B+';
        } elseif ( $n >= 1000000000000 ) {
            // 1t+
            $n_format = floor( $n / 1000000000000 );
            $suffix   = 'T+';
        }

        return !empty( $n_format ) ? $n_format . $suffix : 0;
    }
}
if ( !function_exists( 'theme_get_form_newsletter' ) ) {
    function theme_get_form_newsletter( $visual_composer = false )
    {
        if ( $visual_composer ) {
            $list_form[ esc_html__( 'Default Form', 'ictu' ) ] = '0';
        } else {
            $list_form['0'] = esc_html__( 'Default Form', 'ictu' );
        }
        if ( function_exists( 'mc4wp_show_form' ) ) {
            $args  = array(
                'posts_per_page' => -1,
                'post_type'      => 'mc4wp-form',
                'post_status'    => 'publish',
                'fields'         => 'ids',
            );
            $posts = get_posts( $args );
            if ( $posts ) {
                foreach ( $posts as $post_id ) {
                    $post_id = intval( $post_id );
                    $title   = get_the_title( $post_id );
                    if ( $visual_composer ) {
                        $list_form[ $title ] = $post_id;
                    } else {
                        $list_form[ $post_id ] = $title;
                    }
                }
            }
        }

        return $list_form;
    }
}

if ( !function_exists( 'theme_mobile_menu' ) ) {
    function theme_mobile_menu( $menu_locations, $default = 'primary' )
    {
        if ( !empty( $menu_locations ) ) {
            $count       = 0;
            $mobile_menu = '';
            $array_menus = array();
            $array_child = array();
            $mobile_menu .= "<div class='ovic-menu-clone-wrap'>";
            $mobile_menu .= "<div class='ovic-menu-panels-actions-wrap'>";
            $mobile_menu .= "<span class='ovic-menu-current-panel-title'>" . esc_html__( 'Main Menu', 'ictu' ) . "</span>";
            $mobile_menu .= "<a href='#' class='ovic-menu-close-btn ovic-menu-close-panels'>x</a>";
            $mobile_menu .= "</div>";
            $mobile_menu .= "<div class='ovic-menu-panels'>";
            foreach ( (array)$menu_locations as $location ) {
                $menu_items = array();
                if ( wp_get_nav_menu_items( $location ) ) {
                    $menu_items = wp_get_nav_menu_items( $location );
                } else {
                    $locations = get_nav_menu_locations();
                    if ( isset( $locations[ $default ] ) ) {
                        $menu       = wp_get_nav_menu_object( $locations[ $default ] );
                        $menu_items = wp_get_nav_menu_items( $menu->name );
                    }
                }
                if ( !empty( $menu_items ) ) {
                    foreach ( $menu_items as $key => $menu_item ) {
                        $parent_id = $menu_item->menu_item_parent;
                        /* REND CLASS */
                        $classes   = empty( $menu_item->classes ) ? array() : (array)$menu_item->classes;
                        $classes[] = 'menu-item';
                        $classes[] = 'menu-item-' . $menu_item->ID;
                        /* REND ARGS */
                        $array_menus[ $parent_id ][ $menu_item->ID ] = array(
                            'url'   => $menu_item->url,
                            'class' => $classes,
                            'title' => $menu_item->title,
                        );
                        if ( $parent_id > 0 ) {
                            $array_child[] = $parent_id;
                        }
                    }
                }
            }
            foreach ( $array_menus as $parent_id => $menus ) {
                $main_id = uniqid( 'main-' );
                if ( $count == 0 ) {
                    $mobile_menu .= "<div id='ovic-menu-panel-{$main_id}' class='ovic-menu-panel ovic-menu-panel-main'>";
                } else {
                    $mobile_menu .= "<div id='ovic-menu-panel-{$parent_id}' class='ovic-menu-panel ovic-menu-sub-panel ovic-menu-hidden'>";
                }
                $mobile_menu .= "<ul class='depth-{$count}'>";
                foreach ( $menus as $id => $menu ) {
                    $class_menu  = join( ' ', $menu['class'] );
                    $mobile_menu .= "<li id='ovic-menu-clone-menu-item-{$id}' class='{$class_menu}'>";
                    if ( in_array( $id, $array_child ) ) {
                        $mobile_menu .= "<a class='ovic-menu-next-panel' href='#ovic-menu-panel-{$id}' data-target='#ovic-menu-panel-{$id}'></a>";
                    }
                    $mobile_menu .= "<a href='{$menu['url']}'>{$menu['title']}</a>";
                    $mobile_menu .= "</li>";
                }
                $mobile_menu .= "</ul></div>";
                $count++;
            }
            $mobile_menu .= "</div></div>";
            /*
             * Export Html
             * */
            echo wp_specialchars_decode( $mobile_menu );
        }
    }
}

if ( !function_exists( 'theme_get_svg_image' ) ) {
    function theme_get_svg_image( $width = 300, $height = 300 ) : string
    {
//        return rawurldecode("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22{$width}%22%20height%3D%22{$height}%22%20viewBox%3D%220%200%20{$width}%20{$height}%22%3E%3C%2Fsvg%3E");
        return esc_attr( "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22{$width}%22%20height%3D%22{$height}%22%20viewBox%3D%220%200%20{$width}%20{$height}%22%3E%3C%2Fsvg%3E" );
    }
}

if ( !function_exists( 'is_boolean' ) ) {
    function is_boolean( $string )
    {
        $string = strtolower( $string );

        return ( in_array( $string, array( "true", "false", "1", "0", "yes", "no" ), true ) );
    }
}

if ( !function_exists( 'preg_replace_callback' ) ) {
    function preg_replace_callback( $matches )
    {
        $name = str_replace( array( '"', ':' ), array( '', '' ), $matches[0] );

        if ( is_numeric( $name ) || is_boolean( $name ) === true ) {
            return ":{$name}";
        }

        return $matches[0];
    }
}
if ( !function_exists( 'generate_style' ) ) {
    function generate_style( $atts, $prefix = 'slides_' )
    {
        $style = '';

        if ( isset( $atts["{$prefix}to_show"] ) ) {
            $style .= '--show:' . (int)$atts["{$prefix}to_show"] . ';';
        }
        if ( isset( $atts["{$prefix}margin"] ) ) {
            $style .= '--margin:' . (int)$atts["{$prefix}margin"] . 'px;';
        }
        if ( isset( $atts["slidesToShow"] ) ) {
            $style .= '--show:' . (int)$atts["slidesToShow"] . ';';
        }
        if ( isset( $atts["slidesMargin"] ) ) {
            $style .= '--margin:' . (int)$atts["slidesMargin"] . 'px;';
        }

        if ( !empty( $atts["{$prefix}responsive"] ) ) {
            foreach ( $atts["{$prefix}responsive"] as $tab ) {
                $breakpoint = !empty( $tab['breakpoint'] ) ? (int)$tab['breakpoint'] : (int)$tab['screen'];
                $show       = !empty( $tab['settings']['slidesToShow'] ) ? (int)$tab['settings']['slidesToShow'] : (int)@$tab['show'];
                $margin     = !empty( $tab['settings']['slidesMargin'] ) ? (int)$tab['settings']['slidesMargin'] : (int)@$tab['margin'];

                if ( $breakpoint <= 1500 && $breakpoint > 1200 ) {
                    $style .= '--show-laptop:' . $show . ';--margin-laptop:' . $margin . 'px;';
                } elseif ( $breakpoint <= 1200 && $breakpoint > 992 ) {
                    $style .= '--show-ipad:' . $show . ';--margin-ipad:' . $margin . 'px;';
                } elseif ( $breakpoint <= 992 && $breakpoint > 768 ) {
                    $style .= '--show-landscape:' . $show . ';--margin-landscape:' . $margin . 'px;';
                } elseif ( $breakpoint <= 768 && $breakpoint > 480 ) {
                    $style .= '--show-portrait:' . $show . ';--margin-portrait:' . $margin . 'px;';
                } elseif ( $breakpoint <= 480 ) {
                    $style .= '--show-mobile:' . $show . ';--margin-mobile:' . $margin . 'px;';
                }
            }
        }

        return $style;
    }
}

if ( !function_exists( 'ictu_generate_carousel' ) ) {
    function ictu_generate_carousel( $atts, $prefix = 'slides_', $echo = true )
    {
        if ( !empty( $atts['carousel'] ) && is_array( $atts['carousel'] ) ) {
            $data_slick = preg_replace_callback(
                '/:"[^"]+"/',
            );
            if ( !$echo ) {
                return json_decode( $data_slick, true );
            }

            return htmlspecialchars( ' data-slick=' . $data_slick . ' style=' . generate_style( $atts['carousel'], '' ) . ' ' );
        }

        $responsive = array();
        $settings   = array(
            'slidesToShow' => 4,
            'infinite'     => false,
            'arrows'       => false,
        );
        if ( isset( $atts["{$prefix}rows"] ) ) {
            $settings['rows'] = (int)$atts["{$prefix}rows"];
        }
        if ( isset( $atts["{$prefix}to_show"] ) ) {
            $settings['slidesToShow'] = (int)$atts["{$prefix}to_show"];
        }
        if ( isset( $atts["{$prefix}margin"] ) ) {
            $settings['slidesMargin'] = (int)$atts["{$prefix}margin"];
        }
        if ( isset( $atts["{$prefix}autoplay_speed"] ) ) {
            $settings['autoplaySpeed'] = (int)$atts["{$prefix}autoplay_speed"];
        }
        if ( isset( $atts["{$prefix}speed"] ) ) {
            $settings['speed'] = (int)$atts["{$prefix}speed"];
        }
        if ( isset( $atts["{$prefix}autoplay"] ) && $atts["{$prefix}autoplay"] == 'yes' ) {
            $settings['autoplay'] = true;
        }
        if ( isset( $atts["{$prefix}infinite"] ) && $atts["{$prefix}infinite"] == 'yes' ) {
            $settings['infinite'] = true;
        }
        if ( isset( $atts["{$prefix}vertical"] ) && $atts["{$prefix}vertical"] == 'yes' ) {
            $settings['vertical'] = true;
        }
        if ( isset( $atts["{$prefix}direction"] ) && $atts["{$prefix}direction"] == 'rtl' ) {
            $settings['rtl'] = true;
        }
        if ( isset( $atts["{$prefix}fade"] ) && $atts["{$prefix}fade"] == 'yes' ) {
            $settings['fade'] = true;
        }
        if ( isset( $atts["{$prefix}center_mode"] ) && $atts["{$prefix}center_mode"] == 'yes' ) {
            $settings['centerMode'] = true;
        }
        if ( isset( $atts["{$prefix}variable_width"] ) && $atts["{$prefix}variable_width"] == 'yes' ) {
            $settings['variableWidth'] = true;
        }
        if ( isset( $atts["{$prefix}adaptive_height"] ) && $atts["{$prefix}adaptive_height"] == 'yes' ) {
            $settings['adaptiveHeight'] = true;
        }
        if ( !empty( $atts["{$prefix}navigation"] ) ) {
            if ( $atts["{$prefix}navigation"] == 'both' || $atts["{$prefix}navigation"] == 'arrows' ) {
                $settings['arrows'] = true;
            }
            if ( $atts["{$prefix}navigation"] == 'both' || $atts["{$prefix}navigation"] == 'dots' ) {
                $settings['dots'] = true;
            }
        }
        if ( !empty( $atts["{$prefix}responsive"] ) ) {
            foreach ( $atts["{$prefix}responsive"] as $tab ) {
                $vertical           = false;
                $data               = array();
                $data['breakpoint'] = (int)$tab['screen'];
                $data['settings']   = array();

                if ( !empty( $tab['show'] ) ) {
                    $data['settings']['slidesToShow'] = (int)$tab['show'];
                }
                if ( !empty( $tab['margin'] ) ) {
                    $data['settings']['slidesMargin'] = (int)$tab['margin'];
                }
                if ( !empty( $tab['rows'] ) ) {
                    $data['settings']['rows'] = (int)$tab['rows'];
                }
                if ( !empty( $tab['vertical'] ) && $tab['vertical'] == 'yes' ) {
                    $vertical = true;
                }
                if ( !empty( $tab['navigation'] ) ) {
                    if ( $tab['navigation'] == 'arrows' ) {
                        $data['settings']['arrows'] = true;
                        $data['settings']['dots']   = false;
                    }
                    if ( $tab['navigation'] == 'dots' ) {
                        $data['settings']['dots']   = true;
                        $data['settings']['arrows'] = false;
                    }
                    if ( $tab['navigation'] == 'both' ) {
                        $data['settings']['dots']   = true;
                        $data['settings']['arrows'] = true;
                    }
                    if ( $tab['navigation'] == 'none' ) {
                        $data['settings']['dots']   = false;
                        $data['settings']['arrows'] = false;
                    }
                }
                $data['settings']['vertical'] = $vertical;

                $responsive[] = $data;
            }
        }

        $data_slick = array_merge( $settings, array(
                'responsive' => array_values( $responsive )
            )
        );

        if ( $echo == false ) {
            $data_slick = preg_replace_callback(
                '/:"[^"]+"/',
            );

            return json_decode( $data_slick, true );
        }

        return htmlspecialchars( ' data-slick=' . json_encode( $data_slick ) . ' style=' . generate_style( $atts, $prefix ) . ' ' );
    }
}
