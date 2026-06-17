<?php defined('ABSPATH') || exit;

use \Elementor\Controls_Manager as Controls_Manager;

class Elementor_Ovic_Posts extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_posts';
    }

    public function get_title()
    {
        return esc_html__('Ovic Posts', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-post';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-posts'];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'general_section',
            array(
                'tab'   => Controls_Manager::TAB_CONTENT,
                'label' => __('General', 'ictu'),
            )
        );

        $this->add_control(
            'style',
            [
                'type'    => Controls_Manager::SELECT,
                'label'   => esc_html__('Select style', 'ictu'),
                'options' => array(
                    'style-01' => __('Grid', 'ictu'),
                    'style-02' => __('List', 'ictu'),
                    'style-03' => __('Carousel', 'ictu'),
                ),
                'default' => 'style-01',
            ]
        );

        $this->add_control(
            'category',
            [
                'label'       => __('Category', 'ictu'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_taxonomy(['meta_key' => '', 'hide_empty' => true]),
                'label_block' => true
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'   => __('Number of article', 'ictu'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 4
            ]
        );

        $this->add_control(
            'prevNextButtons',
            [
                'label'        => __('Enable Nav', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true,
                'condition'    => array('style' => 'style-03'),
            ]
        );

        $this->add_control(
            'pageDots',
            [
                'label'        => __('Enable Dots', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true,
                'condition'    => array('style' => 'style-03'),
            ]
        );
        $this->add_control(
            'button_label',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Button Label', 'ictu'),
                'default'     => __('Xem thêm', 'ictu'),
                'condition'   => array('style' => 'style-03'),
            )
        );

        $this->add_control(
            'button_link',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Button Link', 'ictu'),
                'default'     => '#',
                'condition'   => array('style' => 'style-03'),
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