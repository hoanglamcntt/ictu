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
        $options['home_page']    = array(
            'name'     => 'header_main',
            'icon'     => 'fa fa-building-o',
            'title'    => 'Homepage',
            'sections' => array(
                array(
                    'title'  => esc_html__( 'Slideshow', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'     => 'slideshow',
                            'type'   => 'group',
                            'title'  => __( 'Item', 'ictu' ),
                            'fields' => array(
                                array(
                                    'id'    => 'desktop',
                                    'type'  => 'image',
                                    'title' => __( 'Desktop', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'mobile',
                                    'type'  => 'image',
                                    'title' => __( 'Mobile', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'link',
                                    'type'  => 'text',
                                    'title' => __( 'Link', 'ictu' ),
                                ),
                            )
                        ),
                    )
                ),
                array(
                    'title'  => esc_html__( 'Thông tin chung', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'   => 'news_tab',
                            'type' => 'tabbed',
                            'tabs' => array(
                                array(
                                    'title'  => 'Tab 1',
                                    'fields' => array(
                                        array(
                                            'id'      => 'tab1_title',
                                            'type'    => 'text',
                                            'title'   => __( 'Title', 'ictu' ),
                                            'default' => 'Tin tức - Sự kiện',
                                        ),
                                        array(
                                            'id'    => 'tab1_special_post',
                                            'type'  => 'number',
                                            'title' => __( 'Special post id', 'ictu' ),
                                            'desc'  => __( 'Example: 1', 'ictu' ),
                                        ),
                                        array(
                                            'id'      => 'tab1_posts_category',
                                            'type'    => 'select',
                                            'options' => 'category',
                                            'chosen'  => true,
                                            'ajax'    => true,
                                            'title'   => __( 'Posts category', 'ictu' ),
                                        ),
                                        array(
                                            'id'    => 'tab1_posts_ids',
                                            'type'  => 'text',
                                            'title' => __( 'Posts id', 'ictu' ),
                                            'desc'  => __( 'Example: 1, 2, 3', 'ictu' ),
                                        ),
                                        array(
                                            'id'    => 'tab1_posts_more',
                                            'type'  => 'text',
                                            'title' => __( 'View more link', 'ictu' ),
                                            'desc'  => __( 'Default is category link', 'ictu' ),
                                        ),
                                    ),
                                ),
                                array(
                                    'title'  => 'Tab 2',
                                    'fields' => array(
                                        array(
                                            'id'      => 'tab2_title',
                                            'type'    => 'text',
                                            'title'   => __( 'Title', 'ictu' ),
                                            'default' => 'Thông báo',
                                        ),
                                        array(
                                            'id'    => 'tab2_special_post',
                                            'type'  => 'number',
                                            'title' => __( 'Special post id', 'ictu' ),
                                            'desc'  => __( 'Example: 1', 'ictu' ),
                                        ),
                                        array(
                                            'id'      => 'tab2_posts_category',
                                            'type'    => 'select',
                                            'options' => 'category',
                                            'chosen'  => true,
                                            'ajax'    => true,
                                            'title'   => __( 'Posts category', 'ictu' ),
                                        ),
                                        array(
                                            'id'    => 'tab2_posts_ids',
                                            'type'  => 'text',
                                            'title' => __( 'Posts id', 'ictu' ),
                                            'desc'  => __( 'Example: 1, 2, 3', 'ictu' ),
                                        ),
                                        array(
                                            'id'    => 'tab2_posts_more',
                                            'type'  => 'text',
                                            'title' => __( 'View more link', 'ictu' ),
                                            'desc'  => __( 'Default is category link', 'ictu' ),
                                        ),
                                    ),
                                ),
                                array(
                                    'title'  => 'Tab 3',
                                    'fields' => array(
                                        array(
                                            'id'      => 'tab3_title',
                                            'type'    => 'text',
                                            'title'   => __( 'Title', 'ictu' ),
                                            'default' => 'Tuyển sinh',
                                        ),
                                        array(
                                            'id'    => 'tab3_special_img',
                                            'type'  => 'gallery',
                                            'title' => __( 'Special image', 'ictu' ),
                                        ),
                                        array(
                                            'id'     => 'tab3_special_link',
                                            'type'   => 'group',
                                            'title'  => __( 'Special link', 'ictu' ),
                                            'fields' => array(
                                                array(
                                                    'id'    => 'title',
                                                    'type'  => 'text',
                                                    'title' => __( 'Title', 'ictu' ),
                                                ),
                                                array(
                                                    'id'    => 'link',
                                                    'type'  => 'text',
                                                    'title' => __( 'Link', 'ictu' ),
                                                ),
                                            )
                                        ),
                                        array(
                                            'id'     => 'tab3_special_phone',
                                            'type'   => 'group',
                                            'title'  => __( 'Special phone', 'ictu' ),
                                            'fields' => array(
                                                array(
                                                    'id'    => 'phone',
                                                    'type'  => 'text',
                                                    'title' => __( 'Phone', 'ictu' ),
                                                ),
                                            )
                                        ),
                                        array(
                                            'id'    => 'tab3_special_facebook',
                                            'type'  => 'text',
                                            'title' => __( 'Special Facebook', 'ictu' ),
                                        ),
                                        array(
                                            'id'      => 'tab3_posts_category',
                                            'type'    => 'select',
                                            'options' => 'category',
                                            'chosen'  => true,
                                            'ajax'    => true,
                                            'title'   => __( 'Posts category', 'ictu' ),
                                        ),
                                        array(
                                            'id'    => 'tab3_posts_ids',
                                            'type'  => 'text',
                                            'title' => __( 'Posts id', 'ictu' ),
                                            'desc'  => __( 'Example: 1, 2, 3', 'ictu' ),
                                        ),
                                        array(
                                            'id'    => 'tab3_posts_more',
                                            'type'  => 'text',
                                            'title' => __( 'View more link', 'ictu' ),
                                        ),
                                    ),
                                ),

                            ),
                        ),
                    )
                ),
                array(
                    'title'  => esc_html__( 'Truy cập nhanh', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'     => 'access',
                            'type'   => 'group',
                            'title'  => __( 'Item', 'ictu' ),
                            'fields' => array(
                                array(
                                    'id'    => 'title',
                                    'type'  => 'text',
                                    'title' => __( 'Title', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'image',
                                    'type'  => 'image',
                                    'title' => __( 'Image', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'link',
                                    'type'  => 'text',
                                    'title' => __( 'Link', 'ictu' ),
                                ),
                            )
                        ),
                    )
                ),
//                array(
//                    'title'  => esc_html__( 'Tầm nhìn - Sứ mệnh', 'ictu' ),
//                    'fields' => array(
//                        array(
//                            'id'      => 'vision_title',
//                            'type'    => 'text',
//                            'title'   => __( 'Title', 'ictu' ),
//                            'default' => 'Tầm nhìn - Sứ mệnh',
//                        ),
//                        array(
//                            'id'    => 'vision_bg',
//                            'type'  => 'image',
//                            'title' => __( 'Background', 'ictu' ),
//                        ),
//                        array(
//                            'id'     => 'vision',
//                            'type'   => 'group',
//                            'title'  => __( 'Item', 'ictu' ),
//                            'fields' => array(
//                                array(
//                                    'id'    => 'title',
//                                    'type'  => 'text',
//                                    'title' => __( 'Title', 'ictu' ),
//                                ),
//                                array(
//                                    'id'    => 'image',
//                                    'type'  => 'image',
//                                    'title' => __( 'Image', 'ictu' ),
//                                ),
//                                array(
//                                    'id'     => 'text',
//                                    'type'   => 'group',
//                                    'title'  => __( 'Text', 'ictu' ),
//                                    'fields' => array(
//                                        array(
//                                            'id'    => 'text',
//                                            'type'  => 'textarea',
//                                            'title' => __( 'Text', 'ictu' ),
//                                        ),
//                                    )
//                                ),
//                            )
//                        ),
//                    )
//                ),
                array(
                    'title'  => esc_html__( 'Nhóm ngành đào tạo', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'      => 'program_title',
                            'type'    => 'text',
                            'title'   => __( 'Title', 'ictu' ),
                            'default' => 'Nhóm ngành đào tạo',
                        ),
                        array(
                            'id'     => 'program',
                            'type'   => 'group',
                            'title'  => __( 'Item', 'ictu' ),
                            'fields' => array(
                                array(
                                    'id'    => 'title',
                                    'type'  => 'text',
                                    'title' => __( 'Title', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'subtitle',
                                    'type'  => 'text',
                                    'title' => __( 'Subtitle', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'image',
                                    'type'  => 'image',
                                    'title' => __( 'Image', 'ictu' ),
                                ),
                                array(
                                    'id'     => 'text',
                                    'type'   => 'group',
                                    'title'  => __( 'Program', 'ictu' ),
                                    'fields' => array(
                                        array(
                                            'id'    => 'text',
                                            'type'  => 'text',
                                            'title' => __( 'Name', 'ictu' ),
                                        ),
                                        array(
                                            'id'    => 'link',
                                            'type'  => 'text',
                                            'title' => __( 'Link', 'ictu' ),
                                        ),
                                    )
                                ),
                            )
                        ),
                    )
                ),
                array(
                    'title'  => esc_html__( 'Bài viết', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'      => 'activities_title',
                            'type'    => 'text',
                            'title'   => __( 'Title', 'ictu' ),
                            'default' => 'Hợp tác đối ngoại',
                        ),
                        array(
                            'id'    => 'activities_special_post',
                            'type'  => 'number',
                            'title' => __( 'Special post id', 'ictu' ),
                            'desc'  => __( 'Example: 1', 'ictu' ),
                        ),
                        array(
                            'id'      => 'activities_posts_category',
                            'type'    => 'select',
                            'options' => 'category',
                            'chosen'  => true,
                            'ajax'    => true,
                            'title'   => __( 'Posts category', 'ictu' ),
                        ),
                        array(
                            'id'    => 'activities_posts_ids',
                            'type'  => 'text',
                            'title' => __( 'Posts id', 'ictu' ),
                            'desc'  => __( 'Example: 1, 2, 3', 'ictu' ),
                        ),
                        array(
                            'id'      => 'activities_button_link',
                            'type'    => 'text',
                            'title'   => __( 'Button link', 'ictu' ),
                            'default' => 'https://ictu.edu.vn/category/hop-tac-quoc-te/',
                        ),
                    )
                ),
                array(
                    'title'  => esc_html__( 'Báo chí nói về ICTU', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'      => 'press_title',
                            'type'    => 'text',
                            'title'   => __( 'Title', 'ictu' ),
                            'default' => 'Báo chí nói về ICTU',
                        ),
                        array(
                            'id'      => 'press_button_link',
                            'type'    => 'text',
                            'title'   => __( 'Button link', 'ictu' ),
                            'default' => 'https://ictu.edu.vn/bao-chi-noi-gi-ve-ictu/',
                        ),
                    )
                ),
                array(
                    'title'  => esc_html__( 'Chặng đường phát triển', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'      => 'impression_title',
                            'type'    => 'text',
                            'title'   => __( 'Title', 'ictu' ),
                            'default' => 'Chặng đường phát triển',
                        ),
                        array(
                            'id'     => 'impression',
                            'type'   => 'group',
                            'title'  => __( 'Item', 'ictu' ),
                            'fields' => array(
                                array(
                                    'id'    => 'number',
                                    'type'  => 'text',
                                    'title' => __( 'Year', 'ictu' ),
                                ),
//                                array(
//                                    'id'      => 'video_provider',
//                                    'type'    => 'select',
//                                    'title'   => __( 'Provider', 'ictu' ),
//                                    'options' => array(
//                                        'youtube'  => __( 'Youtube', 'ictu' ),
//                                        'vimeo'    => __( 'Vimeo', 'ictu' ),
//                                        'external' => __( 'External', 'ictu' ),
//                                    ),
//                                ),
//                                array(
//                                    'id'    => 'video_thumb',
//                                    'type'  => 'image',
//                                    'title' => __( 'Video Poster', 'ictu' ),
//                                ),
//                                array(
//                                    'id'    => 'video_src',
//                                    'type'  => 'text',
//                                    'title' => __( 'Video Src', 'ictu' ),
//                                ),
//                                array(
//                                    'id'    => 'video_caption',
//                                    'type'  => 'text',
//                                    'title' => __( 'Video Caption', 'ictu' ),
//                                ),
                                array(
                                    'id'    => 'gallery',
                                    'type'  => 'gallery',
                                    'title' => __( 'Gallery', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'desc',
                                    'type'  => 'textarea',
                                    'title' => __( 'Description', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'teacher',
                                    'type'  => 'text',
                                    'title' => __( 'Tỉ lệ giảng viên trình độ cao', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'training_programs',
                                    'type'  => 'text',
                                    'title' => __( 'Số ngành đào tạo', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'total_students',
                                    'type'  => 'text',
                                    'title' => __( 'Quy mô đào tạo ĐHCQ', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'total_labs',
                                    'type'  => 'text',
                                    'title' => __( 'Diện tích sà xây dựng', 'ictu' ),
                                ),
//                                array(
//                                    'id'    => 'link',
//                                    'type'  => 'text',
//                                    'title' => __( 'Article link', 'ictu' ),
//                                ),
                            )
                        ),
                        array(
                            'id'     => 'banner',
                            'type'   => 'group',
                            'title'  => __( 'Banner', 'ictu' ),
                            'fields' => array(
                                array(
                                    'id'    => 'image',
                                    'type'  => 'image',
                                    'title' => __( 'Banner', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'link',
                                    'type'  => 'text',
                                    'title' => __( 'Link', 'ictu' ),
                                ),
                            )
                        ),
                    )
                ),
                array(
                    'title'  => esc_html__( 'Sinh viên', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'      => 'student_title',
                            'type'    => 'text',
                            'title'   => __( 'Title', 'ictu' ),
                            'default' => 'Sinh viên',
                        ),
                        array(
                            'id'    => 'student_gallery',
                            'type'  => 'gallery',
                            'title' => __( 'Gallery', 'ictu' ),
                        ),
                        array(
                            'id'     => 'student',
                            'type'   => 'group',
                            'title'  => __( 'Item', 'ictu' ),
                            'fields' => array(
                                array(
                                    'id'    => 'title',
                                    'type'  => 'text',
                                    'title' => __( 'Title', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'icon',
                                    'type'  => 'icon',
                                    'title' => __( 'Icon', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'image',
                                    'type'  => 'image',
                                    'title' => __( 'Image', 'ictu' ),
                                    'desc'  => __( 'The image will replace the icon', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'link',
                                    'type'  => 'text',
                                    'title' => __( 'Link', 'ictu' ),
                                ),
                            )
                        ),
                    )
                ),
                array(
                    'title'  => esc_html__( 'Đánh giá, cảm nhận', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'      => 'comment_title',
                            'type'    => 'text',
                            'title'   => __( 'Title', 'ictu' ),
                            'default' => 'Đánh giá, cảm nhận',
                        ),
                        array(
                            'id'     => 'comment',
                            'type'   => 'group',
                            'title'  => __( 'Item', 'ictu' ),
                            'fields' => array(
                                array(
                                    'id'    => 'name',
                                    'type'  => 'text',
                                    'title' => __( 'Name', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'job',
                                    'type'  => 'text',
                                    'title' => __( 'Job Title', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'desc',
                                    'type'  => 'textarea',
                                    'title' => __( 'Description', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'avatar',
                                    'type'  => 'image',
                                    'title' => __( 'Avatar', 'ictu' ),
                                ),
                            )
                        ),
                    )
                ),
                array(
                    'title'  => esc_html__( 'Đối tác', 'ictu' ),
                    'fields' => array(
                        array(
                            'id'      => 'partner_title',
                            'type'    => 'text',
                            'title'   => __( 'Title', 'ictu' ),
                            'default' => 'Đối tác của ICTU',
                        ),
                        array(
                            'id'    => 'partner_image',
                            'type'  => 'image',
                            'title' => __( 'Image', 'ictu' ),
                        ),
                        array(
                            'id'     => 'partner',
                            'type'   => 'group',
                            'title'  => __( 'Item', 'ictu' ),
                            'fields' => array(
                                array(
                                    'id'    => 'name',
                                    'type'  => 'text',
                                    'title' => __( 'Name', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'logo',
                                    'type'  => 'image',
                                    'title' => __( 'Logo', 'ictu' ),
                                ),
                                array(
                                    'id'    => 'link',
                                    'type'  => 'text',
                                    'title' => __( 'Link', 'ictu' ),
                                ),
                            )
                        ),
                    )
                ),
            ),
        );
        $options['footer_main']  = array(
            'name'   => 'footer_main',
            'icon'   => 'fa fa-folder-open-o',
            'title'  => esc_html__( 'Footer', 'ictu' ),
            'fields' => array(
                array(
                    'id'    => 'footer_bg',
                    'type'  => 'image',
                    'title' => __( 'Background', 'ictu' ),
                ),
                array(
                    'id'      => 'university_name',
                    'type'    => 'text',
                    'title'   => __( 'Name', 'ictu' ),
                    'default' => 'Trường Đại học Công Nghệ Thông Tin và Truyền Thông'
                ),
                'university_address' => array(
                    'id'      => 'university_address',
                    'type'    => 'text',
                    'title'   => __( 'Address', 'ictu' ),
                    'default' => 'Địa chỉ : Đường Z115, Quyết Thắng, Thành Phố Thái Nguyên.'
                ),
                'university_phone'   => array(
                    'id'      => 'university_phone',
                    'type'    => 'text',
                    'title'   => __( 'Phone', 'ictu' ),
                    'default' => 'DT : 0208.3846254'
                ),
                'university_fax'     => array(
                    'id'      => 'university_fax',
                    'type'    => 'text',
                    'title'   => __( 'Fax', 'ictu' ),
                    'default' => 'Fax : 0208.3846237'
                ),
                'chief_editor_label' => array(
                    'id'      => 'chief_editor_label',
                    'type'    => 'text',
                    'title'   => __( 'Chief Editor Label', 'ictu' ),
                    'default' => 'Trưởng ban biên tập'
                ),
                'chief_editor_name'  => array(
                    'id'      => 'chief_editor_name',
                    'type'    => 'text',
                    'title'   => __( 'Name Of Chief Editor', 'ictu' ),
                    'default' => 'PGS.TS Phùng Trọng Nghĩa'
                ),
                'chief_editor_phone' => array(
                    'id'      => 'chief_editor_phone',
                    'type'    => 'text',
                    'title'   => __( 'Chief Editor Phone', 'ictu' ),
                    'default' => 'ĐT : 0208.3846254'
                ),
                'chief_editor_email' => array(
                    'id'      => 'chief_editor_email',
                    'type'    => 'text',
                    'title'   => __( 'Chief Editor Email', 'ictu' ),
                    'default' => 'Email : ptnghia@ictu.edu.vn'
                ),
//                'chief_editor_note'      => array(
//                    'id'    => 'chief_editor_note',
//                    'type'  => 'text',
//                    'title' => __( 'Chief Editor Note', 'ictu' ),
//                ),
//                'chief_editor_note_link' => array(
//                    'id'    => 'chief_editor_note_link',
//                    'type'  => 'text',
//                    'title' => __( 'Chief Editor Note - Link', 'ictu' ),
//                ),
                'organizations_logo' => array(
                    'id'    => 'organizations_logo',
                    'type'  => 'gallery',
                    'title' => __( 'Organizations Logo', 'ictu' )
                ),
                'copyright_text'     => array(
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
        $options['social']       = array(
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
        $options['typography']   = array(
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
        $options['backup']       = array(
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