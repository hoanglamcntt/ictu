<?php

namespace Elementor;

class Review_Years_Widget extends Widget_Base {

    public function get_name()
    {
        return 'review_years';
    }


    public function get_title()
    {
        return __('Review Years', 'ictu');
    }


    public function get_icon()
    {
        return 'fa fa-code';
    }

    /* categories. */
    public function get_categories()
    {
        return ['basic'];
    }


    protected function _register_controls()
    {

//        $this->start_controls_section(
//            'content_section',
//            [
//                'label' => __('Content', 'ictu'),
//                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
//            ]
//        );
//
//        $this->add_control(
//            'url',
//            [
//                'label'       => __('URL to embed', 'ictu'),
//                'type'        => \Elementor\Controls_Manager::TEXT,
//                'input_type'  => 'url',
//                'placeholder' => __('https://your-link.com', 'plugin-name'),
//            ]
//        );
//
//        $this->end_controls_section();


        $this->start_controls_section(
            'general_section',
            array(
                'tab'   => Controls_Manager::TAB_CONTENT,
                'label' => __('General', 'ictu'),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'number',
            array(
                'type'    => Controls_Manager::NUMBER,
                'label'   => __('Years', 'ictu'),
                'default' => 0
            )
        );

        $repeater->add_control(
            'gallery',
            array(
                'label' => __('Gallery', 'ictu'),
                'type'  => Controls_Manager::GALLERY
            )
        );

        $repeater->add_control(
            'desc',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXTAREA,
                'label'       => __('Description', 'ictu'),
                'default'     => ''
            )
        );

        $repeater->add_control(
            'link',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Link', 'ictu'),
                'default'     => '#'
            )
        );

        $this->add_control(
            'list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'label'       => __('Reviews', 'ictu'),
                'title_field' => '{{{number}}}',
                'show_label'  => true,
                'label_block' => true,
                'fields'      => $repeater->get_controls()
            )
        );

        $this->end_controls_section();

    }


    protected function render()
    {

        $settings = $this->get_settings_for_display();

        echo '<pre>';
        print_r($settings);
        echo '</pre>';

//        $html = wp_oembed_get($settings['url']);

//        echo '<div class="oembed-elementor-widget">';
//
//        echo ($html) ? $html : $settings['url'];
//
//        echo '</div>';

    }
}