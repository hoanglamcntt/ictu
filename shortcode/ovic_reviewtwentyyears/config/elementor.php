<?php defined('ABSPATH') || exit;

use Elementor\Controls_Manager as Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Elementor_Ovic_Reviewtwentyyears extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_reviewtwentyyears';
    }

    public function get_title()
    {
        return esc_html__('Ovic Review Years', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-review';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-reviewtwentyyears'];
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
            'video_provider',
            array(
                'label'   => __('Provider', 'ictu'),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'youtube'  => 'Youtube',
                    'vimeo'    => 'Vimeo',
                    'external' => 'External',
                ),
                'default' => 'youtube'
            )
        );

        $repeater->add_control(
            'video_thumb',
            array(
                'label'   => __('Video Poster', 'ictu'),
                'type'    => Controls_Manager::MEDIA,
                'default' => array('url' => Utils::get_placeholder_image_src(), 'alt' => '')
            )
        );

        $repeater->add_control(
            'video_src',
            array(
                'label'       => __('Video Src', 'ictu'),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
            )
        );

        $repeater->add_control(
            'video_caption',
            array(
                'label'       => __('Video Caption', 'ictu'),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
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
            'teacher',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Tỉ lệ giảng viên trình độ cao', 'ictu'),
                'default' => ''
            )
        );

        $repeater->add_control(
            'training_programs',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Số ngành đào tạo', 'ictu'),
                'default' => ''
            )
        );

        $repeater->add_control(
            'total_students',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Quy mô đào tạo ĐHCQ', 'ictu'),
                'default' => ''
            )
        );

        $repeater->add_control(
            'total_labs',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Diện tích sà xây dựng', 'ictu'),
                'default' => ''
            )
        );

        $repeater->add_control(
            'link',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Article link', 'ictu'),
                'default' => ''
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
        echo ovic_do_shortcode($this->get_name(), $settings);
    }

}