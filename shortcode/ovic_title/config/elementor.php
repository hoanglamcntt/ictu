<?php defined('ABSPATH') || exit;

use \Elementor\Controls_Manager as Controls_Manager;

class Elementor_Ovic_Title extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_title';
    }

    public function get_title()
    {
        return esc_html__('Ovic Title', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-product-title';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-title'];
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

        $this->add_control(
            'letters',
            [
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'label'       => esc_html__('Text', 'ictu')
            ]
        );

        $this->add_control(
            'color',
            [
                'label'     => esc_html__('Text Color', 'ictu'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ovic-title__text' => 'color: {{VALUE}};',
                ],
                'default'   => '#333333'
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