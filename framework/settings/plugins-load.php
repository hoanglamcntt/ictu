<?php
/**
 * Class plugin load.
 */
require_once get_parent_theme_file_path('/framework/classes/class-tgm-plugin-activation.php');
if (!defined('ABSPATH')) {
    exit;
}
if (!function_exists('theme_plugin_load')) {
    function theme_plugin_load()
    {
        /*
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(
            array(
                'name'     => 'Ov Addon Toolkit',
                'slug'     => 'ov-addon-toolkit',
                'source'   => get_theme_file_uri('/plugins/ov-addon-toolkit.zip'),
                'required' => true,
            ),
            //            array(
            //                'name'     => 'WPBakery Visual Composer',
            //                'slug'     => 'js_composer',
            //                'source'   => get_theme_file_uri('/plugins/js_composer.zip'),
            //                'required' => true,
            //                'version'  => '6.6',
            //            ),
            //            array(
            //                'name'               => 'Revolution Slider',
            //                'slug'               => 'revslider',
            //                'source'             => esc_html('https://plugins.kutethemes.net/revslider.zip'),
            //                'required'           => true,
            //                'version'            => '',
            //                'force_activation'   => false,
            //                'force_deactivation' => false,
            //                'external_url'       => '',
            //            ),
            array(
                'name'     => 'Elementor',
                'slug'     => 'elementor',
                'required' => true,
            ),
            //            array(
            //                'name'     => 'WooCommerce',
            //                'slug'     => 'woocommerce',
            //                'required' => true,
            //            ),
            //            array(
            //                'name'     => 'Mailchimp for WordPress',
            //                'slug'     => 'mailchimp-for-wp',
            //                'required' => true,
            //            ),
            //            array(
            //                'name'     => 'AJAX Search for WooCommerce',
            //                'slug'     => 'ajax-search-for-woocommerce',
            //                'required' => true,
            //            ),
            //            array(
            //                'name'     => 'WooCommerce Variation Swatches',
            //                'slug'     => 'woo-product-variation-swatches',
            //                'required' => true,
            //            ),
            //			array(
            //				'name' => 'YITH WooCommerce Compare',
            //				'slug' => 'yith-woocommerce-compare',
            //			),
            //			array(
            //				'name' => 'YITH WooCommerce Wishlist',
            //				'slug' => 'yith-woocommerce-wishlist',
            //			),
            //			array(
            //				'name' => 'YITH WooCommerce Quick View',
            //				'slug' => 'yith-woocommerce-quick-view',
            //			),
            //            array(
            //                'name' => 'Contact Form 7',
            //                'slug' => 'contact-form-7',
            //            )
        );
        /*
         * Array of configuration settings. Amend each line as needed.
         *
         * TGMPA will start providing localized text strings soon. If you already have translations of our standard
         * strings available, please help us make TGMPA even better by giving us access to these translations or by
         * sending in a pull-request with .po file(s) with the translations.
         *
         * Only uncomment the strings in the config array if you want to customize the strings.
         */
        $config = array(
            'id'           => 'theme_plugins',
            'default_path' => '',
            'menu'         => 'ictu-install-plugins',
            'parent_slug'  => 'themes.php',
            'capability'   => 'edit_theme_options',
            'has_notices'  => true,
            'dismissable'  => true,
            'dismiss_msg'  => '',
            'is_automatic' => true,
            'message'      => '',
        );
        tgmpa($plugins, $config);
    }
}
add_action('tgmpa_register', 'theme_plugin_load');
