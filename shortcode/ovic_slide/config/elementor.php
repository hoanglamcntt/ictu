<?php defined('ABSPATH') || exit;

use \Elementor\Controls_Manager as Controls_Manager;
use Elementor\Utils;

class Elementor_Ovic_Slide extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_slide';
    }

    public function get_title()
    {
        return esc_html__('Ovic Slide', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-slider-3d';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-flickity-slide'];
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
            'layout',
            [
                'type'        => Controls_Manager::SELECT,
                'label'       => __('Layout', 'ictu'),
                'label_block' => true,
                'default'     => 'layout-01',
                'options'     => array(
                    'layout-01' => __('Layout 01', 'ictu')
                )
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'desktop',
            array(
                'label_block' => true,
                'label'       => __('Slide On Desktop', 'ictu'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array('url' => Utils::get_placeholder_image_src(), 'alt' => '')
            )
        );

        $repeater->add_control(
            'mobile',
            array(
                'label_block' => true,
                'label'       => __('Slide For Mobile', 'ictu'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array('url' => Utils::get_placeholder_image_src(), 'alt' => '')
            )
        );

        $repeater->add_control(
            'link',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Link', 'ictu'),
                'default'     => ''
            )
        );

        $this->add_control(
            'galleries',
            array(
                'type'        => Controls_Manager::REPEATER,
                'label'       => __('Slides', 'ictu'),
                'title_field' => 'Slide {{{_id}}}',
                'show_label'  => true,
                'label_block' => true,
                'fields'      => $repeater->get_controls()
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'slide_configs',
            array(
                'tab'   => Controls_Manager::TAB_CONTENT,
                'label' => __('Slide Settings', 'ictu'),
            )
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
                'show_label'   => true
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
                'show_label'   => true
            ]
        );

        $this->add_control(
            'freeScroll',
            [
                'label'        => __('Free Scroll', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true
            ]
        );

        $this->add_control(
            'wrapAround',
            [
                'label'        => __('Wrap Around', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true
            ]
        );

        $this->add_control(
            'adaptiveHeight',
            [
                'label'        => __('Adaptive Height', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true
            ]
        );

        $this->add_control(
            'draggable',
            [
                'label'        => __('Draggable', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true
            ]
        );

        $this->add_control(
            'autoPlay',
            [
                'label'        => __('AutoPlay', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true
            ]
        );

        $this->add_control(
            'autoPlayTime',
            [
                'label'     => __('AutoPlay time', 'ictu'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3000,
                'condition' => ['autoPlay' => 'yes']
            ]
        );

        $this->add_control(
            'fade',
            [
                'label'        => __('Fade', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true
            ]
        );
        $this->add_control(
            'lazy_load',
            [
                'label'        => __('Lazy load', 'ictu'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ictu'),
                'label_off'    => __('No', 'ictu'),
                'return_value' => 'yes',
                'default'      => '',
                'show_label'   => true
            ]
        );
        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo ovic_do_shortcode($this->get_name(), $settings);
    }

}