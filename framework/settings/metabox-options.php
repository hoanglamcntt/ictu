<?php if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.
/*==========================================================================
METABOX BOX OPTIONS
===========================================================================*/
if (!function_exists('theme_metabox_options') && class_exists('OVIC_Metabox')) {
    function theme_metabox_options()
    {
        $sections = array();
        // -----------------------------------------
        // Page Side Meta box Options              -
        // -----------------------------------------
        $sections[] = array(
            'id'             => '_custom_page_side_options',
            'title'          => __('Page Options', 'ictu'),
            'post_type'      => 'page',
            'context'        => 'side',
            'priority'       => 'high',
            'page_templates' => 'default',
            'sections'       => array(
                array(
                    'name'   => 'page_option',
                    'fields' => array(
                        'sidebar_page_layout' => array(
                            'id'         => 'sidebar_page_layout',
                            'type'       => 'image_select',
                            'title'      => __('Single Page Sidebar Position', 'ictu'),
                            'desc'       => __('Select sidebar position on Page.', 'ictu'),
                            'options'    => array(
                                'left'  => get_theme_file_uri('assets/images/left-sidebar.png'),
                                'right' => get_theme_file_uri('assets/images/right-sidebar.png'),
                                'full'  => get_theme_file_uri('assets/images/no-sidebar.png'),
                            ),
                            'default'    => 'left',
                            'attributes' => array(
                                'data-depend-id' => 'sidebar_page_layout',
                            ),
                        ),
                        'page_sidebar'        => array(
                            'id'         => 'page_sidebar',
                            'type'       => 'select',
                            'title'      => __('Page Sidebar', 'ictu'),
                            'options'    => 'sidebars',
                            'dependency' => array('sidebar_page_layout', '!=', 'full'),
                        ),
                        'page_extra_class'    => array(
                            'id'    => 'page_extra_class',
                            'type'  => 'text',
                            'title' => __('Extra Class', 'ictu'),
                        ),
                    ),
                ),
            ),
        );
        // -----------------------------------------
        // Page banner                            -
        // -----------------------------------------
        $sections[] = array(
            'id'        => '_custom_page_banner',
            'title'     => __('Setting page banner', 'ictu'),
            'post_type' => 'page',
            'context'   => 'side',
            'priority'  => 'high',
            'sections'  => array(
                array(
                    'name'   => 'banner_option',
                    'fields' => array(
                        'enable_page_banner' => array(
                            'id'      => 'enable_page_banner',
                            'type'    => 'switcher',
                            'title'   => __('Enable Page Banner', 'ictu'),
                            'default' => false
                        ),
                        'page_banner'        => array(
                            'id'         => 'page_banner',
                            'type'       => 'image',
                            'title'      => __('Page banner', 'ictu'),
                            'desc'       => __('Setting banner for this Page', 'ictu'),
                            'dependency' => array('enable_page_banner', '==', '1'),
                        )
                    )
                )
            )
        );
        // -----------------------------------------
        // Page Meta box Options                   -
        // -----------------------------------------
        /*$sections[] = array(
            'id'        => '_custom_metabox_theme_options',
            'title'     => esc_html__('Custom Theme Options', 'ictu'),
            'post_type' => 'page',
            'context'   => 'normal',
            'priority'  => 'high',
            'sections'  => array(
                'options' => array(
                    'name'   => 'options',
                    'title'  => esc_html__('Theme Settings', 'ictu'),
                    'icon'   => 'fa fa-wordpress',
                    'fields' => array(
                        'enable_metabox_options'     => array(
                            'id'    => 'enable_metabox_options',
                            'type'  => 'switcher',
                            'title' => esc_html__('Enable Metabox Options', 'ictu'),
                            'desc'  => esc_html__('If this option enable then this page will get setting in here, else this page will get setting in Theme Options', 'ictu'),
                        ),
                        'metabox_main_container'     => array(
                            'id'      => 'metabox_main_container',
                            'type'    => 'slider',
                            'title'   => esc_html__('Main Container', 'ictu'),
                            'min'     => 1140,
                            'max'     => 1920,
                            'step'    => 10,
                            'unit'    => esc_html__('px', 'ictu'),
                            'default' => 1410,
                        ),
                        'metabox_main_color'         => array(
                            'id'      => 'metabox_main_color',
                            'type'    => 'color',
                            'rgba'    => true,
                            'default' => '#111111',
                            'title'   => esc_html__('Main Color', 'ictu'),
                        ),
                        'metabox_body_color'         => array(
                            'id'      => 'metabox_body_color',
                            'type'    => 'color',
                            'rgba'    => true,
                            'default' => '#7377ab',
                            'title'   => esc_html__('Body Color', 'ictu'),
                        ),
                        'metabox_page_banner'        => array(
                            'id'    => 'metabox_page_banner',
                            'type'  => 'image',
                            'title' => esc_html__('Page banner', 'ictu'),
                            'desc'  => esc_html__('Setting banner for this Page', 'ictu'),
                        ),
                        'body_typography'            => array(
                            'id'             => 'body_typography',
                            'type'           => 'typography',
                            'title'          => esc_html__('Typography of Body', 'ictu'),
                            'font_family'    => true,
                            'font_weight'    => true,
                            'font_style'     => true,
                            'subset'         => true,
                            'text_align'     => true,
                            'text_transform' => true,
                            'font_size'      => true,
                            'line_height'    => true,
                            'letter_spacing' => true,
                            'extra_styles'   => true,
                            'color'          => true,
                            'output'         => 'body',
                        ),
                        'metabox_special_typography' => array(
                            'id'             => 'metabox_special_typography',
                            'type'           => 'typography',
                            'title'          => esc_html__('Typography of Special text', 'ictu'),
                            'font_family'    => true,
                            'font_weight'    => true,
                            'font_style'     => false,
                            'subset'         => false,
                            'text_align'     => false,
                            'text_transform' => false,
                            'font_size'      => false,
                            'line_height'    => false,
                            'letter_spacing' => false,
                            'extra_styles'   => true,
                            'color'          => false,
                            'output'         => '',
                        ),
                    ),
                ),
                'footer'  => array(
                    'name'   => 'footer',
                    'title'  => esc_html__('Footer Settings', 'ictu'),
                    'icon'   => 'fa fa-folder-open-o',
                    'fields' => array(
                        array(
                            'id'      => 'metabox_footer_template',
                            'type'    => 'select_preview',
                            'default' => 'footer-08',
                            'options' => theme_footer_preview(),
                        ),
                    ),
                ),
                'mobile'  => array(
                    'name'   => 'mobile',
                    'title'  => esc_html__('Mobile Settings', 'ictu'),
                    'icon'   => 'fa fa-folder-open-o',
                    'fields' => array(
                        'metabox_mobile_enable' => array(
                            'id'    => 'metabox_mobile_enable',
                            'type'  => 'switcher',
                            'title' => esc_html__('Enable version mobile', 'ictu'),
                        ),
                        'metabox_logo_mobile'   => array(
                            'id'    => 'metabox_logo_mobile',
                            'type'  => 'image',
                            'title' => esc_html__('Logo', 'ictu'),
                            'desc'  => esc_html__('Setting Logo For Site', 'ictu'),
                        ),
                        'metabox_mobile_header' => array(
                            'id'      => 'metabox_mobile_header',
                            'type'    => 'select_preview',
                            'default' => 'style-01',
                            'title'   => esc_html__('Header Mobile', 'ictu'),
                            'options' => theme_file_options('/templates/mobile/', 'mobile'),
                        ),
                        'metabox_mobile_footer' => array(
                            'id'      => 'metabox_mobile_footer',
                            'type'    => 'select_preview',
                            'default' => 'none',
                            'title'   => esc_html__('Footer Mobile', 'ictu'),
                            'options' => theme_footer_preview(),
                        ),
                    ),
                ),
            ),
        );*/
        // -----------------------------------------
        // Post Meta box Options                   -
        // -----------------------------------------
        $sections[] = array(
            'id'        => '_custom_metabox_post_options',
            'title'     => __('Post Options', 'ictu'),
            'post_type' => 'post',
            'context'   => 'side',
            'priority'  => 'high',
            'sections'  => array(
                'post_formats' => array(
                    'name'   => 'post_formats',
                    'icon'   => 'fa fa-picture-o',
                    'fields' => array(
                        'type'             => array(
                            'id'      => 'type',
                            'type'    => 'radio',
                            'title'   => __('Post Type', 'ictu'),
                            'options' => array(
                                'standard' => __('Article', 'ictu'),
                                'event'    => __('Event', 'ictu'),
                                'video'    => __('Video', 'ictu'),
                                // 'quote'    => esc_html__('Quote', 'ictu'),
                                'gallery'  => __('Gallery', 'ictu'),
                                'document' => __('Document', 'ictu'),
                                'calendar' => __('Calendar', 'ictu')
                                // 'audio'    => esc_html__('Audio', 'ictu'),
                            ),
                            'default' => 'standard'
                        ),
                        'quote'            => array(
                            'id'         => 'quote',
                            'type'       => 'text',
                            'title'      => __('Quote Text', 'ictu'),
                            'dependency' => array('type', '==', 'quote',),
                            'attributes' => array('style' => 'width:100%',),
                        ),
                        'gallery'          => array(
                            'id'         => 'gallery',
                            'type'       => 'gallery',
                            'title'      => __('Gallery source', 'ictu'),
                            'dependency' => array('type', '==', 'gallery'),
                        ),
                        'video'            => array(
                            'id'         => 'video',
                            'type'       => 'text',
                            'title'      => __('Video source', 'ictu'),
                            'dependency' => array('type', '==', 'video'),
                            'attributes' => array('style' => 'width:100%')
                        ),
                        'audio'            => array(
                            'id'         => 'audio',
                            'type'       => 'textarea',
                            'title'      => __('Audio source', 'ictu'),
                            'dependency' => array('type', '==', 'audio'),
                            'attributes' => array('style' => 'width:100%'),
                            'desc'       => __('Copy url source audio or iframe', 'ictu'),
                        ),
                        'place'            => array(
                            'id'         => 'place',
                            'type'       => 'textarea',
                            'title'      => __('Place', 'ictu'),
                            'dependency' => array('type', '==', 'event'),
                            //'desc'       => __('Location where the event is held', 'ictu'),
                            'attributes' => array('style' => 'width:100%'),
                        ),
                        'time'             => array(
                            'id'         => 'time',
                            'type'       => 'text',
                            'title'      => __('Time', 'ictu'),
                            'dependency' => array('type', '==', 'event'),
                            //'desc'       => __('Location where the event is held', 'ictu'),
                            'attributes' => array('style' => 'width:100%')
                        ),
                        'participants'     => array(
                            'id'         => 'participants',
                            'type'       => 'textarea',
                            'title'      => __('Participants', 'ictu'),
                            'dependency' => array('type', '==', 'event'),
                            //'desc'       => __('Location where the event is held', 'ictu'),
                            'attributes' => array('style' => 'width:100%')
                        ),
                        'post_banner'      => array(
                            'id'         => 'post_banner',
                            'type'       => 'image',
                            'title'      => __('Post banner', 'ictu'),
                            'desc'       => __('Setting banner for this post', 'ictu'),
                            'dependency' => array('type', 'any', 'event,standard'),
                        ),
                        'doc_code'         => array(
                            'id'          => 'doc_code',
                            'type'        => 'text',
                            'placeholder' => '123/QĐ-ĐHCNTT&TT',
                            'title'       => __('Số văn bản', 'ictu'),
                            'dependency'  => array('type', '==', 'document',),
                            'attributes'  => array('style' => 'width:100%',),
                        ),
                        'doc_type'         => array(
                            'id'         => 'doc_type',
                            'type'       => 'select',
                            'title'      => __('Loại văn bản', 'ictu'),
                            'dependency' => array('type', '==', 'document',),
                            'options'    => ictu_document_type(),
                            'default'    => 'vb1',
                            'attributes' => array('style' => 'width:100%',),
                        ),
                        'doc_date_sight'   => array(
                            'id'         => 'doc_date_sight',
                            'type'       => 'text',
                            'title'      => __('Ngày Ký', 'ictu'),
                            'dependency' => array('type', '==', 'document',),
                            'attributes' => array('style' => 'width:100%',),
                        ),
                        'doc_person_sight' => array(
                            'id'         => 'doc_person_sight',
                            'type'       => 'text',
                            'title'      => __('Người Ký', 'ictu'),
                            'dependency' => array('type', '==', 'document',),
                            'attributes' => array('style' => 'width:100%',),
                        ),
                        'doc_person_title' => array(
                            'id'         => 'doc_person_title',
                            'type'       => 'text',
                            'title'      => __('Chức vụ', 'ictu'),
                            'dependency' => array('type', '==', 'document',),
                            'attributes' => array('style' => 'width:100%',),
                        ),
                        'google_doc'       => array(
                            'id'         => 'google_doc',
                            'type'       => 'text',
                            'title'      => __('Google Docs', 'ictu'),
                            'dependency' => array('type', 'any', 'document,calendar',),
                            'attributes' => array('style' => 'width:100%',),
                        )
                    )
                ),
                'post_feature' => array(
                    'name'   => 'post_feature',
                    'icon'   => 'fa fa-picture-o',
                    'fields' => array(
                        'is_feature' => array(
                            'id'      => 'is_feature',
                            'type'    => 'checkbox',
                            'title'   => __('Advanced', 'ictu'),
                            'label'   => __('Featured Post', 'ictu'),
                            'default' => false // or false
                        ),
                    )
                )
            )
        );
        // $banner = get_theme_option( 'shop_banner', 0);
        // Product Meta box Options
        /*        $sections[] = array(
                    'id'        => '_custom_metabox_post_options',
                    'title'     => esc_html__( 'Custom contents', 'ictu' ),
                    'post_type' => 'product',
                    'context'   => 'normal',
                    'priority'  => 'low',
                    'sections'  => array(
                        'custom_contents' => array(
                            'name'   => 'custom_contents',
                            'icon'   => 'fa fa-picture-o',
                            'fields' => array(
                                'shop_banner'   => array(
                                    'id'    => 'shop_banner',
                                    'type'  => 'select',
                                    'options'     => 'page',
                                    'query_args'  => array(
                                        'posts_per_page' => - 1,
                                    ),
                                    'default'   => $banner,
                                    'chosen'      => true,
                                    'ajax'        => true,
                                    'placeholder' => esc_html__( 'Select Banner', 'ictu' ),
                                    'title'       => esc_html__( 'Shop Banner', 'ictu' ),
                                    'desc'        => esc_html__( 'Get shop banner from page builder.', 'ictu' ),
                                ),
                                'custom_content'    => array(
                                    'id'      => 'custom_content',
                                    'type'    => 'group',
                                    'title'           => esc_html__( 'Custom Contents', 'ictu' ),
                                    'button_title'    => esc_html__( 'Add New', 'ictu' ),
                                    'fields'          => array(
                                        array(
                                            'id'      => 'label',
                                            'type'    => 'text',
                                            'title'   => esc_html__( 'Label', 'ictu' ),
                                        ),
                                        array(
                                            'id'      => 'content',
                                            'type'    => 'textarea',
                                            'title'   => esc_html__( 'Content', 'ictu' ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                );*/

//        $sections[] = array(
//            'id'        => '_custom_metabox_extra_specifications',
//            'title'     => __('Product Specifications', 'ictu'),
//            'post_type' => 'product',
//            'context'   => 'normal',
//            'priority'  => 'low',
//            'sections'  => array(
//                'custom_extra_specifications' => array(
//                    'name'   => 'custom_extra_specifications',
//                    'icon'   => 'fa fa-picture-o',
//                    'fields' => array(
//                        'link_catalogue' => array(
//                            'id'    => 'link_catalogue',
//                            'type'  => 'text',
//                            'title' => __('Link Catalogue', 'ictu')
//                        ),
//                        'specifications' => array(
//                            'id'    => 'specifications',
//                            'type'  => 'wp_editor',
//                            'title' => __('Product Specifications', 'ictu')
//                        )
//                    )
//                )
//            )
//        );

        OVIC_Metabox::instance(apply_filters('theme_framework_metabox_options', $sections));

        /**
         * OVIC_Taxonomy
         */

        // $promotion_products = get_theme_option( 'promotion_products', 0);
        // $options[] = array(
        //     'id'       => '_custom_taxonomy_options',
        //     'taxonomy' => 'product_cat', // category, post_tag or your custom taxonomy name
        //     'fields'   => array(
        //         array(
        //             'id'    => 'shop_banner',
        //             'type'  => 'select',
        //             'options'     => 'page',
        //             'query_args'  => array(
        //                 'posts_per_page' => - 1,
        //             ),
        //             'default'   => $banner,
        //             'chosen'      => true,
        //             'ajax'        => true,
        //             'placeholder' => esc_html__( 'Select Banner', 'ictu' ),
        //             'title'       => esc_html__( 'Shop Banner', 'ictu' ),
        //             'desc'        => esc_html__( 'Get shop banner from page builder.', 'ictu' ),
        //         ),
        //         array(
        //             'id'    => 'promotion_products',
        //             'type'  => 'select',
        //             'options'     => 'page',
        //             'query_args'  => array(
        //                 'posts_per_page' => - 1,
        //             ),
        //             'default'   => $promotion_products,
        //             'chosen'      => true,
        //             'ajax'        => true,
        //             'placeholder' => esc_html__( 'Select promotion products page', 'ictu' ),
        //             'title'       => esc_html__( 'Promotion products', 'ictu' ),
        //         ),


        //     ),
        // );

        // OVIC_Taxonomy::instance( $options );


    }

    add_action('init', 'theme_metabox_options');
}

if (!function_exists('theme_save_meta_box')) {
    function theme_save_meta_box($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if ($parent_id = wp_is_post_revision($post_id)) {
            $post_id = $parent_id;
        }
        if (isset($_POST['post_type']) && $_POST['post_type'] === 'post' && isset($_POST['_custom_metabox_post_options']) && is_array($_POST['_custom_metabox_post_options'])) {
            if (array_key_exists('is_feature', $_POST['_custom_metabox_post_options'])) {
                update_post_meta($post_id, '_is_feature', $_POST['_custom_metabox_post_options']['is_feature']);
            }
            if (array_key_exists('doc_type', $_POST['_custom_metabox_post_options'])) {
                update_post_meta($post_id, '_ictu_doc_type', $_POST['_custom_metabox_post_options']['doc_type']);
            }
            if (array_key_exists('type', $_POST['_custom_metabox_post_options'])) {
                update_post_meta($post_id, '_ictu_post_type', $_POST['_custom_metabox_post_options']['type']);
            }
        }
    }
}
add_action('save_post', 'theme_save_meta_box');