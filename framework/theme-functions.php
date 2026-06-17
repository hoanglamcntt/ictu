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
        get_template_part( 'templates/headers/header', 'style-01' );
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
        }
        $html = '<a href="' . esc_url( $logo_link ) . '">';
        $html .= '<figure><img alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '" class="_rw" /></figure>';
        if ( $organize_name && $tpl_organize_name ) {
            $html .= '<span class="logo-text">' . wp_specialchars_decode( $tpl_organize_name ) . '</span>';
        }
        $html .= '</a>';
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
        $logout       = $login;
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
            'title' => 'Main Icons',
            'icons' => array(
                "main-icon-menu",
                "main-icon-list",
                "main-icon-align-justify",
                "main-icon-align-center",
                "main-icon-align-left",
                "main-icon-align-right",
                "main-icon-chevron-down",
                "main-icon-chevron-up",
                "main-icon-chevron-left",
                "main-icon-chevron-right",
                "main-icon-code",
                "main-icon-chevrons-down",
                "main-icon-chevrons-up",
                "main-icon-chevrons-left",
                "main-icon-chevrons-right",
                "main-icon-arrow-down",
                "main-icon-arrow-up",
                "main-icon-arrow-left",
                "main-icon-arrow-right",
                "main-icon-arrow-down-left",
                "main-icon-arrow-down-right",
                "main-icon-arrow-up-left",
                "main-icon-arrow-up-right",
                "main-icon-arrow-down-circle",
                "main-icon-arrow-up-circle",
                "main-icon-arrow-left-circle",
                "main-icon-arrow-right-circle",
                "main-icon-corner-down-left",
                "main-icon-corner-down-right",
                "main-icon-corner-up-left",
                "main-icon-corner-up-right",
                "main-icon-corner-left-down",
                "main-icon-corner-right-down",
                "main-icon-corner-left-up",
                "main-icon-corner-right-up",
                "main-icon-download",
                "main-icon-upload",
                "main-icon-log-out",
                "main-icon-log-in",
                "main-icon-share",
                "main-icon-download-cloud",
                "main-icon-upload-cloud",
                "main-icon-refresh-ccw",
                "main-icon-refresh-cw",
                "main-icon-rotate-ccw",
                "main-icon-rotate-cw",
                "main-icon-repeat",
                "main-icon-move",
                "main-icon-minimize",
                "main-icon-maximize",
                "main-icon-minimize-2",
                "main-icon-maximize-2",
                "main-icon-circle",
                "main-icon-square",
                "main-icon-hexagon",
                "main-icon-octagon",
                "main-icon-triangle",
                "main-icon-star",
                "main-icon-plus",
                "main-icon-minus",
                "main-icon-x",
                "main-icon-percent",
                "main-icon-plus-circle",
                "main-icon-minus-circle",
                "main-icon-x-circle",
                "main-icon-plus-square",
                "main-icon-minus-square",
                "main-icon-x-square",
                "main-icon-x-octagon",
                "main-icon-toggle-left",
                "main-icon-toggle-right",
                "main-icon-help-circle",
                "main-icon-alert-circle",
                "main-icon-info",
                "main-icon-alert-octagon",
                "main-icon-alert-triangle",
                "main-icon-slash",
                "main-icon-check",
                "main-icon-check-circle",
                "main-icon-check-square",
                "main-icon-search",
                "main-icon-zoom-in",
                "main-icon-zoom-out",
                "main-icon-play",
                "main-icon-pause",
                "main-icon-skip-back",
                "main-icon-skip-forward",
                "main-icon-rewind",
                "main-icon-fast-forward",
                "main-icon-play-circle",
                "main-icon-pause-circle",
                "main-icon-stop-circle",
                "main-icon-disc",
                "main-icon-volume",
                "main-icon-volume-1",
                "main-icon-volume-2",
                "main-icon-volume-x",
                "main-icon-activity",
                "main-icon-trending-down",
                "main-icon-trending-up",
                "main-icon-shuffle",
                "main-icon-mouse-pointer",
                "main-icon-navigation",
                "main-icon-navigation-2",
                "main-icon-send",
                "main-icon-pen-tool",
                "main-icon-bar-chart",
                "main-icon-bar-chart-2",
                "main-icon-more-horizontal",
                "main-icon-more-vertical",
                "main-icon-sliders",
                "main-icon-git-commit",
                "main-icon-git-merge",
                "main-icon-git-branch",
                "main-icon-git-pull-request",
                "main-icon-battery",
                "main-icon-battery-charging",
                "main-icon-bluetooth",
                "main-icon-wifi",
                "main-icon-wifi-off",
                "main-icon-radio",
                "main-icon-loader",
                "main-icon-crosshair",
                "main-icon-shield",
                "main-icon-shield-off",
                "main-icon-bell",
                "main-icon-bell-off",
                "main-icon-eye",
                "main-icon-eye-off",
                "main-icon-camera",
                "main-icon-camera-off",
                "main-icon-video",
                "main-icon-video-off",
                "main-icon-mic",
                "main-icon-mic-off",
                "main-icon-zap",
                "main-icon-zap-off",
                "main-icon-trash",
                "main-icon-trash-2",
                "main-icon-sun",
                "main-icon-sunrise",
                "main-icon-sunset",
                "main-icon-moon",
                "main-icon-cloud",
                "main-icon-cloud-drizzle",
                "main-icon-cloud-lightning",
                "main-icon-cloud-off",
                "main-icon-lock",
                "main-icon-unlock",
                "main-icon-key",
                "main-icon-cloud-rain",
                "main-icon-cloud-snow",
                "main-icon-droplet",
                "main-icon-frown",
                "main-icon-meh",
                "main-icon-smile",
                "main-icon-folder",
                "main-icon-folder-plus",
                "main-icon-folder-minus",
                "main-icon-file-text",
                "main-icon-file",
                "main-icon-file-plus",
                "main-icon-file-minus",
                "main-icon-save",
                "main-icon-image",
                "main-icon-music",
                "main-icon-hard-drive",
                "main-icon-inbox",
                "main-icon-archive",
                "main-icon-printer",
                "main-icon-link",
                "main-icon-link-2",
                "main-icon-paperclip",
                "main-icon-map-pin",
                "main-icon-map",
                "main-icon-compass",
                "main-icon-phone",
                "main-icon-phone-call",
                "main-icon-phone-forwarded",
                "main-icon-phone-missed",
                "main-icon-phone-incoming",
                "main-icon-phone-outgoing",
                "main-icon-phone-off",
                "main-icon-account",
                "main-icon-user",
                "main-icon-user-check",
                "main-icon-user-plus",
                "main-icon-user-minus",
                "main-icon-user-x",
                "main-icon-users",
                "main-icon-thumbs-down",
                "main-icon-thumbs-up",
                "main-icon-edit",
                "main-icon-edit-2",
                "main-icon-edit-3",
                "main-icon-power",
                "main-icon-settings",
                "main-icon-tool",
                "main-icon-share-2",
                "main-icon-scissors",
                "main-icon-gift",
                "main-icon-clock",
                "main-icon-watch",
                "main-icon-thermometer",
                "main-icon-box",
                "main-icon-package",
                "main-icon-grid",
                "main-icon-cpu",
                "main-icon-coffee",
                "main-icon-columns",
                "main-icon-sidebar",
                "main-icon-layout",
                "main-icon-film",
                "main-icon-calendar",
                "main-icon-trello",
                "main-icon-clipboard",
                "main-icon-tag",
                "main-icon-bookmark",
                "main-icon-award",
                "main-icon-flag",
                "main-icon-copy",
                "main-icon-credit-card",
                "main-icon-database",
                "main-icon-layers",
                "main-icon-delete",
                "main-icon-external-link",
                "main-icon-framer",
                "main-icon-filter",
                "main-icon-crop",
                "main-icon-headphones",
                "main-icon-home",
                "main-icon-heart",
                "main-icon-mail",
                "main-icon-message-circle",
                "main-icon-message-square",
                "main-icon-pie-chart",
                "main-icon-pocket",
                "main-icon-shopping-bag",
                "main-icon-shopping-cart",
                "main-icon-truck",
                "main-icon-tv",
                "main-icon-monitor",
                "main-icon-airplay",
                "main-icon-tablet",
                "main-icon-smartphone",
                "main-icon-server",
                "main-icon-speaker",
                "main-icon-umbrella",
                "main-icon-type",
                "main-icon-bold",
                "main-icon-underline",
                "main-icon-italic",
                "main-icon-at-sign",
                "main-icon-dollar-sign",
                "main-icon-hash",
                "main-icon-terminal",
                "main-icon-book",
                "main-icon-book-open",
                "main-icon-cast",
                "main-icon-briefcase",
                "main-icon-anchor",
                "main-icon-wind",
                "main-icon-target",
                "main-icon-life-buoy",
                "main-icon-aperture",
                "main-icon-chrome",
                "main-icon-globe",
                "main-icon-command",
                "main-icon-figma",
                "main-icon-codepen",
                "main-icon-codesandbox",
                "main-icon-slack",
                "main-icon-facebook",
                "main-icon-feather",
                "main-icon-linkedin",
                "main-icon-youtube",
                "main-icon-instagram",
                "main-icon-twitter",
                "main-icon-twitch",
                "main-icon-github",
                "main-icon-gitlab",
                "main-icon-rss",
                "main-icon-voicemail",
                "main-icon-close",
                "main-icon-close-2",
                "main-icon-back",
                "main-icon-next",
                "main-icon-up",
                "main-icon-down",
                "main-icon-back-2",
                "main-icon-next-2",
                "main-icon-up-2",
                "main-icon-down-2",
                "main-icon-long-back",
                "main-icon-long-next",
                "main-icon-long-up",
                "main-icon-long-down",
                "main-icon-long-back-2",
                "main-icon-long-next-2",
                "main-icon-long-up-2",
                "main-icon-long-down-2",
                "main-icon-back-double",
                "main-icon-next-double",
                "main-icon-up-double",
                "main-icon-down-double",
                "main-icon-plus1",
                "main-icon-minus1",
                "main-icon-menu1",
                "main-icon-expand",
                "main-icon-check1",
                "main-icon-star1",
                "main-icon-star_alt",
                "main-icon-spinner",
                "main-icon-clock1",
                "main-icon-documentation",
                "main-icon-support",
                "main-icon-copy1",
                "main-icon-boat",
                "main-icon-contact",
                "main-icon-return",
                "main-icon-delivery",
                "main-icon-payment",
                "main-icon-discount",
                "main-icon-help",
                "main-icon-curated",
                "main-icon-message",
                "main-icon-size-chart",
                "main-icon-twitter-2",
                "main-icon-facebook-2",
                "main-icon-instagram-2",
                "main-icon-youtube-2",
                "main-icon-tiktok-2",
                "main-icon-pinterest-2",
                "main-icon-snapchat-2",
                "main-icon-percent-2",
                "main-icon-box-shipping",
                "main-icon-box-checked",
                "main-icon-box-support",
                "main-icon-box-materials",
                "main-icon-box-quality",
                "main-icon-shipping",
                "main-icon-cube",
                "main-icon-play-2",
                "main-icon-cube-2",
                "main-icon-btn-list",
                "main-icon-btn-grid-2",
                "main-icon-btn-grid-3",
                "main-icon-btn-grid-4",
                "main-icon-btn-grid-5",
                "main-icon-btn-grid-6",
                "main-icon-btn-grid-7",
                "main-icon-btn-grid-8",
                "main-icon-volume-high",
                "main-icon-volume-medium",
                "main-icon-volume-low",
                "main-icon-volume-none",
                "main-icon-mute",
                "main-icon-info-fill",
                "main-icon-info-circle",
                "main-icon-simulation",
                "main-icon-play-circle1",
            ),
        );
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
            array( "main-icon-menu" => "menu" ),
            array( "main-icon-list" => "list" ),
            array( "main-icon-align-justify" => "align justify" ),
            array( "main-icon-align-center" => "align center" ),
            array( "main-icon-align-left" => "align left" ),
            array( "main-icon-align-right" => "align right" ),
            array( "main-icon-chevron-down" => "chevron down" ),
            array( "main-icon-chevron-up" => "chevron up" ),
            array( "main-icon-chevron-left" => "chevron left" ),
            array( "main-icon-chevron-right" => "chevron right" ),
            array( "main-icon-code" => "code" ),
            array( "main-icon-chevrons-down" => "chevrons down" ),
            array( "main-icon-chevrons-up" => "chevrons up" ),
            array( "main-icon-chevrons-left" => "chevrons left" ),
            array( "main-icon-chevrons-right" => "chevrons right" ),
            array( "main-icon-arrow-down" => "arrow down" ),
            array( "main-icon-arrow-up" => "arrow up" ),
            array( "main-icon-arrow-left" => "arrow left" ),
            array( "main-icon-arrow-right" => "arrow right" ),
            array( "main-icon-arrow-down-left" => "arrow down left" ),
            array( "main-icon-arrow-down-right" => "arrow down right" ),
            array( "main-icon-arrow-up-left" => "arrow up left" ),
            array( "main-icon-arrow-up-right" => "arrow up right" ),
            array( "main-icon-arrow-down-circle" => "arrow down circle" ),
            array( "main-icon-arrow-up-circle" => "arrow up circle" ),
            array( "main-icon-arrow-left-circle" => "arrow left circle" ),
            array( "main-icon-arrow-right-circle" => "arrow right circle" ),
            array( "main-icon-corner-down-left" => "corner down left" ),
            array( "main-icon-corner-down-right" => "corner down right" ),
            array( "main-icon-corner-up-left" => "corner up left" ),
            array( "main-icon-corner-up-right" => "corner up right" ),
            array( "main-icon-corner-left-down" => "corner left down" ),
            array( "main-icon-corner-right-down" => "corner right down" ),
            array( "main-icon-corner-left-up" => "corner left up" ),
            array( "main-icon-corner-right-up" => "corner right up" ),
            array( "main-icon-download" => "download" ),
            array( "main-icon-upload" => "upload" ),
            array( "main-icon-log-out" => "log out" ),
            array( "main-icon-log-in" => "log in" ),
            array( "main-icon-share" => "share" ),
            array( "main-icon-download-cloud" => "download cloud" ),
            array( "main-icon-upload-cloud" => "upload cloud" ),
            array( "main-icon-refresh-ccw" => "refresh ccw" ),
            array( "main-icon-refresh-cw" => "refresh cw" ),
            array( "main-icon-rotate-ccw" => "rotate ccw" ),
            array( "main-icon-rotate-cw" => "rotate cw" ),
            array( "main-icon-repeat" => "repeat" ),
            array( "main-icon-move" => "move" ),
            array( "main-icon-minimize" => "minimize" ),
            array( "main-icon-maximize" => "maximize" ),
            array( "main-icon-minimize-2" => "minimize 2" ),
            array( "main-icon-maximize-2" => "maximize 2" ),
            array( "main-icon-circle" => "circle" ),
            array( "main-icon-square" => "square" ),
            array( "main-icon-hexagon" => "hexagon" ),
            array( "main-icon-octagon" => "octagon" ),
            array( "main-icon-triangle" => "triangle" ),
            array( "main-icon-star" => "star" ),
            array( "main-icon-plus" => "plus" ),
            array( "main-icon-minus" => "minus" ),
            array( "main-icon-x" => "x" ),
            array( "main-icon-percent" => "percent" ),
            array( "main-icon-plus-circle" => "plus circle" ),
            array( "main-icon-minus-circle" => "minus circle" ),
            array( "main-icon-x-circle" => "x circle" ),
            array( "main-icon-plus-square" => "plus square" ),
            array( "main-icon-minus-square" => "minus square" ),
            array( "main-icon-x-square" => "x square" ),
            array( "main-icon-x-octagon" => "x octagon" ),
            array( "main-icon-toggle-left" => "toggle left" ),
            array( "main-icon-toggle-right" => "toggle right" ),
            array( "main-icon-help-circle" => "help circle" ),
            array( "main-icon-alert-circle" => "alert circle" ),
            array( "main-icon-info" => "info" ),
            array( "main-icon-alert-octagon" => "alert octagon" ),
            array( "main-icon-alert-triangle" => "alert triangle" ),
            array( "main-icon-slash" => "slash" ),
            array( "main-icon-check" => "check" ),
            array( "main-icon-check-circle" => "check circle" ),
            array( "main-icon-check-square" => "check square" ),
            array( "main-icon-search" => "search" ),
            array( "main-icon-zoom-in" => "zoom in" ),
            array( "main-icon-zoom-out" => "zoom out" ),
            array( "main-icon-play" => "play" ),
            array( "main-icon-pause" => "pause" ),
            array( "main-icon-skip-back" => "skip back" ),
            array( "main-icon-skip-forward" => "skip forward" ),
            array( "main-icon-rewind" => "rewind" ),
            array( "main-icon-fast-forward" => "fast forward" ),
            array( "main-icon-play-circle" => "play circle" ),
            array( "main-icon-pause-circle" => "pause circle" ),
            array( "main-icon-stop-circle" => "stop circle" ),
            array( "main-icon-disc" => "disc" ),
            array( "main-icon-volume" => "volume" ),
            array( "main-icon-volume-1" => "volume 1" ),
            array( "main-icon-volume-2" => "volume 2" ),
            array( "main-icon-volume-x" => "volume x" ),
            array( "main-icon-activity" => "activity" ),
            array( "main-icon-trending-down" => "trending down" ),
            array( "main-icon-trending-up" => "trending up" ),
            array( "main-icon-shuffle" => "shuffle" ),
            array( "main-icon-mouse-pointer" => "mouse pointer" ),
            array( "main-icon-navigation" => "navigation" ),
            array( "main-icon-navigation-2" => "navigation 2" ),
            array( "main-icon-send" => "send" ),
            array( "main-icon-pen-tool" => "pen tool" ),
            array( "main-icon-bar-chart" => "bar chart" ),
            array( "main-icon-bar-chart-2" => "bar chart 2" ),
            array( "main-icon-more-horizontal" => "more horizontal" ),
            array( "main-icon-more-vertical" => "more vertical" ),
            array( "main-icon-sliders" => "sliders" ),
            array( "main-icon-git-commit" => "git commit" ),
            array( "main-icon-git-merge" => "git merge" ),
            array( "main-icon-git-branch" => "git branch" ),
            array( "main-icon-git-pull-request" => "git pull request" ),
            array( "main-icon-battery" => "battery" ),
            array( "main-icon-battery-charging" => "battery charging" ),
            array( "main-icon-bluetooth" => "bluetooth" ),
            array( "main-icon-wifi" => "wifi" ),
            array( "main-icon-wifi-off" => "wifi off" ),
            array( "main-icon-radio" => "radio" ),
            array( "main-icon-loader" => "loader" ),
            array( "main-icon-crosshair" => "crosshair" ),
            array( "main-icon-shield" => "shield" ),
            array( "main-icon-shield-off" => "shield off" ),
            array( "main-icon-bell" => "bell" ),
            array( "main-icon-bell-off" => "bell off" ),
            array( "main-icon-eye" => "eye" ),
            array( "main-icon-eye-off" => "eye off" ),
            array( "main-icon-camera" => "camera" ),
            array( "main-icon-camera-off" => "camera off" ),
            array( "main-icon-video" => "video" ),
            array( "main-icon-video-off" => "video off" ),
            array( "main-icon-mic" => "mic" ),
            array( "main-icon-mic-off" => "mic off" ),
            array( "main-icon-zap" => "zap" ),
            array( "main-icon-zap-off" => "zap off" ),
            array( "main-icon-trash" => "trash" ),
            array( "main-icon-trash-2" => "trash 2" ),
            array( "main-icon-sun" => "sun" ),
            array( "main-icon-sunrise" => "sunrise" ),
            array( "main-icon-sunset" => "sunset" ),
            array( "main-icon-moon" => "moon" ),
            array( "main-icon-cloud" => "cloud" ),
            array( "main-icon-cloud-drizzle" => "cloud drizzle" ),
            array( "main-icon-cloud-lightning" => "cloud lightning" ),
            array( "main-icon-cloud-off" => "cloud off" ),
            array( "main-icon-lock" => "lock" ),
            array( "main-icon-unlock" => "unlock" ),
            array( "main-icon-key" => "key" ),
            array( "main-icon-cloud-rain" => "cloud rain" ),
            array( "main-icon-cloud-snow" => "cloud snow" ),
            array( "main-icon-droplet" => "droplet" ),
            array( "main-icon-frown" => "frown" ),
            array( "main-icon-meh" => "meh" ),
            array( "main-icon-smile" => "smile" ),
            array( "main-icon-folder" => "folder" ),
            array( "main-icon-folder-plus" => "folder plus" ),
            array( "main-icon-folder-minus" => "folder minus" ),
            array( "main-icon-file-text" => "file text" ),
            array( "main-icon-file" => "file" ),
            array( "main-icon-file-plus" => "file plus" ),
            array( "main-icon-file-minus" => "file minus" ),
            array( "main-icon-save" => "save" ),
            array( "main-icon-image" => "image" ),
            array( "main-icon-music" => "music" ),
            array( "main-icon-hard-drive" => "hard drive" ),
            array( "main-icon-inbox" => "inbox" ),
            array( "main-icon-archive" => "archive" ),
            array( "main-icon-printer" => "printer" ),
            array( "main-icon-link" => "link" ),
            array( "main-icon-link-2" => "link 2" ),
            array( "main-icon-paperclip" => "paperclip" ),
            array( "main-icon-map-pin" => "map pin" ),
            array( "main-icon-map" => "map" ),
            array( "main-icon-compass" => "compass" ),
            array( "main-icon-phone" => "phone" ),
            array( "main-icon-phone-call" => "phone call" ),
            array( "main-icon-phone-forwarded" => "phone forwarded" ),
            array( "main-icon-phone-missed" => "phone missed" ),
            array( "main-icon-phone-incoming" => "phone incoming" ),
            array( "main-icon-phone-outgoing" => "phone outgoing" ),
            array( "main-icon-phone-off" => "phone off" ),
            array( "main-icon-account" => "account" ),
            array( "main-icon-user" => "user" ),
            array( "main-icon-user-check" => "user check" ),
            array( "main-icon-user-plus" => "user plus" ),
            array( "main-icon-user-minus" => "user minus" ),
            array( "main-icon-user-x" => "user x" ),
            array( "main-icon-users" => "users" ),
            array( "main-icon-thumbs-down" => "thumbs down" ),
            array( "main-icon-thumbs-up" => "thumbs up" ),
            array( "main-icon-edit" => "edit" ),
            array( "main-icon-edit-2" => "edit 2" ),
            array( "main-icon-edit-3" => "edit 3" ),
            array( "main-icon-power" => "power" ),
            array( "main-icon-settings" => "settings" ),
            array( "main-icon-tool" => "tool" ),
            array( "main-icon-share-2" => "share 2" ),
            array( "main-icon-scissors" => "scissors" ),
            array( "main-icon-gift" => "gift" ),
            array( "main-icon-clock" => "clock" ),
            array( "main-icon-watch" => "watch" ),
            array( "main-icon-thermometer" => "thermometer" ),
            array( "main-icon-box" => "box" ),
            array( "main-icon-package" => "package" ),
            array( "main-icon-grid" => "grid" ),
            array( "main-icon-cpu" => "cpu" ),
            array( "main-icon-coffee" => "coffee" ),
            array( "main-icon-columns" => "columns" ),
            array( "main-icon-sidebar" => "sidebar" ),
            array( "main-icon-layout" => "layout" ),
            array( "main-icon-film" => "film" ),
            array( "main-icon-calendar" => "calendar" ),
            array( "main-icon-trello" => "trello" ),
            array( "main-icon-clipboard" => "clipboard" ),
            array( "main-icon-tag" => "tag" ),
            array( "main-icon-bookmark" => "bookmark" ),
            array( "main-icon-award" => "award" ),
            array( "main-icon-flag" => "flag" ),
            array( "main-icon-copy" => "copy" ),
            array( "main-icon-credit-card" => "credit card" ),
            array( "main-icon-database" => "database" ),
            array( "main-icon-layers" => "layers" ),
            array( "main-icon-delete" => "delete" ),
            array( "main-icon-external-link" => "external link" ),
            array( "main-icon-framer" => "framer" ),
            array( "main-icon-filter" => "filter" ),
            array( "main-icon-crop" => "crop" ),
            array( "main-icon-headphones" => "headphones" ),
            array( "main-icon-home" => "home" ),
            array( "main-icon-heart" => "heart" ),
            array( "main-icon-mail" => "mail" ),
            array( "main-icon-message-circle" => "message circle" ),
            array( "main-icon-message-square" => "message square" ),
            array( "main-icon-pie-chart" => "pie chart" ),
            array( "main-icon-pocket" => "pocket" ),
            array( "main-icon-shopping-bag" => "shopping bag" ),
            array( "main-icon-shopping-cart" => "shopping cart" ),
            array( "main-icon-truck" => "truck" ),
            array( "main-icon-tv" => "tv" ),
            array( "main-icon-monitor" => "monitor" ),
            array( "main-icon-airplay" => "airplay" ),
            array( "main-icon-tablet" => "tablet" ),
            array( "main-icon-smartphone" => "smartphone" ),
            array( "main-icon-server" => "server" ),
            array( "main-icon-speaker" => "speaker" ),
            array( "main-icon-umbrella" => "umbrella" ),
            array( "main-icon-type" => "type" ),
            array( "main-icon-bold" => "bold" ),
            array( "main-icon-underline" => "underline" ),
            array( "main-icon-italic" => "italic" ),
            array( "main-icon-at-sign" => "at sign" ),
            array( "main-icon-dollar-sign" => "dollar sign" ),
            array( "main-icon-hash" => "hash" ),
            array( "main-icon-terminal" => "terminal" ),
            array( "main-icon-book" => "book" ),
            array( "main-icon-book-open" => "book open" ),
            array( "main-icon-cast" => "cast" ),
            array( "main-icon-briefcase" => "briefcase" ),
            array( "main-icon-anchor" => "anchor" ),
            array( "main-icon-wind" => "wind" ),
            array( "main-icon-target" => "target" ),
            array( "main-icon-life-buoy" => "life buoy" ),
            array( "main-icon-aperture" => "aperture" ),
            array( "main-icon-chrome" => "chrome" ),
            array( "main-icon-globe" => "globe" ),
            array( "main-icon-command" => "command" ),
            array( "main-icon-figma" => "figma" ),
            array( "main-icon-codepen" => "codepen" ),
            array( "main-icon-codesandbox" => "codesandbox" ),
            array( "main-icon-slack" => "slack" ),
            array( "main-icon-facebook" => "facebook" ),
            array( "main-icon-feather" => "feather" ),
            array( "main-icon-linkedin" => "linkedin" ),
            array( "main-icon-youtube" => "youtube" ),
            array( "main-icon-instagram" => "instagram" ),
            array( "main-icon-twitter" => "twitter" ),
            array( "main-icon-twitch" => "twitch" ),
            array( "main-icon-github" => "github" ),
            array( "main-icon-gitlab" => "gitlab" ),
            array( "main-icon-rss" => "rss" ),
            array( "main-icon-voicemail" => "voicemail" ),
            array( "main-icon-close" => "close" ),
            array( "main-icon-close-2" => "close 2" ),
            array( "main-icon-back" => "back" ),
            array( "main-icon-next" => "next" ),
            array( "main-icon-up" => "up" ),
            array( "main-icon-down" => "down" ),
            array( "main-icon-back-2" => "back 2" ),
            array( "main-icon-next-2" => "next 2" ),
            array( "main-icon-up-2" => "up 2" ),
            array( "main-icon-down-2" => "down 2" ),
            array( "main-icon-long-back" => "long back" ),
            array( "main-icon-long-next" => "long next" ),
            array( "main-icon-long-up" => "long up" ),
            array( "main-icon-long-down" => "long down" ),
            array( "main-icon-long-back-2" => "long back 2" ),
            array( "main-icon-long-next-2" => "long next 2" ),
            array( "main-icon-long-up-2" => "long up 2" ),
            array( "main-icon-long-down-2" => "long down 2" ),
            array( "main-icon-back-double" => "back double" ),
            array( "main-icon-next-double" => "next double" ),
            array( "main-icon-up-double" => "up double" ),
            array( "main-icon-down-double" => "down double" ),
            array( "main-icon-plus1" => "plus1" ),
            array( "main-icon-minus1" => "minus1" ),
            array( "main-icon-menu1" => "menu1" ),
            array( "main-icon-expand" => "expand" ),
            array( "main-icon-check1" => "check1" ),
            array( "main-icon-star1" => "star1" ),
            array( "main-icon-star_alt" => "star alt" ),
            array( "main-icon-spinner" => "spinner" ),
            array( "main-icon-clock1" => "clock1" ),
            array( "main-icon-documentation" => "documentation" ),
            array( "main-icon-support" => "support" ),
            array( "main-icon-copy1" => "copy1" ),
            array( "main-icon-boat" => "boat" ),
            array( "main-icon-contact" => "contact" ),
            array( "main-icon-return" => "return" ),
            array( "main-icon-delivery" => "delivery" ),
            array( "main-icon-payment" => "payment" ),
            array( "main-icon-discount" => "discount" ),
            array( "main-icon-help" => "help" ),
            array( "main-icon-curated" => "curated" ),
            array( "main-icon-message" => "message" ),
            array( "main-icon-size-chart" => "size chart" ),
            array( "main-icon-twitter-2" => "twitter 2" ),
            array( "main-icon-facebook-2" => "facebook 2" ),
            array( "main-icon-instagram-2" => "instagram 2" ),
            array( "main-icon-youtube-2" => "youtube 2" ),
            array( "main-icon-tiktok-2" => "tiktok 2" ),
            array( "main-icon-pinterest-2" => "pinterest 2" ),
            array( "main-icon-snapchat-2" => "snapchat 2" ),
            array( "main-icon-percent-2" => "percent 2" ),
            array( "main-icon-box-shipping" => "box shipping" ),
            array( "main-icon-box-checked" => "box checked" ),
            array( "main-icon-box-support" => "box support" ),
            array( "main-icon-box-materials" => "box materials" ),
            array( "main-icon-box-quality" => "box quality" ),
            array( "main-icon-shipping" => "shipping" ),
            array( "main-icon-cube" => "cube" ),
            array( "main-icon-play-2" => "play 2" ),
            array( "main-icon-cube-2" => "cube 2" ),
            array( "main-icon-btn-list" => "btn list" ),
            array( "main-icon-btn-grid-2" => "btn grid 2" ),
            array( "main-icon-btn-grid-3" => "btn grid 3" ),
            array( "main-icon-btn-grid-4" => "btn grid 4" ),
            array( "main-icon-btn-grid-5" => "btn grid 5" ),
            array( "main-icon-btn-grid-6" => "btn grid 6" ),
            array( "main-icon-btn-grid-7" => "btn grid 7" ),
            array( "main-icon-btn-grid-8" => "btn grid 8" ),
            array( "main-icon-volume-high" => "volume high" ),
            array( "main-icon-volume-medium" => "volume medium" ),
            array( "main-icon-volume-low" => "volume low" ),
            array( "main-icon-volume-none" => "volume none" ),
            array( "main-icon-mute" => "mute" ),
            array( "main-icon-info-fill" => "info fill" ),
            array( "main-icon-info-circle" => "info circle" ),
            array( "main-icon-simulation" => "simulation" ),
            array( "main-icon-play-circle1" => "play circle1" ),
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
        get_template_part( 'templates/footer/footer', 'style-01' );
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
                $post           = $latest_post[0];
                $menu_item->url = get_permalink( $post->ID );
//                $menu_item->title = $post->post_title;
            }
        }
    }
    return $sorted_menu_items;
}, 10, 2 );