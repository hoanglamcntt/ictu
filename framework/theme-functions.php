<?php defined( 'ABSPATH' ) || exit;

add_filter( 'ovic_menu_toggle_mobile', '__return_false' );
add_filter( 'elementor/icons_manager/native', 'theme_elementor_icons' );
//add_filter('wp_nav_menu_items', 'theme_edit_head_menu', 10, 2);

/**
 *
 * HEADER TEMPLATES
 */
if ( !function_exists( 'theme_header_template' ) ) {
    function theme_header_template()
    {
        $layout = get_theme_option( 'header_template', 'style-01' );
        get_template_part( 'templates/headers/header', $layout );
    }
}

if ( !function_exists( 'theme_header_search_template' ) ) {
    function theme_header_search_template()
    {
        get_template_part( 'templates-parts/header', 'search' );
    }
}

if ( !function_exists( 'theme_header_contacts_template' ) ) {
    function theme_header_contacts_template()
    {
        get_template_part( 'templates-parts/header', 'contacts' );
    }
}

add_action( 'ovic_after_html_mobile_menu', 'theme_header_contacts_template', 10 );

if ( !function_exists( 'theme_page_banner_template' ) ) {
    function theme_page_banner_template()
    {
        get_template_part( 'templates-parts/page', 'banner' );
    }
}

/**
 *
 * LOGO
 */
if ( !function_exists( 'get_theme_logo' ) ) {
    function get_theme_logo( $organize_name = false )
    {
        $logo_url          = get_theme_file_uri( '/assets/images/logo.png' );
        $logo_link         = apply_filters( 'theme_get_link_logo', home_url( '/' ) );
        $logo              = get_theme_option( 'logo', 0 );
        $tpl_organize_name = get_theme_option( 'organize_name', '' );
        if ( $logo != '' ) {
            $logo_url = wp_get_attachment_image_url( $logo, 'full' );
            $html     = '<a href="' . esc_url( $logo_link ) . '"><figure><img alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '" class="_rw" /></figure></a>';
        } else {
            $html = '<a class="logo-text" href="' . esc_url( $logo_link ) . '">logo</a>';
        }
        if ( $organize_name && $tpl_organize_name ) {
            $html .= '<h2>' . wp_specialchars_decode( $tpl_organize_name ) . '</h2>';
        }
        echo apply_filters( 'theme_site_logo', $html );
    }
}

if ( !function_exists( 'get_sticky_logo' ) ) {
    function get_sticky_logo()
    {
        $sticky_logo_id = get_theme_option( 'sticky_logo', 0 );
        $html           = '';
        if ( $sticky_logo_id ) {
            $sticky_logo = wp_get_attachment_image_url( $sticky_logo_id, 'full' );
            $html        = '<a class="sticky-logo"  href="' . esc_url( home_url( '/' ) ) . '"><figure><img alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $sticky_logo ) . '" class="_rw" /></figure></a>';
        }
        echo apply_filters( 'sticky_logo', $html );
    }
}

if ( !function_exists( 'theme_get_post_meta' ) ) {
    function theme_get_post_meta( $post_id, $key, $default = "" )
    {
        $meta = get_post_meta( $post_id, $key, true );
        if ( $meta != '' ) {
            return $meta;
        }

        return $default;
    }
}

if ( !function_exists( 'theme_file_options' ) ) {
    function theme_file_options( $path, $name )
    {
        $layoutDir      = get_template_directory() . $path;
        $header_options = array();
        if ( is_dir( $layoutDir ) ) {
            $files = scandir( $layoutDir );
            if ( $files && is_array( $files ) ) {
                foreach ( $files as $file ) {
                    if ( $file != '.' && $file != '..' && $file != 'style' ) {
                        $fileInfo  = pathinfo( $file );
                        $file_data = get_file_data( $layoutDir . $file, array( 'Name' => 'Name', ) );
                        if ( isset( $fileInfo['extension'] ) && $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
                            if ( $file_data['Name'] != '' ) {
                                $file_name = $file_data['Name'];
                            } else {
                                $file_name = str_replace(
                                    array( '_', '-', 'content' ),
                                    array( ' ', ' ', '' ),
                                    $fileInfo['filename']
                                );
                            }
                            $preview = get_theme_file_uri( '/assets/images/placeholder.jpg' );
                            $file_id = $name != '' ? str_replace( "{$name}-", '', $fileInfo['filename'] ) : $fileInfo['filename'];
                            if ( is_file( get_template_directory() . "{$path}{$fileInfo['filename']}.jpg" ) ) {
                                $preview = get_theme_file_uri( "{$path}{$fileInfo['filename']}.jpg" );
                            }
                            $header_options[ $file_id ] = array(
                                'title'   => ucwords( $file_name ),
                                'preview' => $preview,
                            );
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
                'preview' => get_theme_file_uri( '/assets/images/placeholder.jpg' )
            )
        );
        $posts          = get_posts( array( 'post_type' => 'ovic_footer', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
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

if ( !function_exists( 'theme_product_options' ) ) {
    function theme_product_options( $allow = 'Theme Option', $image_select = false, $is_block = false )
    {
        $layoutDir       = get_template_directory() . '/woocommerce/product-style/';
        $product_options = array();
        if ( is_dir( $layoutDir ) ) {
            $files = scandir( $layoutDir );
            if ( $files && is_array( $files ) ) {
                foreach ( $files as $file ) {
                    if ( $file != '.' && $file != '..' ) {
                        $fileInfo  = pathinfo( $file );
                        $file_data = get_file_data(
                            $layoutDir . $file,
                            array(
                                'Name'         => 'Name',
                                'Theme Option' => 'Theme Option',
                                'Shortcode'    => 'Shortcode',
                            )
                        );
                        $file_name = str_replace( 'content-product-', '', $fileInfo['filename'] );
                        if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' && $file_data[ $allow ] == 'true' ) {
                            $preview = get_theme_file_uri( 'woocommerce/product-style/content-product-' . $file_name . '.jpg' );
                            if ( $file_data['Name'] != '' ) {
                                $file_title = $file_data['Name'];
                            } else {
                                $file_title = str_replace( array( '_', '-', 'content' ), array( ' ', ' ', '' ), $fileInfo['filename'] );
                            }
                            if ( $image_select ) {
                                $product_options[ $file_name ] = $preview;
                            } elseif ( $is_block ) {
                                $product_options[ $file_name ] = $file_title;
                            } else {
                                $product_options[ $file_name ] = array(
                                    'title'   => $file_title,
                                    'preview' => $preview,
                                );
                            }
                        }
                    }
                }
            }
        }
        if ( empty( $product_options ) ) {
            $product_options['no-product'] = array(
                'title' => esc_html__( 'No Product Found', 'ictu' ),
            );
        }

        return $product_options;
    }
}

if ( !function_exists( 'theme_edit_head_menu' ) ) {
    function theme_edit_head_menu( $items, $args )
    {
        if ( $args->theme_location == 'header' ) {
            ob_start();
            get_template_part( 'templates-parts/header', 'user-link' );
            $user_link_template = ob_get_clean();
            $menus_item         = $items;
            $menus_item         .= "<li class=\"menu-item\">{$user_link_template}</li>";
            $items              = $menus_item;
        }

        return $items;
    }
}

if ( !function_exists( 'theme_danh_muc_an_pham' ) ) {
    function theme_danh_muc_an_pham()
    {
        $list_cat = get_theme_option( 'vertical_categories', [] );
        ob_start(); ?>
        <a href="#" class="header__btn-open"><span>Danh mục ấn phẩm</span><i class="fa fa-bars" aria-hidden="true"></i></a>
        <div class="in-thai-nguyen-vertical-categories">
            <?php if ( is_array( $list_cat ) && !empty( $list_cat ) ): ?>
                <ul class="in-thai-nguyen-vertical-categories__list">
                    <?php foreach ( $list_cat as $list ): ?>
                        <?php if ( $list['title'] ): ?>
                            <?php $link = $list['link'] ? $list['link'] : '#' ?>
                            <?php $color = $list['color'] ? $list['color'] : '#f9be1f' ?>
                            <li class="in-thai-nguyen-vertical-categories__elm --elm-width-<?php echo esc_attr( $list['width'] ) ?>">
                                <div class="in-thai-nguyen-vertical-categories__block-container" style="border-top-color: <?php echo esc_attr( $color ) ?>">
                                    <a href="<?php echo esc_attr( $link ) ?>" class="in-thai-nguyen-vertical-categories__block-title"><?php echo esc_html( $list['title'] ) ?></a>
                                    <?php if ( is_array( $list['list'] ) && !empty( $list['list'] ) ): ?>
                                        <ul class="in-thai-nguyen-vertical-categories__list-cat">
                                            <?php foreach ( $list['list'] as $cat ): ?>
                                                <?php $term = get_term_by( 'id', $cat['cat_id'], 'product_cat', 'ARRAY_A' ); ?>
                                                <?php if ( $term ): ?>
                                                    <?php $thumbnail_id = get_term_meta( $term['term_id'], 'thumbnail_id', true ); ?>
                                                    <?php $_img = wp_get_attachment_image_src( $thumbnail_id, 'full' ); ?>
                                                    <?php $src = $_img && $_img[0] ? $_img[0] : get_theme_file_uri( '/assets/images/category-image.png' ); ?>
                                                    <?php $term_link = get_term_link( $term['term_id'] ); ?>
                                                    <li class="in-thai-nguyen-vertical-categories__cat-element">
                                                        <a href="<?php echo !is_wp_error( $term_link ) ? esc_url( $term_link ) : '#' ?>" class="in-thai-nguyen-vertical-categories__cat-link">
                                                            <img class="in-thai-nguyen-vertical-categories__cat-image" src="<?php echo esc_url( $src ) ?>" alt="<?php echo esc_url( $term['description'] ) ?>">
                                                            <span class="in-thai-nguyen-vertical-categories__cat-name"><?php echo esc_html( $term['name'] ) ?></span>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
        echo wp_specialchars_decode( ob_get_clean() );
    }
}

/**
 *
 * POPUP NEWSLETTER
 */
if ( !function_exists( 'theme_popup_newsletter' ) ) {
    function theme_popup_newsletter()
    {
        global $post;
        $enable = get_theme_option( 'enable_popup' );
        if ( $enable != 1 ) {
            return;
        }
        if ( isset( $_COOKIE['theme_disabled_popup_by_user'] ) && $_COOKIE['theme_disabled_popup_by_user'] == 'true' ) {
            return;
        }
        $page = (array)get_theme_option( 'popup_page' );
        if ( isset( $post->ID ) && is_array( $page ) && in_array( $post->ID, $page ) && $post->post_type == 'page' ) {
            wp_enqueue_style( 'magnific-popup' );
            wp_enqueue_script( 'magnific-popup' );
            get_template_part( 'templates-parts/popup', 'newsletter' );
        }
    }
}
if ( !function_exists( 'theme_get_css' ) ) {
    function theme_get_css( $atts )
    {
        $css = '';
        if ( !empty( $atts['background'] ) ) {
            if ( !empty( $atts['background']['background-color'] ) ) {
                $css .= "background-color:{$atts['background']['background-color']};";
            }

            if ( !empty( $atts['background']['background-image']['url'] ) ) {
                $css .= "background-image: url('{$atts['background']['background-image']['url']}');";
            }
            if ( !empty( $atts['background']['background-position'] ) ) {
                $css .= "background-position:{$atts['background']['background-position']};";
            }
            if ( !empty( $atts['background']['background-repeat'] ) ) {
                $css .= "background-repeat:{$atts['background']['background-repeat']};";
            }
            if ( !empty( $atts['background']['background-attachment'] ) ) {
                $css .= "background-attachment:{$atts['background']['background-attachment']};";
            }
            if ( !empty( $atts['background']['background-size'] ) ) {
                $css .= "background-size:{$atts['background']['background-size']};";
            }
        }
        if ( $atts['spacing'] ) {
            if ( !empty( $atts['spacing']['top'] ) ) {
                $css .= "padding-top:{$atts['spacing']['top']}{$atts['spacing']['unit']};";
            }
            if ( !empty( $atts['spacing']['right'] ) ) {
                $css .= "padding-right:{$atts['spacing']['right']}{$atts['spacing']['unit']};";
            }
            if ( !empty( $atts['spacing']['bottom'] ) ) {
                $css .= "padding-bottom:{$atts['spacing']['bottom']}{$atts['spacing']['unit']};";
            }
            if ( !empty( $atts['spacing']['left'] ) ) {
                $css .= "padding-left:{$atts['spacing']['left']}{$atts['spacing']['unit']};";
            }
        }
        if ( !empty( $atts['color'] ) ) {
            $css .= "color:{$atts['color']}";
        }

        return $css;
    }
}
/**
 *
 * CUSTOM MOBILE MENU
 */
if ( !function_exists( 'theme_before_mobile_menu' ) ) {
    function theme_before_mobile_menu()
    {
        $avatar_id    = null;
        $class        = 'login';
        $login        = wp_login_url();
        $current_user = wp_get_current_user();
        $author_name  = esc_html__( 'Guest', 'ictu' );
        $login_text   = esc_html__( 'Login', 'ictu' );
        $author_email = esc_html__( 'Example@email.com', 'ictu' );
        if ( class_exists( 'WooCommerce' ) && !empty( get_option( 'woocommerce_myaccount_page_id' ) ) ) {
            $login = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
        }
        $logout = $login;
        if ( is_user_logged_in() ) {
            $class        = 'logout';
            $avatar_id    = $current_user->ID;
            $author_email = $current_user->user_email;
            $author_name  = $current_user->display_name;
            $login_text   = esc_html__( 'Logout', 'ictu' );
            $logout       = wp_logout_url();
        }
        $avatar         = get_avatar_url( $avatar_id, array( 'size' => 60 ) );
        $background_url = get_theme_file_uri( 'assets/images/menu-mobile.jpg' );
        if ( function_exists( 'jetpack_photon_url' ) ) {
            $background_url = jetpack_photon_url( $background_url );
        }
        ?>
        <div class="head-menu-mobile" style="background-image: url(<?php echo esc_url( $background_url ); ?>)">
            <a href="<?php echo esc_url( $logout ) ?>"
               class="action <?php echo esc_attr( $class ); ?>">
                <span class="icon main-icon-enter"></span>
                <?php echo esc_html( $login_text ); ?>
            </a>
            <a href="<?php echo esc_url( $login ) ?>" class="avatar">
                <figure>
                    <img src="<?php echo esc_url( $avatar ) ?>"
                         alt="<?php echo esc_attr__( 'Avatar Mobile', 'ictu' ) ?>">
                </figure>
            </a>
            <div class="author">
                <a href="<?php echo esc_url( $login ) ?>"
                   class="name">
                    <?php echo esc_html( $author_name ); ?>
                    <span class="email"><?php echo esc_html( $author_email ); ?></span>
                </a>
            </div>
        </div>
        <?php
    }

//    add_action('ovic_before_html_mobile_menu', 'theme_before_mobile_menu', 10, 2);
}
if ( !function_exists( 'theme_after_mobile_menu' ) ) {
    function theme_after_mobile_menu()
    {
        $textarea = theme_option_meta( '_custom_metabox_theme_options', 'header_textarea', 'metabox_header_textarea' );
        if ( !empty( $textarea ) ) : ?>
            <div class="footer-menu-mobile">
                <div class="header-text">
                    <p><?php echo preg_replace( '/<\/?p\>/', "\n", $textarea ); ?></p>
                </div>
            </div>
        <?php endif;
    }

    add_action( 'ovic_after_html_mobile_menu', 'theme_after_mobile_menu', 10, 2 );
}
/**
 *
 * MEGAMENU ICON
 */
add_filter( 'ovic_field_icon_add_icons', 'theme_options_icons' );
if ( !function_exists( 'theme_options_icons' ) ) {
    function theme_options_icons( $icon )
    {
        $icon[] = array(
            'title' => 'Theme Icon',
            'icons' => array(
                "bio-icon1",
                "bio-icon2",
                "bio-icon3",
                "bio-icon4",
                "bio-icon5",
                "bio-icon6",
                "bio-icon7",
                "bio-icon8",
                "bio-icon9",
                "bio-icon10",
                "bio-icon11",
                "bio-icon12",
                "bio-icon13",
                "bio-icon14",
                "bio-icon15",
                "bio-icon16",
                "bio-icon17",
                "bio-icon18",
                "bio-icon19",
                "bio-icon20",
                "bio-icon21",
                "bio-icon22",
                "bio-icon23",
                "bio-icon24",
                "bio-icon25",
                "bio-icon26",
                "bio-icon27",
                "bio-icon28",
                "bio-icon29",
                "bio-icon30",
                "bio-icon31",
                "bio-icon32",
                "bio-icon33",
                "bio-icon34",
                "bio-icon35",
                "bio-icon36",
                "bio-icon37",
                "bio-icon38",
                "bio-icon39",
                "bio-icon40",
                "bio-icon41",
                "bio-icon42",
                "bio-icon43",
                "bio-icon44",
                "bio-icon45",
                "bio-icon46",
                "bio-icon47",
                "bio-icon48",
                "bio-icon51",
                "bio-icon52",
                "bio-icon54",
                "bio-icon55",
                "bio-icon56",
                "bio-icon57",
                "bio-icon58",
            ),
        );
        $icon[] = array(
            'title' => 'IcoFont',
            'icons' => array( "icofont-paper-plane" ),
        );

        return $icon;
    }
}
/**
 *
 * MEGAMENU ICON
 */
add_filter( 'ovic_menu_icons_setting', 'theme_megamenu_options_icons' );
if ( !function_exists( 'theme_megamenu_options_icons' ) ) {
    function theme_megamenu_options_icons()
    {
        return array(
            array( "bio-icon1" => "Icon 1" ),
            array( "bio-icon2" => "Icon 2" ),
            array( "bio-icon3" => "Icon 3" ),
            array( "bio-icon4" => "Icon 4" ),
            array( "bio-icon5" => "Icon 5" ),
            array( "bio-icon6" => "Icon 6" ),
            array( "bio-icon7" => "Icon 7" ),
            array( "bio-icon8" => "Icon 8" ),
            array( "bio-icon9" => "Icon 9" ),
            array( "bio-icon10" => "Icon 10" ),
            array( "bio-icon11" => "Icon 11" ),
            array( "bio-icon12" => "Icon 12" ),
            array( "bio-icon13" => "Icon 13" ),
            array( "bio-icon14" => "Icon 14" ),
            array( "bio-icon15" => "Icon 15" ),
            array( "bio-icon16" => "Icon 16" ),
            array( "bio-icon17" => "Icon 17" ),
            array( "bio-icon18" => "Icon 18" ),
            array( "bio-icon19" => "Icon 19" ),
            array( "bio-icon20" => "Icon 20" ),
            array( "bio-icon21" => "Icon 21" ),
            array( "bio-icon22" => "Icon 22" ),
            array( "bio-icon23" => "Icon 23" ),
            array( "bio-icon24" => "Icon 24" ),
            array( "bio-icon25" => "Icon 25" ),
            array( "bio-icon26" => "Icon 26" ),
            array( "bio-icon27" => "Icon 27" ),
            array( "bio-icon28" => "Icon 28" ),
            array( "bio-icon29" => "Icon 29" ),
            array( "bio-icon30" => "Icon 30" ),
            array( "bio-icon31" => "Icon 31" ),
            array( "bio-icon32" => "Icon 32" ),
            array( "bio-icon33" => "Icon 33" ),
            array( "bio-icon34" => "Icon 34" ),
            array( "bio-icon35" => "Icon 35" ),
            array( "bio-icon36" => "Icon 36" ),
            array( "bio-icon37" => "Icon 37" ),
            array( "bio-icon38" => "Icon 38" ),
            array( "bio-icon39" => "Icon 39" ),
            array( "bio-icon40" => "Icon 40" ),
            array( "bio-icon41" => "Icon 41" ),
            array( "bio-icon42" => "Icon 42" ),
            array( "bio-icon43" => "Icon 43" ),
            array( "bio-icon44" => "Icon 44" ),
            array( "bio-icon45" => "Icon 45" ),
            array( "bio-icon46" => "Icon 46" ),
            array( "bio-icon47" => "Icon 47" ),
            array( "bio-icon48" => "Icon 48" ),
            array( "bio-icon51" => "Icon 51" ),
            array( "bio-icon52" => "Icon 52" ),
            array( "bio-icon54" => "Icon 54" ),
            array( "bio-icon55" => "Icon 55" ),
            array( "bio-icon55" => "Icon 56" ),
            array( "bio-icon55" => "Icon 57" ),
            array( "bio-icon55" => "Icon 58" ),
            array( "icofont-paper-plane" => "IcoFont plane" ),
            array( "icofont-long-arrow-left" => "IcoFont arrow-left" ),
            array( "icofont-long-arrow-right" => "IcoFont arrow-right" ),
        );
    }
}

if ( !function_exists( 'theme_elementor_icons' ) ) {
    function theme_elementor_icons( $tabs )
    {
        $tabs['main-icon'] = [
            'name'          => 'main-icon',
            'label'         => esc_html__( 'Bio-Fonts', 'ictu' ),
            'url'           => get_theme_file_uri( '/assets/vendor/main-icon/style.css' ),
            'enqueue'       => [ get_theme_file_uri( '/assets/vendor/main-icon/style.css' ) ],
            'prefix'        => '',
            'displayPrefix' => 'far',
            'labelIcon'     => 'fab fa-font-awesome-alt',
            'ver'           => '3.0.0',
            'fetchJson'     => get_theme_file_uri( '/assets/vendor/main-icon/icons.json' ),
            'native'        => true,
        ];
        $tabs['icofont']   = [
            'name'          => 'icofont',
            'label'         => esc_html__( 'Bio-Icofont', 'ictu' ),
            'url'           => get_theme_file_uri( '/assets/vendor/icofont/style.min.css' ),
            'enqueue'       => [ get_theme_file_uri( '/assets/vendor/icofont/style.min.css' ) ],
            'prefix'        => '',
            'displayPrefix' => 'far',
            'labelIcon'     => 'fab fa-font-awesome-alt',
            'ver'           => '2.0.9',
            'fetchJson'     => get_theme_file_uri( '/assets/vendor/icofont/icons.json' ),
            'native'        => true,
        ];

        return $tabs;
    }
}

/**
 *
 * GET POST METABOX
 */
if ( !function_exists( 'theme_get_post_metabox' ) ) {
    function theme_get_post_metabox( $post_id, $field = null )
    {
        $post_meta = get_post_meta( $post_id, '_custom_metabox_post_options', true );
        if ( !$post_meta ) {
            return '';
        }

        if ( $field === null ) {
            return $post_meta;
        }

        return '';
    }
}

/**
 *
 * FOOTER
 */
add_action( 'theme_footer_content', 'theme_footer_template', 10 );
if ( !function_exists( 'theme_footer_template' ) ) {
    function theme_footer_template()
    {
        $style = get_theme_option( 'footer_template', 'style-01' );
        get_template_part( 'templates/footer/footer', $style );
    }
}

if ( !function_exists( 'theme_footer_social_template' ) ) {
    function theme_footer_social_template( $custom_class = '' )
    {
        $socials = get_theme_option( 'user_all_social', [] );
        if ( is_array( $socials ) && !empty( $socials ) ) {
            $class = $custom_class ? [ 'theme-social', $custom_class ] : [ 'theme-social' ]; ?>
            <ul class="<?php echo implode( ' ', $class ) ?>">
                <?php foreach ( $socials as $social ): ?>
                    <?php if ( $social['icon_social'] ): ?>
                        <?php $link = $social['link_social'] ? $social['link_social'] : '#' ?>
                        <li>
                            <a href="<?php echo esc_url( $link ); ?>" title="<?php echo esc_attr( $social['title_social'] ) ?>">
                                <i class="<?php echo esc_attr( $social['icon_social'] ); ?>"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <?php
        }
    }
}

if ( !function_exists( 'theme_add_sidebar_head' ) ) {
    function theme_add_sidebar_head()
    {
        ?>
        <div id="sidebar-head-section">
            <span class="sidebar-head-section__title"><?php _e( 'Sidebar Areal', 'ictu' ) ?></span>
            <button type="button" class="sidebar-head-section__button">&times;</button>
        </div>
        <?php
    }

    add_action( 'theme_before_dynamic_sidebar', 'theme_add_sidebar_head', 10 );
}

if ( !function_exists( 'theme_add_sidebar_footer' ) ) {
    function theme_add_sidebar_footer()
    {
        ?>
        <div id="sidebar-foot-section">
            <button type="button" class="sidebar-foot-section__button">
                <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
            </button>
        </div>
        <?php
    }
//    add_action('theme_after_dynamic_sidebar', 'theme_add_sidebar_footer', 10);
}

if ( !function_exists( 'theme_breadcrumb_trail_object' ) ) {
    function theme_breadcrumb_trail_object( $crumbs, $args )
    {
        if ( is_category() || is_tag() || is_date() ) {
            unset( $crumbs[1] );
        }
        return $crumbs;
    }

    add_filter( 'breadcrumb_trail_items', 'theme_breadcrumb_trail_object', 20, 2 );
}

if ( !function_exists( 'ictu_document_type' ) ) {
    function ictu_document_type() : array
    {
        return array(
            'vb0'  => __( 'Khác', 'ictu' ),
            'vb1'  => __( 'Mẫu biểu', 'ictu' ),
            'vb2'  => __( 'Quy trình', 'ictu' ),
            'vb3'  => __( 'Công văn', 'ictu' ),
            'vb4'  => __( 'Quyết định', 'ictu' ),
            'vb5'  => __( 'Quy định', 'ictu' ),
            'vb6'  => __( 'Quy chế', 'ictu' ),
            'vb7'  => __( 'Nghị quyết', 'ictu' ),
            'vb8'  => __( 'Thông cáo', 'ictu' ),
            'vb9'  => __( 'Chỉ thị', 'ictu' ),
            'vb10' => __( 'Thông báo', 'ictu' ),
            'vb11' => __( 'Tờ trình', 'ictu' ),
            'vb12' => __( 'Hướng dẫn', 'ictu' ),
            'vb13' => __( 'Chương trình', 'ictu' ),
            'vb14' => __( 'Kế hoạch', 'ictu' ),
            'vb15' => __( 'Phương án', 'ictu' ),
            'vb16' => __( 'Đề án', 'ictu' ),
            'vb17' => __( 'Dự án', 'ictu' ),
            'vb18' => __( 'Báo cáo', 'ictu' ),
            'vb19' => __( 'Biên bản', 'ictu' ),
            'vb20' => __( 'Hợp đồng', 'ictu' ),
            'vb21' => __( 'Công điện', 'ictu' ),
            'vb22' => __( 'Bản ghi nhớ', 'ictu' ),
            'vb23' => __( 'Bản thỏa thuận', 'ictu' ),
            'vb24' => __( 'Giấy ủy quyền', 'ictu' ),
            'vb25' => __( 'Giấy mời', 'ictu' ),
            'vb26' => __( 'Giấy giới thiệu', 'ictu' ),
            'vb27' => __( 'Giấy nghỉ phép', 'ictu' ),
            'vb28' => __( 'Phiếu gửi', 'ictu' ),
            'vb29' => __( 'Phiếu chuyển', 'ictu' ),
            'vb30' => __( 'Phiếu báo', 'ictu' )
        );
    }
}

if ( !function_exists( 'ictu_get_document_type' ) ) {
    function ictu_get_document_type( $code ) : string
    {
        $document_type = ictu_document_type();
        return ( is_string( $code ) && array_key_exists( $code, $document_type ) ) ? $document_type[ $code ] : '';
    }
}

if ( !function_exists( 'redirect_to_most_recently_post_of_category' ) ) {
    function redirect_to_most_recently_post_of_category()
    {
        $url = null;
        if ( is_archive() && isset( $_GET['filter'] ) && $_GET['filter'] === 'most_recently' ) {
            $category = get_term_by( 'slug', get_query_var( 'category_name' ), 'category' );
            $posts    = get_posts( array( 'numberposts' => 1, 'category' => $category->term_id ) );
            $url      = ( count( $posts ) ) ? get_permalink( $posts[0]->ID ) : null;
        }
        if ( $url ) {
            wp_redirect( $url, 301 );
            exit;
        }
    }

    add_action( 'template_redirect', 'redirect_to_most_recently_post_of_category' );
}

add_filter( 'wp_nav_menu_objects', function ( $sorted_menu_items, $args ) {
    foreach ( $sorted_menu_items as $menu_item ) {
        if ( $menu_item->url === '#lichtuan' ) {
            $latest_post = get_posts( [
                'category'    => 51,
                'numberposts' => 1,
            ] );

            if ( $latest_post ) {
                $post             = $latest_post[0];
                $menu_item->url   = get_permalink( $post->ID );
//                $menu_item->title = $post->post_title;
            }
        }
    }
    return $sorted_menu_items;
}, 10, 2 );