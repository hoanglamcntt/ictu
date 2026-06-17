<?php

class Avocado_Framework_Widget {

    protected static $instance = null;

    public static function get_instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    protected function __construct()
    {
        require_once(get_parent_theme_file_path('/widgets/review-years/Review_Years_Widget.php'));
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
    }

    public function register_widgets()
    {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\Review_Years_Widget());
    }

}

if (!function_exists('avocado_framework_widget_init')) {
    function avocado_framework_widget_init()
    {
        Avocado_Framework_Widget::get_instance();
    }

    add_action('init', 'avocado_framework_widget_init');
}
