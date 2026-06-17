<?php defined('ABSPATH') || exit;

use \Elementor\Controls_Manager as Controls_Manager;

class Elementor_Ovic_Button extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_button';
    }

    public function get_title()
    {
        return esc_html__('Ovic Button', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-button';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-button'];
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
                    'style-01' => __('Style 01', 'ictu'),
                    //                    'style-02' => __('Style 02', 'ictu'),
                ),
                'default' => 'style-01',
            ]
        );

        $this->add_control(
            'text',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Label', 'ictu'),
                'default'     => __('Xem thêm', 'ictu')
            )
        );

        $this->add_control(
            'href',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Link', 'ictu'),
                'default'     => '#'
            )
        );

        $this->add_control(
            'color',
            [
                'label'     => esc_html__('Color', 'ictu'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ovic-button__btn' => 'color: {{VALUE}};',
                ],
                'default'   => '#ffffff'
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label'     => esc_html__('Background Color', 'ictu'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ovic-button__btn' => 'background-color: {{VALUE}};',
                ],
                'default'   => '#006cb5'
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label'     => esc_html__('Hover Color', 'ictu'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ovic-button__btn:hover' => 'color: {{VALUE}};',
                ],
                'default'   => '#ffffff'
            ]
        );

        $this->add_control(
            'hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'ictu'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ovic-button__btn:hover' => 'background-color: {{VALUE}};',
                ],
                'default'   => '#333333'
            ]
        );

        $this->add_control(
            'width',
            [
                'label'       => __('Width', 'ictu'),
                'description' => __('Unit px', 'ictu'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 276,
                'selectors'   => [
                    '{{WRAPPER}} .ovic-button__btn' => 'width: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'border-radius',
            [
                'label'       => __('Border Radius', 'ictu'),
                'description' => __('Unit px', 'ictu'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 5,
                'selectors'   => [
                    '{{WRAPPER}} .ovic-button__btn' => 'border-radius: {{VALUE}}px;',
                ],
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