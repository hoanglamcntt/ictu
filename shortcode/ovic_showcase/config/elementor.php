<?php defined('ABSPATH') || exit;

use \Elementor\Controls_Manager as Controls_Manager;

class Elementor_Ovic_Showcase extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_showcase';
    }

    public function get_title()
    {
        return esc_html__('Ovic Showcase', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-product-description';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-showcase'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'general_section',
            array(
                'tab'   => Controls_Manager::TAB_CONTENT,
                'label' => esc_html__('General', 'ictu'),
            )
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            array(
                'label'   => __('Image', 'ictu'),
                'type'    => Controls_Manager::MEDIA,
                'default' => array('url' => \Elementor\Utils::get_placeholder_image_src(), 'alt' => '')
            )
        );

        $repeater->add_control(
            'text',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Text', 'ictu'),
            )
        );

        $repeater->add_control(
            'link',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Link', 'ictu'),
                'default'     => '#',
            )
        );

        $this->add_control(
            'list',
            array(
                'type'   => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'label'  => __('List show case', 'ictu'),
                'title'  => __('List show case', 'ictu'),
            )
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo ovic_do_shortcode($this->get_name(), $settings);
    }

}