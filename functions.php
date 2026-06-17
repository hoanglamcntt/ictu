<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
if ( !defined( 'WCMP_UNLOAD_BOOTSTRAP_LIB' ) ) {
    define( 'WCMP_UNLOAD_BOOTSTRAP_LIB', true );
}
// Theme version.
if ( !defined( 'THEME_VERSION' ) ) {
    define( 'THEME_VERSION', wp_get_theme()->get( 'Version' ) );
}

if ( !function_exists( 'theme_init_setup' ) ) {
    function theme_init_setup()
    {
        // Set the default content width.
        $GLOBALS['content_width'] = 1400;
        /*
         * Make theme available for translation.
         * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/blank
         * If you're building a theme based on Twenty Seventeen, use a find and replace
         * to change 'ictu' to the name of your theme in all the template files.
         */
        load_theme_textdomain( 'ictu', get_template_directory() . '/languages' );
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );
        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-background' );
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'widgets',
        ) );
        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(
            array(
                'primary' => __( 'Primary Menu', 'ictu' ),
                'footer1' => __( 'Footer Menu 1', 'ictu' ),
                'footer2' => __( 'Footer Menu 2', 'ictu' ),
                'footer3' => __( 'Footer Menu 3', 'ictu' ),
                'footer4' => __( 'Footer Menu 4', 'ictu' ),
            )
        );
        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

//        if (get_theme_option('disable_zoom') != 1) {
//            add_theme_support('wc-product-gallery-zoom');
//        }
//        if (get_theme_option('disable_lightbox') != 1) {
//            add_theme_support('wc-product-gallery-lightbox');
//        }
//        add_theme_support('wc-product-gallery-slider');
        /**
         *
         * SUPPORT BLOCKS
         **/
        // Add support for Block Styles.
        add_theme_support( 'wp-block-styles' );
        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );
        // Add support for editor styles.
        add_theme_support( 'editor-styles' );
        add_theme_support( 'dark-editor-style' );
        // Add support for responsive embedded content.
        add_theme_support( 'responsive-embeds' );
    }

    add_action( 'after_setup_theme', 'theme_init_setup' );
}
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
if ( !function_exists( 'theme_widgets_init' ) ) {
    function theme_widgets_init()
    {
        $sidebars      = array(
            'widget-area'      => array(
                'name'          => esc_html__( 'Widget Area', 'ictu' ),
                'id'            => 'widget-area',
                'description'   => esc_html__( 'Add widgets here to appear in your blog sidebar.', 'ictu' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '<span class="arrow"></span></h2>',
            ),
            'post-widget-area' => array(
                'name'          => esc_html__( 'Post Widget Area', 'ictu' ),
                'id'            => 'post-widget-area',
                'description'   => esc_html__( 'Add widgets here to appear in your post sidebar.', 'ictu' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '<span class="arrow"></span></h2>',
            ),
            //            'shop-widget-area'    => array(
            //                'name'          => esc_html__('Shop Widget Area', 'ictu'),
            //                'id'            => 'shop-widget-area',
            //                'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'ictu'),
            //                'before_widget' => '<div id="%1$s" class="widget %2$s">',
            //                'after_widget'  => '</div>',
            //                'before_title'  => '<h2 class="widget-title">',
            //                'after_title'   => '<span class="arrow"></span></h2>',
            //            ),
            //            'product-widget-area' => array(
            //                'name'          => esc_html__('Product Widget Area', 'ictu'),
            //                'id'            => 'product-widget-area',
            //                'description'   => esc_html__('Add widgets here to appear in your Product sidebar.', 'ictu'),
            //                'before_widget' => '<div id="%1$s" class="widget %2$s">',
            //                'after_widget'  => '</div>',
            //                'before_title'  => '<h2 class="widget-title">',
            //                'after_title'   => '<span class="arrow"></span></h2>',
            //            ),
        );
        $multi_sidebar = get_theme_option( 'multi_sidebar' );
        if ( is_array( $multi_sidebar ) && !empty( $multi_sidebar ) ) {
            foreach ( $multi_sidebar as $sidebar ) {
                if ( !empty( $sidebar ) ) {
                    $sidebar_id              = 'custom-sidebar-' . sanitize_key( $sidebar['add_sidebar'] );
                    $sidebars[ $sidebar_id ] = array(
                        'name'          => $sidebar['add_sidebar'],
                        'id'            => $sidebar_id,
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget'  => '</div>',
                        'before_title'  => '<h2 class="widget-title">',
                        'after_title'   => '<span class="arrow"></span></h2>',
                    );
                }
            }
        }
        foreach ( $sidebars as $sidebar ) {
            register_sidebar( $sidebar );
        }
    }

    add_action( 'widgets_init', 'theme_widgets_init' );
}
/**
 * Custom Comment field.
 */
if ( !function_exists( 'theme_comment_field_to_bottom' ) ) {
    function theme_comment_field_to_bottom( $fields )
    {
        $comment_field = $fields['comment'];
        unset( $fields['comment'] );
        $fields['comment'] = $comment_field;

        return $fields;
    }

    //add_filter( 'comment_form_fields', 'theme_comment_field_to_bottom' );
}
/**
 * Custom Body Class.
 */
if ( !function_exists( 'theme_body_class' ) ) {
    function theme_body_class( $classes )
    {
        $my_theme = wp_get_theme();
        if ( theme_is_mobile() ) {
            $classes[] = "enable-mobile-mode";
        }
        $classes[] = "ictu-v" . $my_theme->get( 'Version' );

        if ( is_single() ) {
            $post_options = get_post_meta( get_the_ID(), '_custom_metabox_post_options', true );
            $classes[]    = !empty($post_options['type']) ? 'single-' . $post_options['type'] : '';
        }

        return $classes;
    }

    add_filter( 'body_class', 'theme_body_class' );
}
add_filter( 'admin_body_class', function ( $classes ) {
    if ( current_user_can( 'manage_options' ) ) {
        $classes .= ' is-administrator';
    } else {
        $classes .= ' not-administrator';
    }

    return trim( $classes );
} );

/**
 * Thay đổi Logo trang Login WordPress
 */
add_action( 'login_enqueue_scripts', 'ictu_custom_login_logo' );
function ictu_custom_login_logo()
{
    $logo_url = get_stylesheet_directory_uri() . '/assets/images/logo-256.png';
    ?>
    <style>
        #login{
            width: 352px !important;
        }
        #login h1 a, .login h1 a{
            background-image: url(<?php echo esc_url($logo_url); ?>);
            height: 128px;
            width: 320px;
            background-size: contain;
            background-repeat: no-repeat;
        }
    </style>
    <?php
}

/**
 * Thay đổi link khi click vào Logo (mặc định trỏ về WordPress.org)
 */
add_filter( 'login_headerurl', 'ictu_login_logo_url' );
function ictu_login_logo_url()
{
    return home_url();
}

/**
 * Thay đổi thuộc tính Title của Logo
 */
add_filter( 'login_headertext', 'ictu_login_logo_url_title' );
function ictu_login_logo_url_title()
{
    return get_bloginfo( 'name' );
}

/**
 * Ẩn chức năng với người dùng không phải Administrator
 */
add_action( 'admin_menu', function () {
    if ( !current_user_can( 'manage_options' ) ) {
        remove_menu_page( 'upload.php' );
        remove_menu_page( 'edit-comments.php' );
        remove_menu_page( 'tools.php' );
        remove_menu_page( 'edit.php?post_type=elementor_library' );
        remove_menu_page( 'edit.php?post_type=rank_math_schema' );
    }
}, 999 );
add_action( 'admin_init', function () {
    global $pagenow;

    // Kiểm tra nếu đang truy cập vào danh sách post type
    $post_type = isset( $_GET['post_type'] ) ? esc_attr( $_GET['post_type'] ) : '';

    $restricted_post_types = [
        'elementor_library',
        'rank_math_schema'
    ];

    if ( !current_user_can( 'manage_options' ) ) {
        if ( ( $pagenow == 'upload.php' || $pagenow == 'edit-comments.php' || $pagenow == 'tools.php' ) ) {
            wp_die( 'Xin lỗi, bạn không có được phép truy cập vào trang này.' );
        }

        if ( is_admin() && $pagenow == 'edit.php' && in_array( $post_type, $restricted_post_types ) ) {
            wp_die( 'Xin lỗi, bạn không có được phép truy cập vào trang này.' );
        }
    }
} );

// Ẩn tất cả thông báo admin đối với không phải Admin
add_action( 'admin_print_scripts', function () {
    if ( !current_user_can( 'manage_options' ) ) {
        echo '<style>.notice, .update-nag, .updated, .error { display: none !important; }</style>';
    }
} );

/**
 * Chỉ hiển thị bài viết của chính tác giả trong trang quản trị
 */
add_action( 'pre_get_posts', function ( $query ) {
    // Chỉ thực thi trong trang quản trị, cho truy vấn chính (main query)
    // và đảm bảo không ảnh hưởng đến các yêu cầu AJAX
    if ( is_admin() && $query->is_main_query() ) {

        // Kiểm tra nếu user không phải là admin (hoặc người có quyền quản lý bài viết của người khác)
        if ( !current_user_can( 'manage_options' ) ) {

            // Lấy ID của user hiện tại
            $current_user_id = get_current_user_id();

            // Ép tham số 'author' vào query để chỉ lấy bài của user này
            $query->set( 'author', $current_user_id );
        }
    }
} );

// Lưu ý: Đây là code gợi ý để biến đổi giao diện admin
add_action( 'admin_footer', 'custom_admin_category_search' );
function custom_admin_category_search()
{
    $screen = get_current_screen();
    if ( $screen->base == 'post' ) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                // Sử dụng các thư viện như Select2 để biến đổi input
                // Hoặc đơn giản là thêm một input text để filter nhanh các checkbox
                $('#category-all > ul').before('<input type="text" id="cat-search" placeholder="Tìm danh mục..." style="width:100%; margin:10px 0 0; border-radius:4px; border:1px solid #ddd;">');

                $('#cat-search').on('keyup', function () {
                    var value = $(this).val().toLowerCase();
                    $('#category-all li').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
        <?php
    }
}

// Xóa chữ WordPress ở cuối tiêu đề trang đăng nhập
add_filter( 'login_title', 'custom_login_title', 10, 2 );
function custom_login_title( $login_title, $title )
{
    $blog_name = get_bloginfo( 'name' );
    return 'Đăng nhập - ' . $blog_name;
}

add_filter( 'admin_title', 'custom_admin_title', 10, 2 );
function custom_admin_title( $admin_title, $title )
{
    $blog_name = get_bloginfo( 'name' );
    return $title . ' - ' . $blog_name;
}

if ( !function_exists( 'theme_check_hide_title' ) ) {
    /**
     * Check hide title.
     *
     * @param bool $val default value.
     *
     * @return bool
     */
    function theme_check_hide_title( $val )
    {
        if ( defined( 'ELEMENTOR_VERSION' ) ) {
            $current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
            if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
                $val = false;
            }
        }

        return $val;
    }
}
add_filter( 'theme_page_title', 'theme_check_hide_title' );
/**
 * WordPress shims.
 *
 * @package storefront
 */
if ( !function_exists( 'wp_body_open' ) ) {
    /**
     * Adds backwards compatibility for wp_body_open() introduced with WordPress 5.2
     *
     * @return void
     * @see https://developer.wordpress.org/reference/functions/wp_body_open/
     * @since 2.5.4
     */
    function wp_body_open()
    {
        do_action( 'wp_body_open' );
    }
}
/**
 * Functions theme Colors convert.
 */
require_once get_theme_file_path( '/framework/classes/colors.php' );
/**
 * Functions theme helper.
 */
require get_parent_theme_file_path( '/framework/settings/helpers.php' );
/**
 * Enqueue scripts and styles.
 */
require get_parent_theme_file_path( '/framework/settings/enqueue.php' );
/**
 * Functions add inline style inline.
 */
require get_parent_theme_file_path( '/framework/settings/color-patterns.php' );
/**
 * Functions plugin load.
 */
require get_parent_theme_file_path( '/framework/settings/plugins-load.php' );
/**
 * Functions theme AJAX.
 */
require get_parent_theme_file_path( '/framework/classes/core-ajax.php' );
/**
 * Functions theme breadcrumbs.
 */
require get_parent_theme_file_path( '/framework/classes/breadcrumbs.php' );
/**
 * Functions theme options.
 */
require get_parent_theme_file_path( '/framework/settings/theme-options.php' );
/**
 * Functions metabox options.
 */
require get_parent_theme_file_path( '/framework/settings/metabox-options.php' );
/**
 * Functions theme.
 */
require get_parent_theme_file_path( '/framework/theme-functions.php' );
/**
 * Functions blog.
 */
require get_parent_theme_file_path( '/framework/blog-functions.php' );

/**
 * Theme widgets
 */
require get_parent_theme_file_path( '/framework/widget-init.php' );
//require_once(get_parent_theme_file_path('/widgets/Avocado_Framework_Widget.php'));
/**
 * Others
 */
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'wp_image_editors', function ( $editors ) {
    return [ 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' ];
} );
/**
 * Homepage - Shortcodes
 */
require_once get_parent_theme_file_path( '/shortcodes/heading.php' );
require_once get_parent_theme_file_path( '/shortcodes/slider.php' );
require_once get_parent_theme_file_path( '/shortcodes/impression.php' );
require_once get_parent_theme_file_path( '/shortcodes/banner.php' );
require_once get_parent_theme_file_path( '/shortcodes/blog.php' );
require_once get_parent_theme_file_path( '/shortcodes/vision.php' );
require_once get_parent_theme_file_path( '/shortcodes/program.php' );
require_once get_parent_theme_file_path( '/shortcodes/press.php' );
require_once get_parent_theme_file_path( '/shortcodes/discover.php' );
require_once get_parent_theme_file_path( '/shortcodes/comment.php' );
require_once get_parent_theme_file_path( '/shortcodes/partner.php' );
require_once get_parent_theme_file_path( '/shortcodes/student.php' );
require_once get_parent_theme_file_path( '/shortcodes/document.php' );
//add_action('after_setup_theme', function() {
//    global $shortcode_tags;
//    var_dump('after_setup_theme', isset($shortcode_tags['ictu-slider']));
//});
/**
 * Custom post type
 */
require_once get_parent_theme_file_path( '/framework/custom-post-type/baochi/baochi.php' );
