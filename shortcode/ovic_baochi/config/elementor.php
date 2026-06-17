<?php defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager as Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils as Utils;

class Elementor_Ovic_Baochi extends Ovic_Widget_Elementor
{

    public function get_name()
    {
        return 'ovic_baochi';
    }

    public function get_title()
    {
        return esc_html__( 'Ovic Baochi', 'ictu' );
    }

    public function get_icon()
    {
        return 'eicon-image-bold';
    }

    public function get_categories()
    {
        return array( 'ovic' );
    }

    public function get_style_depends()
    {
        return [ 'ovic-baochi' ];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'general_section',
            array(
                'tab'   => Controls_Manager::TAB_CONTENT,
                'label' => __( 'General', 'ictu' ),
            )
        );

        $this->add_responsive_control(
            'columns',
            [
                'type'  => Controls_Manager::NUMBER,
                'label' => esc_html__( 'Columns', 'umeno' ),
                'selectors' => [
                    '{{WRAPPER}} .ovic-document-list' => '--cols: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo ovic_do_shortcode( $this->get_name(), $settings );
    }

}