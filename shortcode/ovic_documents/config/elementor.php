<?php defined('ABSPATH') || exit;

use \Elementor\Controls_Manager as Controls_Manager;

class Elementor_Ovic_Documents extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_documents';
    }

    public function get_title()
    {
        return esc_html__('Ovic Document List', 'ictu');
    }

    public function get_icon()
    {
        return ' eicon-check-circle';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-documents'];
    }

    protected function _register_controls()
    {

//        $this->start_controls_section(
//            'general_section',
//            array(
//                'tab'   => Controls_Manager::TAB_CONTENT,
//                'label' => __('General', 'ictu'),
//            )
//        );
//
//        $this->add_control(
//            'block_title',
//            array(
//                'type'        => Controls_Manager::TEXT,
//                'label'       => __('Title', 'ictu'),
//                'label_block' => true,
//                'default'     => __('Why Choose Us', 'ictu'),
//            )
//        );
//
//        $this->add_control(
//            'bg_color',
//            [
//                'label'     => __('Background Color', 'ictu'),
//                'type'      => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .--wcs-bg-color' => 'background-color: {{VALUE}};',
//                ],
//                'default'   => '#006cb6'
//            ]
//        );
//
//        $this->add_control(
//            'color',
//            [
//                'label'     => __('Text Color', 'ictu'),
//                'type'      => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .--wcs-text-color' => 'color: {{VALUE}};',
//                ],
//                'default'   => '#ffffff'
//            ]
//        );
//
//        $repeater = new \Elementor\Repeater();
//
//        $repeater->add_control(
//            'number',
//            array(
//                'type'    => Controls_Manager::NUMBER,
//                'label'   => __('Number', 'ictu'),
//                'default' => 0
//            )
//        );
//
//        $repeater->add_control(
//            'suffix_style',
//            array(
//                'type'    => Controls_Manager::SELECT,
//                'label'   => __('Suffix Style', 'ictu'),
//                'options' => array(
//                    'custom' => __('Custom', 'ictu'),
//                    'star'   => __('Star', 'ictu'),
//                ),
//                'default' => 'custom',
//            )
//        );
//
//        $repeater->add_control(
//            'suffix',
//            array(
//                'type'      => Controls_Manager::TEXT,
//                'label'     => __('Suffix', 'ictu'),
//                'default'   => '%',
//                'condition' => array('suffix_style' => 'custom')
//            )
//        );
//
//        $repeater->add_control(
//            'desc',
//            array(
//                'label_block' => true,
//                'type'        => Controls_Manager::TEXT,
//                'label'       => __('Description', 'ictu'),
//                'default'     => ''
//            )
//        );
//
//        $this->add_control(
//            'list',
//            array(
//                'type'        => Controls_Manager::REPEATER,
//                'label'       => __('List Elements', 'ictu'),
//                'title_field' => '{{{ number }}}{{{ suffix }}}',
//                'show_label'  => true,
//                'label_block' => true,
//                'fields'      => $repeater->get_controls()
//            )
//        );
//
//        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo ovic_do_shortcode($this->get_name(), $settings);
    }

}