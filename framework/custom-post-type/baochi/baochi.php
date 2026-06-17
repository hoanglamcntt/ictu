<?php
/**
 * Plugin Name: ICTU: Post type - Baochi
 * Plugin URI:  https://ictu.vn/
 * Description: Custom post type Baochi for ICTU themes.
 * Version:     1.0.0
 * Author:      Liam Hoang
 * Author URI:  https://ictu.vn/
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 **/

if ( !defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.

if ( !class_exists( 'Ictu_Baochi' ) ) {
    class Ictu_Baochi
    {
        public function __construct()
        {
            // register
            add_action( 'init', array( $this, 'register_baochi' ), 1 );
            add_action( 'init', array( $this, 'register_baochi_taxonomy' ), 10 );
            add_action( 'init', array( $this, 'register_baochi_sidebar' ), 10 );
//            add_action('widgets_init', array($this, 'register_baochi_sidebar'));
            // add posts columns
            add_filter( 'manage_baochi_posts_columns', array( $this, 'add_posts_columns' ) );
            add_action( 'manage_baochi_posts_custom_column', array( $this, 'fill_data_in_to_columns' ), 10, 2 );
            // add sort posts columns
            add_filter( 'manage_edit-baochi_sortable_columns', array( $this, 'make_column_sortable' ) );
            add_action( 'pre_get_posts', array( $this, 'orderby_submit_time' ) );
            // meta box options
            add_action( 'init', array( $this, 'theme_metabox_options' ) );
        }

        public function register_baochi()
        {
            $labels = array(
                'name'                  => __( 'Báo chí', 'umeno' ),
                'singular_name'         => __( 'Báo chí', 'umeno' ),
                'menu_name'             => __( 'Báo chí', 'umeno' ),
                'name_admin_bar'        => __( 'Báo chí', 'umeno' ),
                'add_new'               => __( 'Thêm Bài báo', 'umeno' ),
                'add_new_item'          => __( 'Thêm Bài báo mới', 'umeno' ),
                'new_item'              => __( 'Bài báo mới', 'umeno' ),
                'edit_item'             => __( 'Sửa Bài báo', 'umeno' ),
                'view_item'             => __( 'Xem Bài báo', 'umeno' ),
                'all_items'             => __( 'Tất cả Bài báo', 'umeno' ),
                'search_items'          => __( 'Tìm kiếm Bài báo', 'umeno' ),
                'not_found'             => __( 'Không có Bài báo.', 'umeno' ),
                'not_found_in_trash'    => __( 'Không có Bài báo trong thùng rác.', 'umeno' ),
                'featured_image'        => __( 'Ảnh chính', 'umeno' ),
                'set_featured_image'    => __( 'Chọn ảnh chính', 'umeno' ),
                'remove_featured_image' => __( 'Xóa ảnh chính', 'umeno' ),
                'use_featured_image'    => __( 'Sử dụng ảnh chính', 'umeno' ),
                'archives'              => __( 'Danh sách Bài báo', 'umeno' ),
                'insert_into_item'      => __( 'Chèn vào Bài báo', 'umeno' ),
                'uploaded_to_this_item' => __( 'Bài báo đã được tải lên', 'umeno' ),
                'filter_items_list'     => __( 'Lọc danh sách Bài báo', 'umeno' ),
                'items_list_navigation' => __( 'Phân trang', 'umeno' ),
                'items_list'            => __( 'Danh sách Bài báo', 'umeno' ),
            );
            $args   = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'menu_icon'          => 'dashicons-welcome-widgets-menus',
                'rewrite'            => array( 'slug' => 'baochi' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'show_in_rest'       => true,
                'supports'           => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'revisions',
                ),
            );
            register_post_type( 'baochi', $args );
        }

        public function register_baochi_taxonomy()
        {
            $labels = array(
                'name'          => __( 'Tên Báo', 'umeno' ),
                'singular_name' => __( 'Tên Báo', 'umeno' ),
                'menu_name'     => __( 'Tên Báo', 'umeno' ),
                'add_new_item'  => __( 'Thêm Tên Báo mới', 'umeno' ),
                'edit_item'     => __( 'Sửa Tên Báo', 'umeno' ),
                'all_items'     => __( 'Tất cả Tên Báo', 'umeno' ),
                'search_items'  => __( 'Tìm kiếm Tên Báo', 'umeno' ),
            );
            $args   = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'show_admin_column'  => true,
                'query_var'          => true,
                'hierarchical'       => true,
                'show_in_nav_menus'  => true,
                'show_in_rest'       => true,
            );
            register_taxonomy( 'loaibaochi', 'baochi', $args );
        }

        public function register_baochi_sidebar()
        {
            register_sidebar(
                array(
                    'name'          => 'Báo chí - Widget Area',
                    'id'            => 'baochi-sidebar',
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h2 class="widget-title">',
                    'after_title'   => '<span class="arrow"></span></h2>',
                )
            );
        }

        function add_posts_columns( $columns )
        {
            unset( $columns['date'] );
            $columns["baochi_order"] = __( 'Số thứ tự sắp xếp', 'dreamit-elementor-extension' );
            $columns['date']         = __( 'Thời gian', 'dreamit-elementor-extension' );

            return $columns;
        }

        function fill_data_in_to_columns( $column_name, $post_id )
        {
            if ( $column_name === 'baochi_order' ) {
                $baochi_order = get_post_meta( get_the_ID(), 'custom_baochi_order', true );
                echo esc_html( $baochi_order ) ?? '';
            }
        }

        function make_column_sortable( $columns )
        {
            $columns['baochi_order'] = 'baochi_order';
            return $columns;
        }

        function orderby_submit_time( $query )
        {
            if ( !is_admin() || !$query->is_main_query() ) {
                return;
            }

            $orderby = $query->get( 'orderby' );

            if ( 'baochi_order' === $orderby ) {
                $query->set( 'meta_key', 'custom_baochi_order' );
                $query->set( 'orderby', 'meta_value_num' );
            }
        }

        public function theme_metabox_options()
        {
            $sections   = array();
            $sections[] = array(
                'id'        => '_custom_metabox_baochi_options',
                'title'     => __( 'Thông tin', 'umeno' ),
                'post_type' => 'baochi',
                'context'   => 'normal',
                'priority'  => 'high',
                'prefix'    => 'custom_baochi_',
                'sections'  => array(
                    array(
                        'name'   => 'post_formats',
                        'icon'   => 'fa fa-picture-o',
                        'fields' => array(
                            array(
                                'id'    => 'link',
                                'type'  => 'text',
                                'title' => __( 'Link bài báo', 'umeno' ),
                            ),
                            array(
                                'id'      => 'order',
                                'type'    => 'number',
                                'title'   => __( 'Số thứ tự sắp xếp', 'umeno' ),
                                'desc'    => esc_html__( 'Là 1 số nguyên dương, bài nào có số thứ tự nhỏ hơn sẽ hiển thị trước, nếu có nhiều bài có số thứ tự bằng nhau thì bài nào có thời gian xuất bản sớm hơn sẽ hiển thị trước.', 'masino' ),
                                'default' => 1,
                            ),
                        )
                    )
                )
            );
            if ( class_exists( 'OVIC_Metabox' ) ) {
                OVIC_Metabox::instance( $sections );
            }
        }
    }

    new Ictu_Baochi();
}

?>