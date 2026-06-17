<?php
defined('ABSPATH') || exit;

/**
 * Theme support system widget class.
 *
 * @extends WC_Widget
 */
if (!class_exists('Theme_Widget_Support_Systems')) {
    class Theme_Widget_Support_Systems extends OVIC_Widget {

        public function __construct()
        {
            $this->widget_cssclass    = 'theme-widget theme-widget-support-systems';
            $this->widget_description = esc_html__('Display your support system', 'ictu');
            $this->widget_id          = 'theme-widget-support-systems';
            $this->widget_name        = esc_html__('Theme: Support Systems', 'ictu');
            $this->settings           = apply_filters(
                'theme-widget-support-systems-settings',
                array(
                    'hotline'        => array(
                        'type'  => 'text',
                        'title' => esc_html__('Hotline', 'ictu'),
                    ),
                    'email'          => array(
                        'type'  => 'text',
                        'title' => esc_html__('Email', 'ictu'),
                    ),
                    'sky_supporters' => array(
                        'id'     => 'sky_supporters',
                        'type'   => 'group',
                        'title'  => esc_html__('Skype Supporters', 'ictu'),
                        'fields' => array(
                            array(
                                'id'    => 'name',
                                'type'  => 'text',
                                'title' => esc_html__('Name', 'ictu'),
                            ),
                            array(
                                'id'    => 'phone',
                                'type'  => 'text',
                                'title' => esc_html__('Phone number', 'ictu'),
                            ),
                        ),
                    ),
                )
            );
            parent::__construct();
        }

        /**
         * Output widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Widget instance.
         * @see WP_Widget
         */
        public function widget($args, $instance)
        {
            $this->widget_start($args, $instance);

            ob_start();
            ?>
            <div class="theme-widget-support-systems__inner">
                <h3 class="theme-widget-support-systems__title"><?php esc_html_e('Support systems', 'ictu'); ?></h3>
                <div class="theme-widget-support-systems__content">
                    <?php if ($instance['hotline']): ?>
                        <p class="theme-widget-support-systems__hotline">
                            <img src="<?php echo esc_url(get_theme_file_uri('assets/images/phone-sp.png')) ?>" alt="" width="19" height="19">
                            <?php esc_html_e('Hotline:', 'ictu'); ?>
                            <span><?php echo esc_html($instance['hotline']) ?></span>
                        </p>
                    <?php endif; ?>
                    <?php if ($instance['email']): ?>
                        <p class="theme-widget-support-systems__email">
                            <img src="<?php echo esc_url(get_theme_file_uri('assets/images/email.png')) ?>" alt="" width="22" height="14">
                            <?php esc_html_e('Email:', 'ictu'); ?>
                            <span><?php echo esc_html($instance['email']) ?></span>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($instance['sky_supporters'])): ?>
                        <ul class="theme-widget-support-systems__skype-supporters">
                            <?php foreach ($instance['sky_supporters'] as $supporter): ?>
                                <li class="theme-widget-support-systems__skype-supporters__elm">
                                    <span class="theme-widget-support-systems__skype-supporters__media">
                                        <img src="<?php echo esc_url(get_theme_file_uri('assets/images/skype.png')) ?>" alt="" width="19" height="20">
                                        <span class="theme-widget-support-systems__skype-supporters__name"><?php echo esc_html($supporter['name']) ?></span>
                                    </span>
                                    <span class="theme-widget-support-systems__skype-supporters__phone-number"><?php echo esc_html($supporter['phone']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            echo apply_filters('theme-widget-support-systems', ob_get_clean(), $instance);
            $this->widget_end($args);
        }
    }

    /**
     * Register Widgets.
     *
     * @since 2.3.0
     */
    add_action('widgets_init',
        function () {
            register_widget('Theme_Widget_Support_Systems');
        }
    );
}