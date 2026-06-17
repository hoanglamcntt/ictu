<?php
if (!class_exists('Avocado_Load_Widgets')) {
    class Avocado_Load_Widgets {
        public function __construct()
        {
//            if (class_exists('WooCommerce')) {
//                require_once get_parent_theme_file_path('/framework/widgets/class-theme-widget-product-categories.php');
//                require_once get_parent_theme_file_path('/framework/widgets/class-theme-widget-featured-products.php');
//            }
            if (class_exists('OVIC_Widget')) {
                require_once get_parent_theme_file_path('/framework/widgets/class-theme-widget-related-posts.php');
//                require_once get_parent_theme_file_path('/framework/widgets/class-theme-widget-support-systems.php');
                require_once get_parent_theme_file_path('/framework/widgets/class-theme-widget-featured-posts.php');
            }
        }
    }
}

new Avocado_Load_Widgets();