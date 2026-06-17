<?php defined('ABSPATH') || exit;

use \Elementor\Controls_Manager as Controls_Manager;

class Elementor_Ovic_Testimonial extends Ovic_Widget_Elementor {

    public function get_name()
    {
        return 'ovic_testimonial';
    }

    public function get_title()
    {
        return esc_html__('Ovic Testimonials', 'ictu');
    }

    public function get_icon()
    {
        return 'eicon-editor-quote';
    }

    public function get_categories()
    {
        return array('ovic');
    }

    public function get_style_depends()
    {
        return ['ovic-testimonial'];
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
                    'testimonial' => __('Testimonial', 'ictu'),
                    // 'vision_and_mission' => __('Vision And Mission', 'ictu')
                ),
                'default' => 'testimonial',
            )
        );

        $this->add_control(
            'visions_and_missions',
            array(
                'type'        => Controls_Manager::REPEATER,
                'label'       => __('Elements', 'ictu'),
                'title_field' => '{{{ name }}}',
                'show_label'  => true,
                'label_block' => true,
                'fields'      => $this->getVisionsAndMissions_controls(),
                'condition'   => array('style' => 'vision_and_mission')
            )
        );

        $this->add_control(
            'testimonials',
            array(
                'type'        => Controls_Manager::REPEATER,
                'label'       => __('Elements', 'ictu'),
                'title_field' => '{{{ name }}}',
                'show_label'  => true,
                'label_block' => true,
                'fields'      => $this->getTestimonial_controls(),
                'condition'   => array('style' => 'testimonial')
            )
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo ovic_do_shortcode($this->get_name(), $settings);
    }

    /**
     * @return mixed Controls list.
     */
    protected function getTestimonial_controls()
    {
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'avatar',
            array(
                'label_block' => true,
                'label'       => __('Avatar', 'ictu'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array('url' => \Elementor\Utils::get_placeholder_image_src(), 'alt' => '')
            )
        );

        $repeater->add_control(
            'name',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Name', 'ictu'),
                'default' => ''
            )
        );

        $repeater->add_control(
            'title',
            array(
                'type'    => Controls_Manager::TEXT,
                'label'   => __('Job Title', 'ictu'),
                'default' => ''
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

        return $repeater->get_controls();
    }

    /**
     * @return mixed Controls list.
     */
    protected function getVisionsAndMissions_controls()
    {
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'v_and_m_layout',
            array(
                'type'    => Controls_Manager::SELECT,
                'label'   => __('Layout', 'ictu'),
                'options' => array(
                    'one' => __('One slide', 'ictu'),
                    'two' => __('Two Slide', 'ictu')
                ),
                'default' => 'one'
            )
        );

        $repeater->add_control(
            'subject',
            array(
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'label'       => __('Subject', 'ictu'),
                'default'     => ''
            )
        );

        $repeater->add_control(
            'icon',
            array(
                'label_block' => true,
                'label'       => __('icon', 'ictu'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array('url' => \Elementor\Utils::get_placeholder_image_src(), 'alt' => '')
            )
        );

        $repeater->add_control(
            'text',
            array(
                'type'    => Controls_Manager::TEXTAREA,
                'label'   => __('Text', 'ictu'),
                'default' => ''
            )
        );

        $repeater->add_control(
            'icon2',
            array(
                'label_block' => true,
                'label'       => __('icon 2', 'ictu'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array('url' => \Elementor\Utils::get_placeholder_image_src(), 'alt' => ''),
                'condition'   => array('v_and_m_layout' => 'two')
            )
        );

        $repeater->add_control(
            'text2',
            array(
                'type'      => Controls_Manager::TEXTAREA,
                'label'     => __('Text 2', 'ictu'),
                'condition' => array('v_and_m_layout' => 'two'),
                'default'   => ''
            )
        );
        return $repeater->get_controls();
    }

}