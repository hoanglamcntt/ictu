<?php defined('ABSPATH') || exit;

use Elementor\Controls_Manager as Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Elementor_Ovic_Trainingprogram extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_trainingprogram';
    }

    public function get_title()
    {
        return __('Ovic Training Program', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-icon-box';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-trainingprogram'];
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
            'type',
            [
                'type'        => Controls_Manager::SELECT,
                'label'       => __('List type', 'ictu'),
                'label_block' => true,
                'options'     => array(
                    'programs' => __('Programs', 'ictu'),
                    'missions' => __('Missions And Vision', 'ictu')
                ),
                'default'     => 'programs'
            ]
        );

        $this->add_control(
            'image',
            array(
                'label_block' => true,
                'label'       => __('Banners', 'ictu'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array('url' => Utils::get_placeholder_image_src(), 'alt' => '')
            )
        );

        $this->add_control(
            'title',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Title', 'ictu'),
                'default'     => ''
            )
        );

        $repeater_missions = new Repeater();

        $repeater_missions->add_control(
            'text',
            array(
                'type'        => Controls_Manager::TEXTAREA,
                'label'       => __('text', 'ictu'),
                'label_block' => true,
                'default'     => ''
            )
        );

        $this->add_control(
            'missions',
            array(
                'type'        => Controls_Manager::REPEATER,
                'label'       => __('List', 'ictu'),
                'title_field' => '{{{ text }}}',
                'show_label'  => true,
                'label_block' => true,
                'fields'      => $repeater_missions->get_controls(),
                'condition'   => array('type' => 'missions')
            )
        );

        $repeater_programs = new Repeater();

        $repeater_programs->add_control(
            'name',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Name', 'ictu'),
                'default' => ''
            )
        );

        $repeater_programs->add_control(
            'link',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Link', 'ictu'),
                'default' => '#'
            )
        );

        $this->add_control(
            'fields',
            array(
                'type'        => Controls_Manager::REPEATER,
                'label'       => __('Programs', 'ictu'),
                'title_field' => '{{{ name }}}',
                'show_label'  => true,
                'label_block' => true,
                'fields'      => $repeater_programs->get_controls(),
                'condition'   => array('type' => 'programs')
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