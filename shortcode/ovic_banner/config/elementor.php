<?php defined('ABSPATH') || exit;

use Elementor\Controls_Manager as Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils as Utils;

class Elementor_Ovic_Banner extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_banner';
    }

    public function get_title()
    {
        return esc_html__('Ovic Banner', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-image-bold';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-banner'];
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
            array(
                'type'    => Controls_Manager::SELECT,
                'label'   => __('Select style', 'ictu'),
                'options' => array(
                    'single'   => __('Single', 'ictu'),
                    'grid'     => __('Grid', 'ictu'),
                    'gallery'  => __('Gallery', 'ictu'),
                    'video'    => __('Video', 'ictu'),
                    'facebook' => __('Facebook', 'ictu'),
                ),
                'default' => 'grid',
            )
        );

        $grid_repeater = new Repeater();

        $grid_repeater->add_control(
            'image',
            array(
                'label'   => __('Image', 'ictu'),
                'type'    => Controls_Manager::MEDIA,
                'default' => array('url' => Utils::get_placeholder_image_src(), 'alt' => '')
            )
        );

        $grid_repeater->add_control(
            'text',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Text', 'ictu'),
            )
        );

        $grid_repeater->add_control(
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
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $grid_repeater->get_controls(),
                'label'     => __('Banners', 'ictu'),
                'title'     => __('New Banner', 'ictu'),
                'condition' => array('style' => 'grid')
            )
        );

        $this->add_control(
            'banner_image',
            array(
                'label'     => __('Banner', 'ictu'),
                'type'      => Controls_Manager::MEDIA,
                'default'   => array('url' => Utils::get_placeholder_image_src(), 'alt' => ''),
                'condition' => array('style!' => 'grid')
            )
        );

        $this->add_control(
            'banner_label',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Label', 'ictu'),
                'default'     => __('Xem thêm tại', 'ictu'),
                'condition'   => array('style' => array('video', 'facebook'))
            )
        );

        $this->add_control(
            'banner_link',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Link', 'ictu'),
                'default'     => '#',
                'condition'   => array('style' => array('single', 'facebook', 'video'))
            )
        );

        $this->add_control(
            'view_more_link',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Link View More', 'ictu'),
                'default'     => '#',
                'condition'   => array('style' => array('facebook', 'video'))
            )
        );

        $this->add_control(
            'video_src',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Video Src', 'ictu'),
                'default'     => '#',
                'condition'   => array('style' => 'video')
            )
        );

        $this->add_control(
            'video_width',
            array(
                'type'      => Controls_Manager::NUMBER,
                'label'     => __('Video Width', 'ictu'),
                'default'   => 1280,
                'condition' => array('style' => 'video')
            )
        );

        $this->add_control(
            'video_height',
            array(
                'type'      => Controls_Manager::NUMBER,
                'label'     => __('Video Height', 'ictu'),
                'default'   => 720,
                'condition' => array('style' => 'video')
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