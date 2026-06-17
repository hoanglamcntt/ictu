<?php if ( !defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.
/*==========================================================================
THEME BOX OPTIONS
===========================================================================*/
if ( !function_exists( 'settings_theme_options' ) && class_exists( 'OVIC_Options' ) ) {
    function settings_theme_options()
    {
        $options                 = array();
        $options['general_main'] = array(
            'name'     => 'general_main',
            'icon'     => 'fa fa-wordpress',
            'title'    => __( 'General', 'ictu' ),
            'sections' => array(
                array(
                    'title'  => __( 'General', 'ictu' ),
                    'fields' => array(
                        'main_container'   => array(
                            'id'      => 'main_container',
                            'type'    => 'slider',
                            'title'   => __( 'Main Container', 'ictu' ),
                            'min'     => 1140,
                            'max'     => 1920,
                            'step'    => 10,
                            'unit'    => 'px',
                            'default' => 1322,
                        ),
                        'logo'             => array(
                            'id'    => 'logo',
                            'type'  => 'image',
                            'title' => __( 'Logo', 'ictu' ),
                            'desc'  => __( 'Setting Logo For Site', 'ictu' ),
                        ),
                        'sticky_logo'      => array(
                            'id'    => 'sticky_logo',
                            'type'  => 'image',
                            'title' => __( 'Sticky Logo', 'ictu' ),
                            'desc'  => __( 'The logo shows on scroll', 'ictu' ),
                        ),
                        'organize_name'    => array(
                            'id'      => 'organize_name',
                            'type'    => 'textarea',
                            'title'   => __( 'Organize Name', 'ictu' ),
                            'desc'    => __( 'The text showed near by the logo', 'ictu' ),
                            'default' => '',
                        ),
                        'main_color'       => array(
                            'id'      => 'main_color',
                            'type'    => 'color',
                            'rgba'    => true,
                            'default' => '#006cb5',
                            'title'   => __( 'Main Color', 'ictu' ),
                        ),
                        'main_color_hover' => array(
                            'id'      => 'main_color_hover',
                            'type'    => 'color',
                            'rgba'    => true,
                            'default' => '#f7aa23',
                            'title'   => __( 'Main Color Hover', 'ictu' ),
                        ),
                        'text_color'       => array(
                            'id'      => 'text_color',
                            'type'    => 'color',
                            'rgba'    => true,
                            'default' => '#333333',
                            'title'   => __( 'Text Color', 'ictu' ),
                        ),
                        'page_banner'      => array(
                            'id'    => 'page_banner',
                            'type'  => 'image',
                            'title' => __( 'Page banner', 'ictu' ),
                            'desc'  => __( 'Setting banner for this Page', 'ictu' ),
                        )
                    ),
                ),
                array(
                    'title'  => 'Enable/Disable',
                    'fields' => array(
                        'enable_lazy_load'   => array(
                            'id'    => 'enable_lazy_load',
                            'type'  => 'switcher',
                            'title' => 'Enable Lazy Load',
                        ),
                        'disable_crop_image' => array(
                            'id'    => 'disable_crop_image',
                            'type'  => 'switcher',
                            'title' => 'Disable Crop Image',
                        ),
                        'enable_back_to_top' => array(
                            'id'    => 'enable_back_to_top',
                            'type'  => 'switcher',
                            'title' => 'Enable Back To Top Button',
                        )
                    ),
                ),
                array(
                    'title'  => 'Sidebar Settings',
                    'fields' => array(
                        array(
                            'id'           => 'multi_sidebar',
                            'type'         => 'repeater',
                            'button_title' => 'Add Sidebar',
                            'title'        => 'Multi Sidebar',
                            'fields'       => array(
                                array(
                                    'id'    => 'add_sidebar',
                                    'type'  => 'text',
                                    'title' => 'Name Sidebar',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title'  => 'Popup Newsletter',
                    'fields' => array(
                        'enable_popup'      => array(
                            'id'    => 'enable_popup',
                            'type'  => 'switcher',
                            'title' => 'Enable Popup',
                        ),
                        'popup_page'        => array(
                            'id'         => 'popup_page',
                            'type'       => 'select',
                            'title'      => 'Popup Page',
                            'options'    => 'page',
                            'multiple'   => true,
                            'ajax'       => true,
                            'chosen'     => true,
                            'desc'       => 'The page popup will be show.',
                            'dependency' => array( 'enable_popup', '==', 1 )
                        ),
                        'popup_type'        => array(
                            'id'         => 'popup_type',
                            'type'       => 'select',
                            'title'      => esc_html__( 'Popup Type', 'ictu' ),
                            'options'    => array(
                                'registered_form' => esc_html__( 'Registered form', 'ictu' ),
                                'link_to_page'    => esc_html__( 'Link to page', 'ictu' ),
                            ),
                            'default'    => 'registered_form',
                            'dependency' => array( 'enable_popup', '==', 1 )
                        ),
                        'link'              => array(
                            'id'         => 'link',
                            'type'       => 'text',
                            'title'      => esc_html__( 'Link to page', 'ictu' ),
                            'desc'       => esc_html__( 'Link to destination page', 'ictu' ),
                            'dependency' => array( 'enable_popup|popup_type', '==|==', '1|link_to_page' ),
                        ),
                        'popup_effect'      => array(
                            'id'         => 'popup_effect',
                            'type'       => 'select',
                            'title'      => 'Effect',
                            'options'    => array(
                                'mfp-zoom-in'         => 'Zoom In',
                                'mfp-newspaper'       => 'Newspaper',
                                'mfp-move-horizontal' => 'Horizontal Move',
                                'mfp-move-from-top'   => 'Move From Top',
                                'mfp-3d-unfold'       => '3D Unfold',
                                'mfp-zoom-out'        => 'Zoom Out',
                            ),
                            'default'    => 'mfp-zoom-in',
                            'dependency' => array( 'enable_popup', '==', 1 )
                        ),
                        'popup_bg'          => array(
                            'id'         => 'popup_bg',
                            'type'       => 'image',
                            'title'      => 'Image',
                            'dependency' => array( 'enable_popup', '==', 1 )
                        ),
                        'popup_title'       => array(
                            'id'         => 'popup_title',
                            'type'       => 'textarea',
                            'title'      => 'Title',
                            'desc'       => 'Use {text} for highlight text',
                            'dependency' => array( 'enable_popup|popup_type', '==|==', '1|registered_form' )
                        ),
                        'popup_desc'        => array(
                            'id'         => 'popup_desc',
                            'type'       => 'textarea',
                            'title'      => 'Subtitle',
                            'dependency' => array( 'enable_popup|popup_type', '==|==', '1|registered_form' )
                        ),
                        'input_placeholder' => array(
                            'id'         => 'input_placeholder',
                            'type'       => 'text',
                            'title'      => 'Input Placeholder',
                            'default'    => 'Your e-mail address',
                            'dependency' => array( 'enable_popup|popup_type', '==|==', '1|registered_form' )
                        ),
                        'popup_button'      => array(
                            'id'         => 'popup_button',
                            'type'       => 'text',
                            'title'      => 'Button',
                            'default'    => 'Subscribe',
                            'dependency' => array( 'enable_popup|popup_type', '==|==', '1|registered_form' )
                        ),
                        'popup_delay'       => array(
                            'id'         => 'popup_delay',
                            'type'       => 'spinner',
                            'title'      => 'Delay',
                            'step'       => 1,
                            'min'        => 0,
                            'max'        => 9999,
                            'unit'       => 'milliseconds',
                            'default'    => 1000,
                            'dependency' => array( 'enable_popup', '==', 1 )
                        ),
                    ),
                ),
                array(
                    'title'  => 'Custom Css/js',
                    'fields' => array(
                        'ace_style'  => array(
                            'id'       => 'ace_style',
                            'title'    => 'Editor Style',
                            'type'     => 'code_editor',
                            'settings' => array(
                                'theme' => 'dracula',
                                'mode'  => 'css',
                            )
                        ),
                        'ace_script' => array(
                            'id'       => 'ace_script',
                            'title'    => 'Editor Javascript',
                            'type'     => 'code_editor',
                            'settings' => array(
                                'theme' => 'dracula',
                                'mode'  => 'javascript',
                            )
                        ),
                    ),
                ),
            ),
        );
        $options['header_main']  = array(
            'name'   => 'header_main',
            'icon'   => 'fa fa-folder-open-o',
            'title'  => 'Header',
            'fields' => array(
                'header_template' => array(
                    'id'         => 'header_template',
                    'type'       => 'select_preview',
                    'title'      => __( 'Desktop', 'ictu' ),
                    'options'    => theme_file_options( '/templates/headers/', 'header' ),
                    'default'    => 'style-01',
                    'attributes' => array( 'data-depend-id' => 'header_template' ),
                ),
                'sticky_menu'     => array(
                    'id'    => 'sticky_menu',
                    'type'  => 'switcher',
                    'title' => __( 'Header Sticky', 'ictu' )
                ),
                'header_contacts' => array(
                    'id'              => 'header_contacts',
                    'type'            => 'group',
                    'title'           => __( 'Top Left Menu', 'ictu' ),
                    'button_title'    => __( 'Add New Menu Item', 'ictu' ),
                    'accordion_title' => __( 'Menu Item Settings', 'ictu' ),
                    'max'             => 5,
                    'fields'          => array(
                        array(
                            'id'      => 'title',
                            'type'    => 'text',
                            'title'   => __( 'Title', 'ictu' ),
                            'default' => ''
                        ),
                        array(
                            'id'      => 'link',
                            'type'    => 'text',
                            'title'   => __( 'Url', 'ictu' ),
                            'default' => '#'
                        ),
                        array(
                            'id'      => 'type',
                            'type'    => 'select',
                            'title'   => __( 'Element Type', 'ictu' ),
                            'options' => array(
                                'icon'  => __( 'Icon', 'ictu' ),
                                'image' => __( 'Image', 'ictu' )
                            ),
                            'default' => 'icon'
                        ),
                        array(
                            'id'         => 'icon',
                            'type'       => 'icon',
                            'title'      => __( 'Icon', 'ictu' ),
                            'default'    => 'fa fa-facebook',
                            'dependency' => array( 'type', '==', 'icon' )
                        ),
                        array(
                            'id'         => 'image',
                            'type'       => 'image',
                            'title'      => __( 'Image', 'ictu' ),
                            'dependency' => array( 'type', '==', 'image' )
                        )
                    )
                )
            )
        );
        $options['footer_main']  = array(
            'name'   => 'footer_main',
            'icon'   => 'fa fa-folder-open-o',
            'title'  => esc_html__( 'Footer', 'ictu' ),
            'fields' => array(
                'footer_template'        => array(
                    'id'         => 'footer_template',
                    'type'       => 'select_preview',
                    'title'      => __( 'Footer template', 'ictu' ),
                    'options'    => theme_file_options( '/templates/footer/', 'footer' ),
                    'default'    => 'style-01',
                    'attributes' => array( 'data-depend-id' => 'footer_template' ),
                ),
                'university_name'        => array(
                    'id'      => 'university_name',
                    'type'    => 'text',
                    'title'   => __( 'University Name', 'ictu' ),
                    'default' => 'trường đại học công nghệ thông tin và truyền thông'
                ),
                'university_address'     => array(
                    'id'      => 'university_address',
                    'type'    => 'text',
                    'title'   => __( 'Address', 'ictu' ),
                    'default' => 'Địa chỉ : Đường Z115, Quyết Thắng, Thành Phố Thái Nguyên.'
                ),
                'university_phone'       => array(
                    'id'      => 'university_phone',
                    'type'    => 'text',
                    'title'   => __( 'Phone', 'ictu' ),
                    'default' => 'DT : 0208.3846254'
                ),
                'university_fax'         => array(
                    'id'      => 'university_fax',
                    'type'    => 'text',
                    'title'   => __( 'Fax', 'ictu' ),
                    'default' => 'Fax : 0208.3846237'
                ),
                'chief_editor_label'     => array(
                    'id'      => 'chief_editor_label',
                    'type'    => 'text',
                    'title'   => __( 'Chief Editor Label', 'ictu' ),
                    'default' => 'Trưởng ban biên tập'
                ),
                'chief_editor_name'      => array(
                    'id'      => 'chief_editor_name',
                    'type'    => 'text',
                    'title'   => __( 'Name Of Chief Editor', 'ictu' ),
                    'default' => 'PGS.TS Phùng Trọng Nghĩa'
                ),
                'chief_editor_phone'     => array(
                    'id'      => 'chief_editor_phone',
                    'type'    => 'text',
                    'title'   => __( 'Chief Editor Phone', 'ictu' ),
                    'default' => 'ĐT : 0208.3846254'
                ),
                'chief_editor_email'     => array(
                    'id'      => 'chief_editor_email',
                    'type'    => 'text',
                    'title'   => __( 'Chief Editor Email', 'ictu' ),
                    'default' => 'Email : ptnghia@ictu.edu.vn'
                ),
                'chief_editor_note'      => array(
                    'id'    => 'chief_editor_note',
                    'type'  => 'text',
                    'title' => __( 'Chief Editor Note', 'ictu' ),
                ),
                'chief_editor_note_link' => array(
                    'id'    => 'chief_editor_note_link',
                    'type'  => 'text',
                    'title' => __( 'Chief Editor Note - Link', 'ictu' ),
                ),
                'organizations_logo'     => array(
                    'id'    => 'organizations_logo',
                    'type'  => 'gallery',
                    'title' => __( 'Organizations Logo', 'ictu' )
                ),
                'copyright_text'         => array(
                    'id'      => 'copyright_text',
                    'type'    => 'text',
                    'title'   => __( 'Copyright', 'ictu' ),
                    'default' => 'Bản quyền © 2021 TRƯỜNG ĐẠI HỌC CÔNG NGHỆ THÔNG TIN VÀ TRUYỀN THÔNG. TẤT CẢ CÁC QUYỀN. CHÍNH SÁCH BẢO MẬT'
                )
            )
        );
        $options['posts_main']   = array(
            'name'     => 'posts_main',
            'icon'     => 'fa fa-rss',
            'title'    => esc_html__( 'Blog', 'ictu' ),
            'sections' => array(
                array(
                    'title'  => esc_html__( 'Blog Page', 'ictu' ),
                    'fields' => array(
                        'sidebar_blog_layout' => array(
                            'id'      => 'sidebar_blog_layout',
                            'type'    => 'image_select',
                            'title'   => esc_html__( 'Blog Sidebar', 'ictu' ),
                            'desc'    => esc_html__( 'Select sidebar position on Blog.', 'ictu' ),
                            'options' => array(
                                'left'  => get_theme_file_uri( 'assets/images/left-sidebar.png' ),
                                'right' => get_theme_file_uri( 'assets/images/right-sidebar.png' ),
                                'full'  => get_theme_file_uri( 'assets/images/no-sidebar.png' ),
                            ),
                            'default' => 'left',
                        ),
                        'blog_used_sidebar'   => array(
                            'id'         => 'blog_used_sidebar',
                            'type'       => 'select',
                            'default'    => 'widget-area',
                            'title'      => esc_html__( 'Sidebar Used for Blog', 'ictu' ),
                            'options'    => 'sidebars',
                            'dependency' => array( 'sidebar_blog_layout', '!=', 'full' ),
                        ),
                        'blog_pagination'     => array(
                            'id'      => 'blog_pagination',
                            'type'    => 'button_set',
                            'title'   => esc_html__( 'Blog Pagination', 'ictu' ),
                            'options' => array(
                                'pagination' => esc_html__( 'Pagination', 'ictu' ),
                                'load_more'  => esc_html__( 'Load More', 'ictu' ),
                                'infinite'   => esc_html__( 'Infinite Scrolling', 'ictu' ),
                            ),
                            'default' => 'pagination',
                            'desc'    => esc_html__( 'Select style pagination on blog page.', 'ictu' ),
                        ),
                    ),
                ),
                array(
                    'title'  => esc_html__( 'Post Single', 'ictu' ),
                    'fields' => array(
                        'sidebar_single_layout' => array(
                            'id'      => 'sidebar_single_layout',
                            'type'    => 'image_select',
                            'title'   => esc_html__( 'Post Single Sidebar', 'ictu' ),
                            'desc'    => esc_html__( 'Select sidebar position on Blog.', 'ictu' ),
                            'options' => array(
                                'left'  => get_theme_file_uri( 'assets/images/left-sidebar.png' ),
                                'right' => get_theme_file_uri( 'assets/images/right-sidebar.png' ),
                                'full'  => get_theme_file_uri( 'assets/images/no-sidebar.png' ),
                            ),
                            'default' => 'left',
                        ),
                        'single_used_sidebar'   => array(
                            'id'         => 'single_used_sidebar',
                            'type'       => 'select',
                            'default'    => 'widget-area',
                            'title'      => esc_html__( 'Sidebar Used for Post', 'ictu' ),
                            'options'    => 'sidebars',
                            'dependency' => array( 'sidebar_single_layout', '!=', 'full' ),
                        )
                    ),
                ),
            ),
        );
        if ( class_exists( 'WooCommerce' ) ) {
            $options['woocommerce_mains'] = array(
                'name'     => 'woocommerce_mains',
                'icon'     => 'fa fa-shopping-bag',
                'title'    => esc_html__( 'WooCommerce', 'ictu' ),
                'sections' => array(
                    array(
                        'title'  => esc_html__( 'Shop Page', 'ictu' ),
                        'fields' => array(
                            'shop_banner'              => array(
                                'id'    => 'shop_banner',
                                'type'  => 'image',
                                'title' => __( 'Shop Banner', 'ictu' ),
                            ),
                            'shop_list_style'          => array(
                                'id'      => 'shop_list_style',
                                'type'    => 'image_select',
                                'default' => 'grid',
                                'title'   => esc_html__( 'Shop Layout', 'ictu' ),
                                'desc'    => esc_html__( 'Select layout for shop product, product category archive.', 'ictu' ),
                                'options' => array(
                                    'grid' => get_theme_file_uri( 'assets/images/grid-display.png' ),
                                    'list' => get_theme_file_uri( 'assets/images/list-display.png' ),
                                ),
                            ),
                            'product_loop_columns'     => array(
                                'id'         => 'product_loop_columns',
                                'type'       => 'spinner',
                                'title'      => esc_html__( 'Grid Columns', 'ictu' ),
                                'max'        => 5,
                                'min'        => 2,
                                'step'       => 2,
                                'unit'       => 'columns',
                                'default'    => 4,
                                'dependency' => array( 'shop_list_style', '==', 'grid' ),
                            ),
                            'product_per_page'         => array(
                                'id'      => 'product_per_page',
                                'type'    => 'spinner',
                                'default' => '20',
                                'unit'    => 'items',
                                'title'   => esc_html__( 'Shop Per Page', 'ictu' ),
                            ),
                            'sidebar_shop_layout'      => array(
                                'id'      => 'sidebar_shop_layout',
                                'type'    => 'image_select',
                                'title'   => esc_html__( 'Shop Sidebar', 'ictu' ),
                                'desc'    => esc_html__( 'Select sidebar position on Shop Page.', 'ictu' ),
                                'options' => array(
                                    'left'  => get_theme_file_uri( 'assets/images/left-sidebar.png' ),
                                    'right' => get_theme_file_uri( 'assets/images/right-sidebar.png' ),
                                    'full'  => get_theme_file_uri( 'assets/images/no-sidebar.png' ),
                                ),
                                'default' => 'left',
                            ),
                            'shop_used_sidebar'        => array(
                                'id'         => 'shop_used_sidebar',
                                'type'       => 'select',
                                'default'    => 'shop-widget-area',
                                'title'      => esc_html__( 'Sidebar Used for Shop', 'ictu' ),
                                'options'    => 'sidebars',
                                'dependency' => array( 'sidebar_shop_layout', '!=', 'full' ),
                            ),
                            'shop_vendor_used_sidebar' => array(
                                'id'         => 'shop_vendor_used_sidebar',
                                'type'       => 'select',
                                'title'      => esc_html__( 'Sidebar Used for Vendor', 'ictu' ),
                                'options'    => 'sidebars',
                                'dependency' => array( 'sidebar_shop_layout', '!=', 'full' ),
                            ),
                            'woocommerce_pagination'   => array(
                                'id'      => 'woocommerce_pagination',
                                'type'    => 'button_set',
                                'title'   => esc_html__( 'Shop Pagination', 'ictu' ),
                                'options' => array(
                                    'pagination' => esc_html__( 'Pagination', 'ictu' ),
                                    'load_more'  => esc_html__( 'Load More', 'ictu' ),
                                    'infinite'   => esc_html__( 'Infinite Scrolling', 'ictu' ),
                                ),
                                'default' => 'pagination',
                                'desc'    => esc_html__( 'Select style pagination on shop page.', 'ictu' ),
                            ),
                            //							'shop_content_top'          => array(
                            //								'id'          => 'shop_content_top',
                            //								'type'        => 'select',
                            //								'options'     => 'page',
                            //								'chosen'      => true,
                            //								'ajax'        => true,
                            //								'placeholder' => esc_html__( 'Select page', 'ictu' ),
                            //								'title'       => esc_html__( 'Shop Content Top', 'ictu' ),
                            //								'desc'        => esc_html__( 'Get shop content on top from page builder.', 'ictu' ),
                            //							),
                            //							'shop_content_top_position' => array(
                            //								'id'      => 'shop_content_top_position',
                            //								'type'    => 'select',
                            //								'title'   => esc_html__( 'Content Top Position', 'ictu' ),
                            //								'options' => array(
                            //									'inside'  => esc_html__( 'Inside', 'ictu' ),
                            //									'outside' => esc_html__( 'Outside', 'ictu' ),
                            //								),
                            //								'default' => 'inside',
                            //							),
                            //							'shop_content_bot'          => array(
                            //								'id'          => 'shop_content_bot',
                            //								'type'        => 'select',
                            //								'options'     => 'page',
                            //								'chosen'      => true,
                            //								'ajax'        => true,
                            //								'placeholder' => esc_html__( 'Select page', 'ictu' ),
                            //								'title'       => esc_html__( 'Shop Content Bottom', 'ictu' ),
                            //								'desc'        => esc_html__( 'Get shop content on bottom from page builder.', 'ictu' ),
                            //							),
                        ),
                    ),
                    array(
                        'title'  => esc_html__( 'Shop Products', 'ictu' ),
                        'fields' => array(
                            'product_hover'      => array(
                                'id'      => 'product_hover',
                                'type'    => 'button_set',
                                'title'   => esc_html__( 'Product Image Hover', 'ictu' ),
                                'options' => array(
                                    'none'   => esc_html__( 'None', 'ictu' ),
                                    'change' => esc_html__( 'Change Image', 'ictu' ),
                                    'zoom'   => esc_html__( 'Zoom Image', 'ictu' ),
                                    'slide'  => esc_html__( 'Slide Image', 'ictu' ),
                                ),
                                'default' => 'none',
                            ),
                            'product_newness'    => array(
                                'id'      => 'product_newness',
                                'default' => '100',
                                'type'    => 'spinner',
                                'unit'    => 'days',
                                'title'   => esc_html__( 'Products Newness', 'ictu' ),
                            ),
                            'enable_short_title' => array(
                                'id'    => 'enable_short_title',
                                'type'  => 'switcher',
                                'title' => esc_html__( 'Enable Short Title on Mobile (<768px)', 'ictu' ),
                            ),
                        ),
                    ),
                    array(
                        'title'  => esc_html__( 'Single Product', 'ictu' ),
                        'fields' => array(
                            'disable_zoom'           => array(
                                'id'    => 'disable_zoom',
                                'type'  => 'switcher',
                                'title' => esc_html__( 'Disable Zoom Gallery', 'ictu' ),
                            ),
                            'disable_lightbox'       => array(
                                'id'    => 'disable_lightbox',
                                'type'  => 'switcher',
                                'title' => esc_html__( 'Disable Lightbox Gallery', 'ictu' ),
                            ),
                            'sidebar_product_layout' => array(
                                'id'      => 'sidebar_product_layout',
                                'type'    => 'image_select',
                                'title'   => __( 'Single Product Layout', 'ictu' ),
                                'desc'    => __( 'Select sidebar position on Shop Page.', 'ictu' ),
                                'options' => array(
                                    'left'  => get_theme_file_uri( 'assets/images/left-sidebar.png' ),
                                    'right' => get_theme_file_uri( 'assets/images/right-sidebar.png' ),
                                    'full'  => get_theme_file_uri( 'assets/images/no-sidebar.png' ),
                                ),
                                'default' => 'left',
                            ),
                            'product_used_sidebar'   => array(
                                'id'         => 'product_used_sidebar',
                                'type'       => 'select',
                                'default'    => 'product-widget-area',
                                'title'      => esc_html__( 'Sidebar used for single product', 'ictu' ),
                                'options'    => 'sidebars',
                                'dependency' => array( 'sidebar_product_layout', '!=', 'full' ),
                            )
                        )
                    ),
                    array(
                        'title'  => esc_html__( 'Related Products', 'ictu' ),
                        'fields' => array(
                            'woo_related_enable'  => array(
                                'id'      => 'woo_related_enable',
                                'type'    => 'button_set',
                                'default' => 'enable',
                                'options' => array(
                                    'enable'  => esc_html__( 'Enable', 'ictu' ),
                                    'disable' => esc_html__( 'Disable', 'ictu' ),
                                ),
                                'title'   => esc_html__( 'Enable Related Products', 'ictu' ),
                            ),
                            'woo_related_title'   => array(
                                'id'         => 'woo_related_title',
                                'title'      => esc_html__( 'Disable Title', 'ictu' ),
                                'type'       => 'button_set',
                                'default'    => 'enable',
                                'options'    => array(
                                    'enable'  => esc_html__( 'Enable', 'ictu' ),
                                    'disable' => esc_html__( 'Disable', 'ictu' ),
                                ),
                                'dependency' => array( 'woo_related_enable', '==', 'enable' ),
                            ),
                            'woo_related_columns' => array(
                                'id'         => 'woo_related_columns',
                                'type'       => 'spinner',
                                'title'      => esc_html__( 'Items Columns', 'ictu' ),
                                'max'        => 8,
                                'min'        => 4,
                                'step'       => 1,
                                'unit'       => 'columns',
                                'default'    => 4,
                                'dependency' => array( 'woo_related_enable', '==', 'enable' ),
                            ),
                            'woo_related_perpage' => array(
                                'id'         => 'woo_related_perpage',
                                'type'       => 'spinner',
                                'title'      => esc_html__( 'Items Per Page', 'ictu' ),
                                'unit'       => 'items',
                                'default'    => '6',
                                'dependency' => array( 'woo_related_enable', '==', 'enable' ),
                            ),
                        ),
                    ),
                    /* array(
                         'title'  => esc_html__('Upsell Products', 'ictu'),
                         'fields' => array(
                             'woo_upsell_enable'  => array(
                                 'id'      => 'woo_upsell_enable',
                                 'type'    => 'button_set',
                                 'default' => 'enable',
                                 'options' => array(
                                     'enable'  => esc_html__('Enable', 'ictu'),
                                     'disable' => esc_html__('Disable', 'ictu'),
                                 ),
                                 'title'   => esc_html__('Enable Upsell Products', 'ictu'),
                             ),
                             'woo_upsell_title'   => array(
                                 'id'         => 'woo_upsell_title',
                                 'type'       => 'text',
                                 'title'      => esc_html__('Title', 'ictu'),
                                 'default'    => esc_html__('Upsell Products', 'ictu'),
                                 'dependency' => array('woo_upsell_enable', '==', 'enable'),
                             ),
                             'woo_upsell_columns' => array(
                                 'id'         => 'woo_upsell_columns',
                                 'type'       => 'spinner',
                                 'title'      => esc_html__('Items Columns', 'ictu'),
                                 'max'        => 8,
                                 'min'        => 4,
                                 'step'       => 1,
                                 'unit'       => 'columns',
                                 'default'    => 4,
                                 'dependency' => array('woo_upsell_enable', '==', 'enable'),
                             ),
                         ),
                     ),*/
                ),
            );
        }
        $options['social']     = array(
            'name'   => 'social',
            'icon'   => 'fa fa-users',
            'title'  => esc_html__( 'Social', 'ictu' ),
            'fields' => array(
                array(
                    'id'              => 'user_all_social',
                    'type'            => 'group',
                    'title'           => esc_html__( 'Social', 'ictu' ),
                    'button_title'    => esc_html__( 'Add New Social', 'ictu' ),
                    'accordion_title' => esc_html__( 'Social Settings', 'ictu' ),
                    'fields'          => array(
                        array(
                            'id'      => 'title_social',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Title Social', 'ictu' ),
                            'default' => 'Facebook',
                        ),
                        array(
                            'id'      => 'link_social',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Link Social', 'ictu' ),
                            'default' => 'https://facebook.com',
                        ),
                        array(
                            'id'      => 'icon_social',
                            'type'    => 'icon',
                            'title'   => esc_html__( 'Icon Social', 'ictu' ),
                            'default' => 'fa fa-facebook',
                        ),
                    ),
                    'default'         => array(
                        array(
                            'title_social' => 'Facebook',
                            'link_social'  => 'https://facebook.com/',
                            'icon_social'  => 'fa fa-facebook',
                        ),
                        array(
                            'title_social' => 'Twitter',
                            'link_social'  => 'https://twitter.com/',
                            'icon_social'  => 'fa fa-twitter',
                        ),
                        array(
                            'title_social' => 'Youtube',
                            'link_social'  => 'https://youtube.com/',
                            'icon_social'  => 'fa fa-youtube',
                        ),
                        array(
                            'title_social' => 'Pinterest',
                            'link_social'  => 'https://pinterest.com/',
                            'icon_social'  => 'fa fa-pinterest',
                        ),
                        array(
                            'title_social' => 'Instagram',
                            'link_social'  => 'https://instagram.com/',
                            'icon_social'  => 'fa fa-instagram',
                        ),
                    ),
                ),
            ),
        );
        $options['typography'] = array(
            'name'   => 'typography',
            'icon'   => 'fa fa-font',
            'title'  => esc_html__( 'Typography', 'ictu' ),
            'fields' => array(
                'body_typography' => array(
                    'id'                 => 'body_typography',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'Typography of Body', 'ictu' ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'font_size'          => true,
                    'line_height'        => false,
                    'letter_spacing'     => false,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => true,
                    'subset'             => true,
                    'extra_styles'       => true,
                    'backup_font_family' => false,
                    'font_variant'       => false,
                    'word_spacing'       => false,
                    'text_decoration'    => false,
                    'output'             => 'body',
                ),
            ),
        );
        $options['backup']     = array(
            'name'   => 'backup',
            'icon'   => 'fa fa-bold',
            'title'  => esc_html__( 'Backup / Reset', 'ictu' ),
            'fields' => array(
                'reset'             => array(
                    'id'    => 'reset',
                    'type'  => 'backup',
                    'title' => esc_html__( 'Reset', 'ictu' ),
                ),
                'delete_transients' => array(
                    'id'      => 'delete_transients',
                    'type'    => 'content',
                    'content' => '<a href="#" data-text-done="' . esc_attr__( '%n transient database entries have been deleted.', 'ictu' ) . '" class="button button-primary delete-transients"/>' . esc_html__( 'Delete Transients', 'ictu' ) . '</a><span class="spinner" style="float:none;"></span>',
                    'title'   => esc_html__( 'Delete Transients', 'ictu' ),
                    'desc'    => esc_html__( 'All transient related database entries will be deleted.', 'ictu' ),
                    'after'   => ' <p class="ovic-text-success"></p>',
                ),
            ),
        );
        //
        // Framework Settings
        //
        $settings = array(
            'option_name'      => '_ovic_customize_options',
            'menu_title'       => esc_html__( 'Theme Options', 'ictu' ),
            'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
            'menu_parent'      => 'ovic_addon-dashboard',
            'menu_slug'        => 'ovic_theme_options',
            'menu_position'    => 5,
            'show_search'      => true,
            'show_reset'       => true,
            'show_footer'      => false,
            'show_all_options' => true,
            'ajax_save'        => true,
            'sticky_header'    => false,
            'save_defaults'    => true,
            'framework_title'  => 'Theme Options <small>by <a href="#" target="_blank">Avocado</a></small>'
        );

        OVIC_Options::instance( $settings, apply_filters( 'settings_theme_options', $options ) );
    }

    add_action( 'init', 'settings_theme_options' );
}