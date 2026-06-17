<?php
if (!class_exists('Avocado_Load_Widgets')) {
    class Avocado_Load_Widgets {
        public function __construct()
        {
            if (class_exists('OVIC_Widget')) {
                require_once get_parent_theme_file_path('/framework/widgets/theme-posts.php');
            }
        }
    }
}

new Avocado_Load_Widgets();