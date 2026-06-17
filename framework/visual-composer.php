<?php defined('ABSPATH') || exit;// Cannot access pages directly.

if (!class_exists('Avocado_Visual_Composer')) {
    class Avocado_Visual_Composer {
        public function __construct()
        {
            $this->autocomplete();
            // add_filter( 'ovic_include_templates_shortcode', array( $this, 'include_templates_shortcode' ), 10, 2 );

            // add_filter( 'ovic_vc_add_param_visual_composer', array( $this, 'add_param_visual_composer' ) );

            add_action('vc_before_init', array($this, 'map_shortcode'));
            add_action('vc_after_init', array($this, 'include_shortcode'));

            add_filter('vc_grid_item_shortcodes', array($this, 'module_add_grid_shortcodes'));
            add_filter('vc_iconpicker-type-oviccustomfonts', array($this, 'iconpicker_type_oviccustomfonts'));
            add_action('vc_after_init', array($this, 'add_button_style'));
            add_filter('vc_google_fonts_get_fonts_filter', array($this, 'add_vc_fonts'));
        }

        private function get_templates($template_name)
        {
            $directory_shortcode = '';
            $path_templates      = 'vc_templates/' . $template_name;
            if (is_file(get_template_directory() . "/{$path_templates}/{$template_name}.php")) {
                $directory_shortcode = get_template_directory() . "/{$path_templates}";
            }
            if ($directory_shortcode != '') {
                include_once "{$directory_shortcode}/{$template_name}.php";
            }
        }

        public function map_shortcode()
        {
            $param_maps = $this->add_param_visual_composer(array());
            if (!empty($param_maps)) {
                foreach ($param_maps as $map) {
                    if (function_exists('vc_map')) {
                        vc_map($map);
                    }
                }
            }
        }

        public function include_shortcode()
        {
            $param_maps = $this->add_param_visual_composer(array());
            if (!empty($param_maps)) {
                foreach ($param_maps as $shortcode) {
                    $this->get_templates($shortcode['base']);
                }
            }
        }

        public function include_templates_shortcode($default, $template_name)
        {
            return 'vc_templates/' . $template_name;
        }

        public function add_button_style()
        {
            /* param button */
            $param_separator = WPBMap::getParam('vc_separator', 'el_width');

            $param_separator['value'][esc_html__('Full Screen', 'ictu')] = 'full';
            /* param tabs*/
            $param_tabs = WPBMap::getParam('vc_tta_section', 'i_type');

            $param_tabs['value'][esc_html__('Custom Fonts', 'ictu')] = 'oviccustomfonts';
            $param_tabs['value'][esc_html__('Image', 'ictu')]        = 'image';
            /* update params */
            vc_update_shortcode_param('vc_separator', $param_separator);
            vc_update_shortcode_param('vc_tta_section', $param_tabs);
        }

        public function post_autocomplete_suggester($query)
        {
            global $wpdb;
            $post_id      = (int)$query;
            $post_results = $wpdb->get_results(
                $wpdb->prepare("SELECT a.ID AS id, a.post_title AS title FROM {$wpdb->posts} AS a WHERE a.post_type = 'post' AND a.post_status != 'trash' AND ( a.ID = '%d' OR a.post_title LIKE '%%%s%%' )", $post_id > 0 ? $post_id : -1, stripslashes($query), stripslashes($query)
                ), ARRAY_A
            );
            $results      = array();
            if (is_array($post_results) && !empty($post_results)) {
                foreach ($post_results as $value) {
                    $data          = array();
                    $data['value'] = $value['id'];
                    $data['label'] = $value['title'];
                    $results[]     = $data;
                }
            }

            return $results;
        }

        public function post_autocomplete_suggester_render($query)
        {
            $query = trim($query['value']);
            // get value from requested
            if (!empty($query)) {
                $post_object = get_post((int)$query);
                if (is_object($post_object)) {
                    $post_title    = $post_object->post_title;
                    $post_id       = $post_object->ID;
                    $data          = array();
                    $data['value'] = $post_id;
                    $data['label'] = $post_title;

                    return !empty($data) ? $data : false;
                }

                return false;
            }

            return false;
        }

        public function iconpicker_type_oviccustomfonts()
        {
            $icons['Custom Fonts'] = array();

            return $icons;
        }

        public function get_post_type()
        {
            $postTypes         = get_post_types(array());
            $postTypesList     = array();
            $excludedPostTypes = array(
                'revision',
                'nav_menu_item',
                'vc_grid_item',
            );
            if (is_array($postTypes) && !empty($postTypes)) {
                foreach ($postTypes as $postType) {
                    if (!in_array($postType, $excludedPostTypes, true)) {
                        $label           = ucfirst($postType);
                        $postTypesList[] = array(
                            $postType,
                            $label,
                        );
                    }
                }
            }

            return $postTypesList;
        }

        public function get_post_category()
        {
            $categories_array = array(
                esc_html__('All', 'ictu') => '',
            );
            $categories       = get_categories();
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $categories_array[$category->name] = $category->slug;
                }
            }

            return $categories_array;
        }

        public function params_shortcode($params, $add_grid = false)
        {
            // Icon Field
            $icon_field = array(
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__('Icon library', 'ictu'),
                    'value'       => array(
                        esc_html__('Font Awesome', 'ictu') => 'fontawesome',
                        esc_html__('Open Iconic', 'ictu')  => 'openiconic',
                        esc_html__('Typicons', 'ictu')     => 'typicons',
                        esc_html__('Entypo', 'ictu')       => 'entypo',
                        esc_html__('Linecons', 'ictu')     => 'linecons',
                        esc_html__('Mono Social', 'ictu')  => 'monosocial',
                        esc_html__('Material', 'ictu')     => 'material',
                        esc_html__('Custom Fonts', 'ictu') => 'oviccustomfonts',
                        esc_html__('Image', 'ictu')        => 'image',
                    ),
                    'admin_label' => true,
                    'param_name'  => 'type',
                    'description' => esc_html__('Select icon library.', 'ictu'),
                ),
                array(
                    'type'       => 'attach_image',
                    'heading'    => esc_html__('Image', 'ictu'),
                    'param_name' => 'image_icon',
                    'dependency' => array(
                        'element' => 'type',
                        'value'   => 'image',
                    ),
                ),
                array(
                    'param_name'  => 'icon_oviccustomfonts',
                    'heading'     => esc_html__('Icon', 'ictu'),
                    'description' => esc_html__('Select icon from library.', 'ictu'),
                    'type'        => 'iconpicker',
                    'settings'    => array(
                        'emptyIcon' => false,
                        'type'      => 'oviccustomfonts',
                    ),
                    'dependency'  => array(
                        'element' => 'type',
                        'value'   => 'oviccustomfonts',
                    ),
                ),
                array(
                    'type'        => 'iconpicker',
                    'heading'     => esc_html__('Icon', 'ictu'),
                    'param_name'  => 'icon_fontawesome',
                    'value'       => 'fa fa-adjust',
                    'settings'    => array(
                        'emptyIcon'    => false,
                        'iconsPerPage' => 100,
                    ),
                    'dependency'  => array(
                        'element' => 'type',
                        'value'   => 'fontawesome',
                    ),
                    'description' => esc_html__('Select icon from library.', 'ictu'),
                ),
                array(
                    'type'        => 'iconpicker',
                    'heading'     => esc_html__('Icon', 'ictu'),
                    'param_name'  => 'icon_openiconic',
                    'value'       => 'vc-oi vc-oi-dial',
                    'settings'    => array(
                        'emptyIcon'    => false,
                        'type'         => 'openiconic',
                        'iconsPerPage' => 100,
                    ),
                    'dependency'  => array(
                        'element' => 'type',
                        'value'   => 'openiconic',
                    ),
                    'description' => esc_html__('Select icon from library.', 'ictu'),
                ),
                array(
                    'type'        => 'iconpicker',
                    'heading'     => esc_html__('Icon', 'ictu'),
                    'param_name'  => 'icon_typicons',
                    'value'       => 'typcn typcn-adjust-brightness',
                    'settings'    => array(
                        'emptyIcon'    => false,
                        'type'         => 'typicons',
                        'iconsPerPage' => 100,
                    ),
                    'dependency'  => array(
                        'element' => 'type',
                        'value'   => 'typicons',
                    ),
                    'description' => esc_html__('Select icon from library.', 'ictu'),
                ),
                array(
                    'type'       => 'iconpicker',
                    'heading'    => esc_html__('Icon', 'ictu'),
                    'param_name' => 'icon_entypo',
                    'value'      => 'entypo-icon entypo-icon-note',
                    'settings'   => array(
                        'emptyIcon'    => false,
                        'type'         => 'entypo',
                        'iconsPerPage' => 100,
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value'   => 'entypo',
                    ),
                ),
                array(
                    'type'        => 'iconpicker',
                    'heading'     => esc_html__('Icon', 'ictu'),
                    'param_name'  => 'icon_linecons',
                    'value'       => 'vc_li vc_li-heart',
                    'settings'    => array(
                        'emptyIcon'    => false,
                        'type'         => 'linecons',
                        'iconsPerPage' => 100,
                    ),
                    'dependency'  => array(
                        'element' => 'type',
                        'value'   => 'linecons',
                    ),
                    'description' => esc_html__('Select icon from library.', 'ictu'),
                ),
                array(
                    'type'        => 'iconpicker',
                    'heading'     => esc_html__('Icon', 'ictu'),
                    'param_name'  => 'icon_monosocial',
                    'value'       => 'vc-mono vc-mono-fivehundredpx',
                    'settings'    => array(
                        'emptyIcon'    => false,
                        'type'         => 'monosocial',
                        'iconsPerPage' => 100,
                    ),
                    'dependency'  => array(
                        'element' => 'type',
                        'value'   => 'monosocial',
                    ),
                    'description' => esc_html__('Select icon from library.', 'ictu'),
                ),
                array(
                    'type'        => 'iconpicker',
                    'heading'     => esc_html__('Icon', 'ictu'),
                    'param_name'  => 'icon_material',
                    'value'       => 'vc-material vc-material-cake',
                    'settings'    => array(
                        'emptyIcon'    => false,
                        'type'         => 'material',
                        'iconsPerPage' => 100,
                    ),
                    'dependency'  => array(
                        'element' => 'type',
                        'value'   => 'material',
                    ),
                    'description' => esc_html__('Select icon from library.', 'ictu'),
                ),
            );
            if (!$add_grid) {
                vc_add_params(
                    'vc_single_image',
                    array(
                        array(
                            'param_name' => 'image_effect',
                            'heading'    => esc_html__('Effect', 'ictu'),
                            'group'      => esc_html__('Image Effect', 'ictu'),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__('None', 'ictu')               => 'none',
                                esc_html__('Normal Effect', 'ictu')      => 'effect normal-effect',
                                esc_html__('Normal Effect Dark', 'ictu') => 'effect normal-effect dark-bg',
                                esc_html__('Faded In', 'ictu')           => 'effect faded-in',
                                esc_html__('Bounce In', 'ictu')          => 'effect bounce-in',
                                esc_html__('Gray filter', 'ictu')        => 'effect gray-filter',
                                esc_html__('Background Zoom', 'ictu')    => 'effect background-zoom',
                                esc_html__('Background Slide', 'ictu')   => 'effect background-slide',
                                esc_html__('Rotate Left In', 'ictu')     => 'effect rotate-in rotate-left',
                                esc_html__('Rotate Right In', 'ictu')    => 'effect rotate-in rotate-right',
                                esc_html__('Plus Zoom', 'ictu')          => 'effect plus-zoom',
                                esc_html__('Border Zoom', 'ictu')        => 'effect border-zoom',
                                esc_html__('Border Scale 1', 'ictu')     => 'effect border-scale',
                                esc_html__('Border Scale 2', 'ictu')     => 'effect border-scale s2',
                                esc_html__('Border Plus 1', 'ictu')      => 'effect border-plus',
                                esc_html__('Border Plus 2', 'ictu')      => 'effect border-plus s2',
                                esc_html__('Overlay Plus', 'ictu')       => 'effect overlay-plus',
                                esc_html__('Overlay Cross', 'ictu')      => 'effect overlay-cross',
                                esc_html__('Overlay Horizontal', 'ictu') => 'effect overlay-horizontal',
                                esc_html__('Overlay Vertical', 'ictu')   => 'effect overlay-vertical',
                                esc_html__('Flashlight', 'ictu')         => 'effect flashlight',
                            ),
                            'std'        => 'none',
                        ),
                    )
                );
                /* Custom tab shortcode */
                vc_remove_param('vc_tta_section', 'el_class');
                vc_add_params(
                    'vc_tta_section',
                    array(
                        array(
                            'param_name'  => 'i_icon_oviccustomfonts',
                            'heading'     => esc_html__('Icon', 'ictu'),
                            'description' => esc_html__('Select icon from library.', 'ictu'),
                            'type'        => 'iconpicker',
                            'settings'    => array(
                                'emptyIcon' => false,
                                'type'      => 'oviccustomfonts',
                            ),
                            'dependency'  => array(
                                'element' => 'i_type',
                                'value'   => 'oviccustomfonts',
                            ),
                        ),
                        array(
                            'type'       => 'attach_image',
                            'heading'    => esc_html__('Image', 'ictu'),
                            'param_name' => 'image_icon',
                            'dependency' => array(
                                'element' => 'i_type',
                                'value'   => 'image',
                            ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Extra class name', 'ictu'),
                            'param_name'  => 'el_class',
                            'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ictu'),
                        ),
                    )
                );
            }

            $params['tuongnam_megamenu_container'] = array(
                'base'                    => 'tuongnam_megamenu_container',
                'name'                    => esc_html__('Intn: Megamenu Container', 'ictu'),
                'icon'                    => get_theme_file_uri('assets/images/icon/mega-menu.svg'),
                'category'                => 'Intn Shortcode',
                'as_parent'               => array('only' => 'tuongnam_megamenu_items'),
                'content_element'         => true,
                'show_settings_on_create' => true,
                'js_view'                 => 'VcColumnView',
                'params'                  => array(
                    array(
                        "type"        => 'textfield',
                        "heading"     => 'Extra class name',
                        "param_name"  => 'el_class',
                        "description" => 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'
                    )
                )
            );

            $params['tuongnam_megamenu_items'] = array(
                'base'     => 'tuongnam_megamenu_items',
                'name'     => esc_html__('Intn: Megamenu items', 'ictu'),
                'icon'     => get_theme_file_uri('assets/images/icon/menu.svg'),
                'category' => 'Intn Shortcode',
                'params'   => array(
                    array(
                        "type"        => 'textfield',
                        "heading"     => 'Title',
                        "param_name"  => 'title',
                        'admin_label' => true,
                        "description" => 'The title of the menu'
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__('Position', 'ictu'),
                        'value'       => array(
                            esc_html__('Col 1', 'ictu') => 'col_1',
                            esc_html__('Col 2', 'ictu') => 'col_2',
                            esc_html__('Col 3', 'ictu') => 'col_3'
                        ),
                        'std'         => 'col_1',
                        'param_name'  => 'position',
                        'description' => esc_html__('Position of the menu', 'ictu'),
                    ),
                    array(
                        'type'        => 'autocomplete',
                        'heading'     => esc_html__('Items', 'ictu'),
                        'param_name'  => 'ids',
                        'settings'    => array(
                            'multiple'      => true,
                            'sortable'      => true,
                            'unique_values' => true,
                            // In UI show results except selected. NB! You should manually check values in backend
                        ),
                        // 'group'       => esc_html__( 'Product Options', 'ictu' ),
                        'description' => esc_html__('Enter List of Products', 'ictu'),
                        // 'dependency'  => array(
                        //     'element' => 'target',
                        //     'value'   => array( 'products' ),
                        // ),
                    ),
                    array(
                        "type"        => 'textfield',
                        "heading"     => 'Extra class name',
                        "param_name"  => 'el_class',
                        "description" => 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'
                    )
                )
            );

            return $params;
        }

        public function module_add_grid_shortcodes($shortcodes)
        {
            return $this->params_shortcode($shortcodes, true);
        }

        public function add_param_visual_composer($param)
        {
            return $this->params_shortcode($param);
        }

        public function get_preview_options($name, $default = 'style-01', $param_name = 'style')
        {
            $path            = trailingslashit(get_template_directory()) . "vc_templates/{$name}/layout/";
            $preview_options = array();
            if (is_dir($path)) {
                $files = scandir($path);
                if ($files && is_array($files)) {
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            $fileInfo = pathinfo($file);
                            if ($fileInfo['extension'] == 'jpg') {
                                $fileName = str_replace(
                                    array('_', '-'),
                                    array(' ', ' '),
                                    $fileInfo['filename']
                                );
                                /* PRINT OPTION */
                                $preview_options[$fileInfo['filename']] = array(
                                    'title'   => ucwords($fileName),
                                    'preview' => get_theme_file_uri("vc_templates/{$name}/layout/{$fileInfo['filename']}.jpg"),
                                );
                            }
                        }
                    }
                }
            }

            return array(
                'type'        => 'select_preview',
                'heading'     => esc_html__('Select style', 'ictu'),
                'value'       => $preview_options,
                'default'     => $default,
                'admin_label' => true,
                'param_name'  => $param_name,
            );
        }

        public function get_params_products()
        {
            $order_by_values  = array(
                '',
                esc_html__('Date', 'ictu')               => 'date',
                esc_html__('ID', 'ictu')                 => 'ID',
                esc_html__('Author', 'ictu')             => 'author',
                esc_html__('Title', 'ictu')              => 'title',
                esc_html__('Modified', 'ictu')           => 'modified',
                esc_html__('Random', 'ictu')             => 'rand',
                esc_html__('Comment count', 'ictu')      => 'comment_count',
                esc_html__('Menu order', 'ictu')         => 'menu_order',
                esc_html__('Price: low to high', 'ictu') => 'price',
                esc_html__('Price: high to low', 'ictu') => 'price-desc',
                esc_html__('Average Rating', 'ictu')     => 'rating',
                esc_html__('Popularity', 'ictu')         => 'popularity',
                esc_html__('Post In', 'ictu')            => 'post__in',
            );
            $order_way_values = array(
                '',
                esc_html__('Descending', 'ictu') => 'DESC',
                esc_html__('Ascending', 'ictu')  => 'ASC',
            );
            $attributes_tax   = wc_get_attribute_taxonomies();
            $attributes       = array('');
            foreach ($attributes_tax as $attribute) {
                $attributes[$attribute->attribute_label] = $attribute->attribute_name;
            }
            // CUSTOM PRODUCT SIZE
            $product_size_width_list = array();
            $width                   = 300;
            $height                  = 300;
            if (function_exists('wc_get_image_size')) {
                $size   = wc_get_image_size('shop_catalog');
                $width  = isset($size['width']) ? $size['width'] : $width;
                $height = isset($size['height']) ? $size['height'] : $height;
            }
            for ($i = 100; $i < $width; $i = $i + 10) {
                array_push($product_size_width_list, $i);
            }
            $product_size_list                         = array();
            $product_size_list[$width . 'x' . $height] = $width . 'x' . $height;
            foreach ($product_size_width_list as $k => $w) {
                $w      = intval($w);
                $width  = intval($width);
                $height = intval($height);
                if (isset($width) && $width > 0) {
                    $h = round($height * $w / $width);
                } else {
                    $h = $w;
                }
                $product_size_list[$w . 'x' . $h] = $w . 'x' . $h;
            }
            $product_size_list['Custom'] = 'custom';
            // All this move to product
            $param_maps['ovic_products'] = array(
                'base'        => 'ovic_products',
                'icon'        => get_theme_file_uri('assets/images/icon/shopping-bag.svg'),
                'name'        => esc_html__('Ovic: Products', 'ictu'),
                'category'    => 'Ovic Shortcode',
                'description' => esc_html__('Display Products', 'ictu'),
                'params'      => array(
                    $this->get_preview_options('ovic_products'),
                    array(
                        'type'        => 'textarea',
                        'heading'     => esc_html__('Title', 'ictu'),
                        'param_name'  => 'title',
                        'description' => esc_html__('Use {text} for highlight text.', 'ictu'),
                        'admin_label' => true,
                    ),
                    array(
                        'type'       => 'attach_image',
                        'heading'    => esc_html__('Background Image', 'ictu'),
                        'param_name' => 'image_background',
                        'dependency' => array(
                            'element' => 'style',
                            'value'   => array('layout-01'),
                        ),
                    ),
                    array(
                        'type'        => 'vc_link',
                        'heading'     => esc_html__('Link Button', 'ictu'),
                        'param_name'  => 'button_link_show',
                        'description' => esc_html__('The text of link', 'ictu'),
                        'dependency'  => array(
                            'element' => 'style',
                            'value'   => array('layout-01'),
                        ),
                    ),
                    array(
                        'type'        => 'vc_link',
                        'heading'     => esc_html__('Link Show All', 'ictu'),
                        'param_name'  => 'button_link',
                        'description' => esc_html__('The text of link', 'ictu'),
                        'dependency'  => array(
                            'element' => 'product_style',
                            'value'   => array('style-07'),
                        ),
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__('Product list style', 'ictu'),
                        'param_name'  => 'productsliststyle',
                        'value'       => array(
                            esc_html__('None', 'ictu')           => 'none',
                            esc_html__('Grid Bootstrap', 'ictu') => 'grid',
                            esc_html__('Owl Carousel', 'ictu')   => 'owl',
                        ),
                        'admin_label' => true,
                        'std'         => 'grid',
                    ),
                    array(
                        'type'        => 'select_preview',
                        'heading'     => esc_html__('Item Style', 'ictu'),
                        'value'       => theme_product_options('Shortcode'),
                        'default'     => 'style-01',
                        'admin_label' => true,
                        'param_name'  => 'product_style',
                        'description' => esc_html__('Select a style for product item', 'ictu'),
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__('Image size', 'ictu'),
                        'param_name'  => 'product_image_size',
                        'value'       => $product_size_list,
                        'description' => esc_html__('Select a size for product', 'ictu'),
                    ),
                    array(
                        'type'       => 'number',
                        'heading'    => esc_html__('Width', 'ictu'),
                        'param_name' => 'product_custom_thumb_width',
                        'value'      => $width,
                        'suffix'     => esc_html__('px', 'ictu'),
                        'dependency' => array(
                            'element' => 'product_image_size',
                            'value'   => array('custom'),
                        ),
                    ),
                    array(
                        'type'       => 'number',
                        'heading'    => esc_html__('Height', 'ictu'),
                        'param_name' => 'product_custom_thumb_height',
                        'value'      => $height,
                        'suffix'     => esc_html__('px', 'ictu'),
                        'dependency' => array(
                            'element' => 'product_image_size',
                            'value'   => array('custom'),
                        ),
                    ),
                    vc_map_add_css_animation(),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__('Pagination', 'ictu'),
                        'param_name' => 'pagination',
                        'value'      => array(
                            esc_html__('None', 'ictu')               => 'none',
                            esc_html__('View All', 'ictu')           => 'view_all',
                            esc_html__('Load More', 'ictu')          => 'load_more',
                            esc_html__('Infinite Scrolling', 'ictu') => 'infinite',
                        ),
                        'std'        => 'none',
                        'group'      => esc_html__('Product Options', 'ictu'),
                    ),
                    array(
                        'type'       => 'vc_link',
                        'heading'    => esc_html__('Button', 'ictu'),
                        'param_name' => 'view_all_button',
                        'group'      => esc_html__('Product Options', 'ictu'),
                        'dependency' => array(
                            'element' => 'pagination',
                            'value'   => array('view_all'),
                        ),
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__('Target', 'ictu'),
                        'param_name'  => 'target',
                        'value'       => array(
                            esc_html__('Recent Products', 'ictu')       => 'recent_products',
                            esc_html__('Feature Products', 'ictu')      => 'featured_products',
                            esc_html__('Sale Products', 'ictu')         => 'sale_products',
                            esc_html__('Best Selling Products', 'ictu') => 'best_selling_products',
                            esc_html__('Top Rated Products', 'ictu')    => 'top_rated_products',
                            esc_html__('Products', 'ictu')              => 'products',
                            esc_html__('Products Category', 'ictu')     => 'product_category',
                            esc_html__('Products Attribute', 'ictu')    => 'product_attribute',
                            esc_html__('Products Related', 'ictu')      => 'related_products',
                        ),
                        'admin_label' => true,
                        'description' => esc_html__('Choose the target to filter products', 'ictu'),
                        'std'         => 'recent_products',
                        'group'       => esc_html__('Product Options', 'ictu'),
                    ),
                    array(
                        'type'        => 'autocomplete',
                        'heading'     => esc_html__('Products', 'ictu'),
                        'param_name'  => 'ids',
                        'settings'    => array(
                            'multiple'      => true,
                            'sortable'      => true,
                            'unique_values' => true,
                            // In UI show results except selected. NB! You should manually check values in backend
                        ),
                        'group'       => esc_html__('Product Options', 'ictu'),
                        'description' => esc_html__('Enter List of Products', 'ictu'),
                        'dependency'  => array(
                            'element' => 'target',
                            'value'   => array('products'),
                        ),
                    ),
                    array(
                        'type'        => 'taxonomy',
                        'heading'     => esc_html__('Product Categories', 'ictu'),
                        'param_name'  => 'category',
                        'settings'    => array(
                            'multiple'    => true,
                            'hide_empty'  => false,
                            'taxonomy'    => 'product_cat',
                            'placeholder' => esc_html__('Select Products Category', 'ictu'),
                        ),
                        'dependency'  => array(
                            'element'            => 'target',
                            'value_not_equal_to' => 'products',
                        ),
                        'group'       => esc_html__('Product Options', 'ictu'),
                        'description' => esc_html__('Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.', 'ictu'),
                    ),
                    array(
                        'type'        => 'taxonomy',
                        'heading'     => esc_html__('Product Brand', 'ictu'),
                        'param_name'  => 'category_brand',
                        'settings'    => array(
                            'multiple'    => true,
                            'hide_empty'  => false,
                            'taxonomy'    => 'product_brand',
                            'placeholder' => esc_html__('Select Products Brand', 'ictu'),
                        ),
                        'dependency'  => array(
                            'element'            => 'target',
                            'value_not_equal_to' => 'products',
                        ),
                        'group'       => esc_html__('Product Options', 'ictu'),
                        'description' => esc_html__('Note: If you want to narrow output, select Brand(s) above. Only selected Brand will be displayed.', 'ictu'),
                    ),
                    array(
                        'type'       => 'hidden',
                        'param_name' => 'skus',
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => esc_html__('Per page', 'ictu'),
                        'value'       => 6,
                        'param_name'  => 'limit',
                        'admin_label' => true,
                        'group'       => esc_html__('Product Options', 'ictu'),
                        'description' => esc_html__('How much items per page to show', 'ictu'),
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__('Order by', 'ictu'),
                        'param_name'  => 'orderby',
                        'value'       => $order_by_values,
                        'group'       => esc_html__('Product Options', 'ictu'),
                        'description' => sprintf(esc_html__('Select how to sort retrieved products. More at %s.', 'ictu'), '<a href="' . esc_url('http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters') . '" target="_blank">' . esc_html__('WordPress codex page', 'ictu') . '</a>'),
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__('Sort order', 'ictu'),
                        'param_name'  => 'order',
                        'value'       => $order_way_values,
                        'group'       => esc_html__('Product Options', 'ictu'),
                        'description' => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'ictu'), '<a href="' . esc_url('http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters') . '" target="_blank">' . esc_html__('WordPress codex page', 'ictu') . '</a>'),
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__('Attribute', 'ictu'),
                        'param_name'  => 'attribute',
                        'value'       => $attributes,
                        'group'       => esc_html__('Product Options', 'ictu'),
                        'description' => esc_html__('List of product taxonomy attribute', 'ictu'),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => esc_html__('Filter', 'ictu'),
                        'param_name'  => 'filter',
                        'value'       => array('empty' => 'empty'),
                        'group'       => esc_html__('Product Options', 'ictu'),
                        'description' => esc_html__('Taxonomy values', 'ictu'),
                        'dependency'  => array(
                            'callback' => 'vcWoocommerceProductAttributeFilterDependencyCallback',
                        ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__('Rows space', 'ictu'),
                        'param_name' => 'owl_rows_space',
                        'value'      => array(
                            esc_html__('Default', 'ictu') => '',
                            esc_html__('10px', 'ictu')    => 'rows-space-10',
                            esc_html__('20px', 'ictu')    => 'rows-space-20',
                            esc_html__('30px', 'ictu')    => 'rows-space-30',
                            esc_html__('40px', 'ictu')    => 'rows-space-40',
                            esc_html__('50px', 'ictu')    => 'rows-space-50',
                            esc_html__('60px', 'ictu')    => 'rows-space-60',
                        ),
                        'std'        => '',
                        'dependency' => array(
                            'element' => 'productsliststyle',
                            'value'   => array('owl'),
                        ),
                        'group'      => esc_html__('Carousel Settings', 'ictu'),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__('Navigation style', 'ictu'),
                        'param_name' => 'owl_navigation_style',
                        'value'      => array(
                            esc_html__('Default', 'ictu')           => '',
                            esc_html__('Nav Square', 'ictu')        => 'nav-square',
                            esc_html__('Nav Hover Square', 'ictu')  => 'nav-hover-square',
                            esc_html__('Nav on top', 'ictu')        => 'nav-on-top',
                            esc_html__('Nav Square on top', 'ictu') => 'nav-on-top nav-square-on-top',
                            esc_html__('Nav style 01', 'ictu')      => 'nav-square nav-style-01',
                            esc_html__('Nav style 02', 'ictu')      => 'nav-style-02',
                            esc_html__('Nav style 03', 'ictu')      => 'nav-style-03',
                            esc_html__('Nav Tools2', 'ictu')        => 'nav-tools2',
                        ),
                        'std'        => '',
                        'dependency' => array(
                            'element' => 'productsliststyle',
                            'value'   => array('owl'),
                        ),
                        'group'      => esc_html__('Carousel Settings', 'ictu'),
                    ),
                    array(
                        'type'       => 'number',
                        'heading'    => esc_html__('Navigation offset top', 'ictu'),
                        'param_name' => 'owl_nav_offset_top',
                        'suffix'     => 'px',
                        'dependency' => array(
                            'element' => 'owl_navigation_style',
                            'value'   => array('nav-on-top', 'nav-on-top nav-square-on-top'),
                        ),
                        'group'      => esc_html__('Carousel Settings', 'ictu'),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__('Content Overflow', 'ictu'),
                        'param_name' => 'content_overflow',
                        'value'      => array(
                            esc_html__('No', 'ictu')  => '',
                            esc_html__('Yes', 'ictu') => 'content-overflow',
                        ),
                        'std'        => '',
                        'group'      => esc_html__('Carousel Settings', 'ictu'),
                        'dependency' => array(
                            'element' => 'productsliststyle',
                            'value'   => array('owl'),
                        ),
                    ),
                    array(
                        'type'       => 'carousel',
                        'heading'    => esc_html__('Carousel Settings', 'ictu'),
                        'param_name' => 'carousel',
                        'group'      => esc_html__('Carousel Settings', 'ictu'),
                        'dependency' => array(
                            'element' => 'productsliststyle',
                            'value'   => array('owl'),
                        ),
                    ),
                    array(
                        'type'       => 'bootstrap_v3',
                        'heading'    => esc_html__('Bootstrap Settings', 'ictu'),
                        'param_name' => 'bootstrap',
                        'group'      => esc_html__('Bootstrap Settings', 'ictu'),
                        'dependency' => array(
                            'element' => 'productsliststyle',
                            'value'   => array('grid'),
                        ),
                    ),
                ),
            );

            return $param_maps['ovic_products'];
        }

        public function autocomplete()
        {
            if (class_exists('Vc_Vendor_Woocommerce')) {
                $vendor_woocommerce = new Vc_Vendor_Woocommerce();
                //Filters For autocomplete param:
                //For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
                add_filter('vc_autocomplete_ovic_products_ids_callback', array(
                    $vendor_woocommerce,
                    'productIdAutocompleteSuggester'
                ),         10, 1); // Get suggestion(find). Must return an array
                add_filter('vc_autocomplete_ovic_products_ids_render', array(
                    $vendor_woocommerce,
                    'productIdAutocompleteRender'
                ),         10, 1); // Render exact product. Must return an array (label,value)
                //For param: ID default value filter
                add_filter('vc_form_fields_render_field_ovic_products_ids_param_value', array(
                    $vendor_woocommerce,
                    'productsIdsDefaultValue'
                ),         10, 4); // Defines default value for param if not provided. Takes from other param value.
                //For param: "filter" param value
                //vc_form_fields_render_field_{shortcode_name}_{param_name}_param
                add_filter('vc_form_fields_render_field_ovic_products_filter_param', array(
                    $vendor_woocommerce,
                    'productAttributeFilterParamValue'
                ),         10, 4); // Defines default value for param if not provided. Takes from other param value.


                //Filters For autocomplete param:
                //For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
                add_filter('vc_autocomplete_tuongnam_megamenu_items_ids_callback', array(
                    $vendor_woocommerce,
                    'productIdAutocompleteSuggester'
                ),         10, 1); // Get suggestion(find). Must return an array
                add_filter('vc_autocomplete_tuongnam_megamenu_items_ids_render', array(
                    $vendor_woocommerce,
                    'productIdAutocompleteRender'
                ),         10, 1); // Render exact product. Must return an array (label,value)
                //For param: ID default value filter
                add_filter('vc_form_fields_render_field_tuongnam_megamenu_items_ids_param_value', array(
                    $vendor_woocommerce,
                    'productsIdsDefaultValue'
                ),         10, 4); // Defines default value for param if not provided. Takes from other param value.
                //For param: "filter" param value
                //vc_form_fields_render_field_{shortcode_name}_{param_name}_param
                add_filter('vc_form_fields_render_field_tuongnam_megamenu_items_filter_param', array(
                    $vendor_woocommerce,
                    'productAttributeFilterParamValue'
                ),         10, 4); // Defines default value for param if not provided. Takes from other param value.


                /*
                 * COUNTDOWN
                 * */
                //Filters For autocomplete param:
                //For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
                add_filter('vc_autocomplete_ovic_countdown_ids_callback', array(
                    $vendor_woocommerce,
                    'productIdAutocompleteSuggester'
                ),         10, 1); // Get suggestion(find). Must return an array
                add_filter('vc_autocomplete_ovic_countdown_ids_render', array(
                    $vendor_woocommerce,
                    'productIdAutocompleteRender'
                ),         10, 1); // Render exact product. Must return an array (label,value)
                //For param: ID default value filter
                add_filter('vc_form_fields_render_field_ovic_countdown_ids_param_value', array(
                    $vendor_woocommerce,
                    'productsIdsDefaultValue'
                ),         10, 4); // Defines default value for param if not provided. Takes from other param value.
                //For param: "filter" param value
                //vc_form_fields_render_field_{shortcode_name}_{param_name}_param
                add_filter('vc_form_fields_render_field_ovic_countdown_filter_param', array(
                    $vendor_woocommerce,
                    'productAttributeFilterParamValue'
                ),         10, 4); // Defines default value for param if not provided. Takes from other param value.
            }
            /* AUTOCOMPLETE POST */
            add_filter('vc_autocomplete_ovic_blog_ids_callback', array(
                $this,
                'post_autocomplete_suggester'
            ),         10, 1);
            add_filter('vc_autocomplete_ovic_blog_ids_render', array(
                $this,
                'post_autocomplete_suggester_render'
            ),         10, 1);
        }

        public function add_vc_fonts($fonts_list)
        {
            /* IBMPlex Sans */
            $IBMPlexSans              = new stdClass();
            $IBMPlexSans->font_family = "IBM Plex Sans";
            $IBMPlexSans->font_styles = "100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i";
            $IBMPlexSans->font_types  = '100 thin:100:normal,100 thin  italic:100:italic,200 extra-light:200:normal,200 extra-light italic:200:italic,300 light :300:normal,300 light italic :300:italic,400 regular:400:normal,400 regular italic:400:italic,500 medium:500:normal,500 medium italic:500:italic,600 semi bold:600:normal,600 semi bold italic:600:italic,700 bold :700:normal,700 bold italic:700:italic';
            $IBMPlexSans->font_styles = 'regular';
            /* Barlow */
            $Barlow              = new stdClass();
            $Barlow->font_family = "Barlow";
            $Barlow->font_styles = "100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i";
            $Barlow->font_types  = '100 thin:100:normal,100 thin  italic:100:italic,200 extra-light:200:normal,200 extra-light italic:200:italic,300 light :300:normal,300 light italic :300:italic,400 regular:400:normal,400 regular italic:400:italic,500 medium:500:normal,500 medium italic:500:italic,600 semi bold:600:normal,600 semi bold italic:600:italic,700 bold :700:normal,700 bold italic:700:italic,800 extra-bold :800:normal,800 extra-bold italic:800:italic,900 black :900:normal,900 black italic:900:italic';
            $Barlow->font_styles = 'regular';
            $fonts               = array($IBMPlexSans, $Barlow);

            return array_merge($fonts, $fonts_list);
        }
    }

    new Avocado_Visual_Composer();
}

VcShortcodeAutoloader::getInstance()->includeClass('WPBakeryShortCode_VC_Tta_Accordion');

if (class_exists('WPBakeryShortCode_VC_Tta_Accordion') && !class_exists('WPBakeryShortCode_Ovic_Tabs')) {
    class WPBakeryShortCode_Ovic_Tabs extends WPBakeryShortCode_VC_Tta_Accordion { }
}

if (class_exists('WPBakeryShortCode_VC_Tta_Accordion') && !class_exists('WPBakeryShortCode_Ovic_Accordion')) {
    class WPBakeryShortCode_Ovic_Accordion extends WPBakeryShortCode_VC_Tta_Accordion { }
}

if (class_exists('WPBakeryShortCodesContainer') && !class_exists('WPBakeryShortCode_Ovic_Container')) {
    class WPBakeryShortCode_Ovic_Container extends WPBakeryShortCodesContainer { }
}
// if ( !class_exists( 'WPBakeryShortCode_Ovic_Banner_Build' ) && !class_exists( 'WPBakeryShortCode_Ovic_Banner_Build' ) ) {
//     class WPBakeryShortCode_Ovic_Banner_Build extends WPBakeryShortCodesContainer{}
// }

if (class_exists('WPBakeryShortCodesContainer') && !class_exists('WPBakeryShortCode_Intn_Megamenu_Container')) {
    class WPBakeryShortCode_Intn_Megamenu_Container extends WPBakeryShortCodesContainer { }
}