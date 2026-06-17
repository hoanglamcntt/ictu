<?php
/**
 * Handle frontend scripts
 *
 * @package Intn/Classes
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
/**
 * Frontend scripts class.
 */
if (!class_exists('Theme_Assets')) {
    class Theme_Assets {
        private static $scripts             = array();
        private static $styles              = array();
        private static $suffix              = '';
        private static $wp_localize_scripts = array();

        /**
         * Hook in methods.
         */
        public static function init()
        {
            /* check for developer mode */
            self::$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
            self::$suffix = '';

            add_action('wp_enqueue_scripts', array(__CLASS__, 'load_scripts'), 999);
            add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_scripts'));
            add_action('wp_print_scripts', array(__CLASS__, 'localize_printed_scripts'), 5);
            add_action('wp_print_footer_scripts', array(__CLASS__, 'localize_printed_scripts'), 5);
            add_action('elementor/frontend/after_register_scripts', array(__CLASS__, 'after_register_scripts'));
            add_action('wp_head', array(__CLASS__, 'generate_google_fonts'));
        }

        public static function after_register_scripts()
        {
            wp_register_style('ovic-documents', get_theme_file_uri('/shortcode/ovic_documents/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-trainingprogram', get_theme_file_uri('/shortcode/ovic_trainingprogram/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-testimonial', get_theme_file_uri('/shortcode/ovic_testimonial/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-reviewtwentyyears', get_theme_file_uri('/shortcode/ovic_reviewtwentyyears/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-whychooseus', get_theme_file_uri('/shortcode/ovic_whychooseus/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-banner', get_theme_file_uri('/shortcode/ovic_banner/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-button', get_theme_file_uri('/shortcode/ovic_button/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-posts', get_theme_file_uri('/shortcode/ovic_posts/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-title', get_theme_file_uri('/shortcode/ovic_title/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-flickity-slide', get_theme_file_uri('/shortcode/ovic_slide/style.css'), array(), THEME_VERSION);
            wp_register_style('ovic-showcase', get_theme_file_uri('/shortcode/ovic_showcase/style.css'), array(), THEME_VERSION);

            wp_register_style('theme-blog', get_theme_file_uri('/assets/css/blog.css'), array(), THEME_VERSION);
            wp_register_style('blog-archive', get_theme_file_uri('/assets/css/blog-archive.css'), array('theme-blog'), THEME_VERSION);
            wp_register_style('theme-post-single', get_theme_file_uri('/assets/css/post-single.css'), array('theme-blog'), THEME_VERSION);
        }

        /**
         * Get google fonts.
         *
         * @return string
         */

        public static function generate_google_fonts()
        {
            ?>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
            <?php
            return '';
        }

        /**
         * Get styles for the frontend.
         *
         * @return array
         */
        public static function get_styles()
        {
            $styles = array(
                'animate-css' => array(
                    'src'     => get_theme_file_uri('/assets/css/animate.min.css'),
                    'deps'    => array(),
                    'version' => '3.7.0',
                    'media'   => 'all',
                    'has_rtl' => false,
                ),
                'chosen'      => array(
                    'src'     => get_theme_file_uri('/assets/vendor/chosen/chosen.min.css'),
                    'deps'    => array(),
                    'version' => '1.8.7',
                    'media'   => 'all',
                    'has_rtl' => false,
                ),
                'bootstrap'   => array(
                    'src'     => get_theme_file_uri('/assets/css/bootstrap.min.css'),
                    'deps'    => array(),
                    'version' => '3.4.1',
                    'media'   => 'all',
                    'has_rtl' => false,
                ),
                'flickity'    => array(
                    'src'     => get_theme_file_uri('/assets/vendor/flickity/flickity.min.css'),
                    'deps'    => array(),
                    'version' => THEME_VERSION,
                    'media'   => 'all',
                    'has_rtl' => false,
                ),
                'elementor'   => array(
                    'src'     => get_theme_file_uri('/assets/css/elementor' . self::$suffix . '.css'),
                    'deps'    => array(),
                    'version' => THEME_VERSION,
                    'media'   => 'all',
                    'has_rtl' => false,
                )
            );
            /* STYLE MAIN */
            $styles['theme-main-style'] = array(
                'src'     => get_theme_file_uri('/assets/css/style' . self::$suffix . '.css'),
                'deps'    => array('font-awesome', 'main-icon', 'icofont', 'elegant'),
                'version' => THEME_VERSION,
                'media'   => 'all',
                'has_rtl' => true,
            );
            $styles['theme-header']     = array(
                'src'     => get_theme_file_uri('/assets/css/header' . self::$suffix . '.css'),
                'deps'    => array(),
                'version' => THEME_VERSION,
                'media'   => 'all',
                'has_rtl' => true,
            );
            $styles['theme-post']       = array(
                'src'     => get_theme_file_uri('/assets/css/post.css'),
                'deps'    => array(),
                'version' => THEME_VERSION,
                'media'   => 'all',
                'has_rtl' => true,
            );
            $styles['theme-main'] = array(
                'src'     => get_stylesheet_uri(),
                'deps'    => array(),
                'version' => THEME_VERSION,
                'media'   => 'all',
                'has_rtl' => false,
            );

            return apply_filters('theme_enqueue_styles', $styles);
        }

        /**
         * Register a script for use.
         *
         * @param string $handle Name of the script. Should be unique.
         * @param string $path Full URL of the script, or path of the script relative to the WordPress root directory.
         * @param string[] $deps An array of registered script handles this script depends on.
         * @param array|false|string $version String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
         * @param boolean $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
         *
         * @uses   wp_register_script()
         */
        private static function register_script($handle, $path, $deps = array('jquery'), $version = THEME_VERSION, $in_footer = true)
        {
            self::$scripts[] = $handle;
            wp_register_script($handle, $path, $deps, $version, $in_footer);
        }

        /**
         * Register and enqueue a script for use.
         *
         * @param string $handle Name of the script. Should be unique.
         * @param string $path Full URL of the script, or path of the script relative to the WordPress root directory.
         * @param string[] $deps An array of registered script handles this script depends on.
         * @param array|false|string $version String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
         * @param boolean $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
         *
         * @uses   wp_enqueue_script()
         */
        private static function enqueue_script($handle, $path = '', $deps = array('jquery'), $version = THEME_VERSION, $in_footer = true)
        {
            if (!in_array($handle, self::$scripts, true) && $path) {
                self::register_script($handle, $path, $deps, $version, $in_footer);
            }
            wp_enqueue_script($handle);
        }

        /**
         * Register a style for use.
         *
         * @param string $handle Name of the stylesheet. Should be unique.
         * @param string $path Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
         * @param string[] $deps An array of registered stylesheet handles this stylesheet depends on.
         * @param array|false|string $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
         * @param string $media The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
         * @param boolean $has_rtl If has RTL version to load too.
         *
         * @uses   wp_register_style()
         */
        private static function register_style($handle, $path, $deps = array(), $version = THEME_VERSION, $media = 'all', $has_rtl = false)
        {
            self::$styles[] = $handle;
            wp_register_style($handle, $path, $deps, $version, $media);
            if ($has_rtl) {
                wp_style_add_data($handle, 'rtl', 'replace');
            }
        }

        /**
         * Register and enqueue a styles for use.
         *
         * @param string $handle Name of the stylesheet. Should be unique.
         * @param string $path Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
         * @param string[] $deps An array of registered stylesheet handles this stylesheet depends on.
         * @param array|false|string $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
         * @param string $media The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
         * @param boolean $has_rtl If has RTL version to load too.
         *
         * @uses   wp_enqueue_style()
         */
        private static function enqueue_style(
            $handle,
            $path = '',
            $deps = array(),
            $version = THEME_VERSION,
            $media = 'all',
            $has_rtl = false
        ) {
            if (!in_array($handle, self::$styles, true) && $path) {
                self::register_style($handle, $path, $deps, $version, $media, $has_rtl);
            }
            wp_enqueue_style($handle);
        }

        /**
         * Register all Intn scripts.
         */
        private static function register_scripts()
        {
            $deps = array(
                'jquery',
                'bootstrap',
                'lazyload',
                'chosen',
                'plyr',
                'fancybox',
                'flickity',
            );
            if (class_exists('Elementor\Plugin') && Elementor\Plugin::$instance->preview->is_preview_mode()) {
                $deps[] = 'theme-countdown';
            }
            $register_scripts = array(
                'theme-frontend'  => array(
                    'src'     => get_theme_file_uri('/assets/js/frontend' . self::$suffix . '.js'),
                    'deps'    => $deps,
                    'version' => THEME_VERSION,
                ),
                'theme-sticky'    => array(
                    'src'     => get_theme_file_uri('/assets/js/sticky' . self::$suffix . '.js'),
                    'deps'    => array('jquery'),
                    'version' => THEME_VERSION,
                ),
                'mobile-menu'     => array(
                    'src'     => get_theme_file_uri('/assets/vendor/mobile-menu/mobile-menu.min.js'),
                    'deps'    => array('jquery'),
                    'version' => THEME_VERSION,
                ),
                'theme-admin'     => array(
                    'src'     => get_theme_file_uri('/assets/js/admin.min.js'),
                    'deps'    => array('jquery', 'flickity', 'theme-frontend'),
                    'version' => THEME_VERSION,
                ),
                /* https://harvesthq.github.io/chosen/ */
                'chosen'          => array(
                    'src'     => get_theme_file_uri('/assets/vendor/chosen/chosen.min.js'),
                    'deps'    => array('jquery'),
                    'version' => '1.8.7',
                ),
                /* http://jquery.eisbehr.de/lazy */
                'lazyload'        => array(
                    'src'     => get_theme_file_uri('/assets/vendor/lazyload/lazyload.min.js'),
                    'deps'    => array('jquery'),
                    'version' => '1.7.10',
                ),
                /* http://hilios.github.io/jQuery.countdown/documentation.html */
                'countdown'       => array(
                    'src'     => get_theme_file_uri('/assets/vendor/countdown/countdown.min.js'),
                    'deps'    => array('jquery'),
                    'version' => '2.2.0',
                ),
                'theme-countdown' => array(
                    'src'     => get_theme_file_uri('/assets/js/countdown' . self::$suffix . '.js'),
                    'deps'    => array('countdown'),
                    'version' => THEME_VERSION,
                ),
                /* https://getbootstrap.com/ */
                'bootstrap'       => array(
                    'src'     => get_theme_file_uri('/assets/js/bootstrap.min.js'),
                    'deps'    => array('jquery'),
                    'version' => '3.4.1',
                ),
                'waypoints'       => array(
                    'src'     => get_theme_file_uri('/assets/js/waypoints.min.js'),
                    'deps'    => array('jquery'),
                    'version' => '2.0.3',
                ),
                'flickity-fade'   => array(
                    'src'     => get_theme_file_uri('/assets/vendor/flickity/flickity.pkgd.min.js'),
                    'deps'    => array('jquery'),
                    'version' => '2.2.2',
                ),
                'flickity'        => array(
                    'src'     => get_theme_file_uri('/assets/vendor/flickity/flickity-fade.js'),
                    'deps'    => array('jquery', 'flickity-fade'),
                    'version' => '2.2.2',
                ),
                /* https://github.com/gromo/jquery.scrollbar/ */
                'scrollbar'       => array(
                    'src'     => get_theme_file_uri('/assets/vendor/scrollbar/scrollbar.min.js'),
                    'deps'    => array('jquery'),
                    'version' => '0.2.10',
                ),
                /* http://dimsemenov.com/plugins/magnific-popup/ */
                'magnific-popup'  => array(
                    'src'     => get_theme_file_uri('/assets/vendor/magnific-popup/magnific-popup.min.js'),
                    'deps'    => array('jquery'),
                    'version' => '1.1.0',
                ),
                'plyr'            => array(
                    'src'     => get_theme_file_uri('/assets/vendor/plyr/plyr.js'),
                    'deps'    => array(),
                    'version' => '3.6.4',
                ),
                'fancybox'        => array(
                    'src'     => get_theme_file_uri('/assets/vendor/fancybox/fancybox.umd.js'),
                    'deps'    => array(),
                    'version' => '4.0.10',
                )
            );
            foreach ($register_scripts as $name => $props) {
                self::register_script($name, $props['src'], $props['deps'], $props['version']);
            }
        }

        /**
         * Register all Intn styles.
         */
        private static function register_styles()
        {
            $register_styles = array(
                'theme-admin'     => array(
                    'src'     => get_theme_file_uri('/assets/css/admin.min.css'),
                    'deps'    => array(),
                    'version' => THEME_VERSION,
                    'has_rtl' => false,
                ),
                'theme-edit-post' => array(
                    'src'     => get_theme_file_uri('/assets/css/edit-post.min.css'),
                    'deps'    => array(),
                    'version' => THEME_VERSION,
                    'has_rtl' => false,
                ),
                'theme-edit-link' => array(
                    'src'     => get_theme_file_uri('/assets/css/edit-link.min.css'),
                    'deps'    => array(),
                    'version' => THEME_VERSION,
                    'has_rtl' => false,
                ),
                'font-awesome'    => array(
                    'src'     => get_theme_file_uri('/assets/css/fontawesome.min.css'),
                    'deps'    => array(),
                    'version' => '4.7.0',
                    'has_rtl' => false,
                ),
                'main-icon'       => array(
                    'src'     => get_theme_file_uri('/assets/vendor/main-icon/style.css'),
                    'deps'    => array(),
                    'version' => '1.0.0',
                    'has_rtl' => false,
                ),
                'icofont'         => array(
                    'src'     => get_theme_file_uri('/assets/vendor/icofont/style.min.css'),
                    'deps'    => array(),
                    'version' => '1.0.0',
                    'has_rtl' => false,
                ),
                'elegant'         => array(
                    'src'     => get_theme_file_uri('/assets/vendor/elegant/style.min.css'),
                    'deps'    => array(),
                    'version' => '1.0.0',
                    'has_rtl' => false,
                ),
                'scrollbar'       => array(
                    'src'     => get_theme_file_uri('/assets/vendor/scrollbar/scrollbar.min.css'),
                    'deps'    => array(),
                    'version' => '0.2.10',
                    'has_rtl' => false,
                ),
                'magnific-effect' => array(
                    'src'     => get_theme_file_uri('/assets/vendor/magnific-popup/magnific-effect.css'),
                    'deps'    => array(),
                    'version' => '1.1.0',
                    'has_rtl' => false,
                ),
                'magnific-popup'  => array(
                    'src'     => get_theme_file_uri('/assets/vendor/magnific-popup/magnific-popup.min.css'),
                    'deps'    => array('magnific-effect'),
                    'version' => '1.1.0',
                    'has_rtl' => false,
                ),
                'mobile-menu'     => array(
                    'src'     => get_theme_file_uri('/assets/vendor/mobile-menu/mobile-menu.min.css'),
                    'deps'    => array(),
                    'version' => THEME_VERSION,
                    'has_rtl' => false,
                ),
                'plyr'            => array(
                    'src'     => get_theme_file_uri('/assets/vendor/plyr/plyr.css'),
                    'deps'    => array(),
                    'version' => '3.6.4',
                    'has_rtl' => false,
                ),
                'fancybox'        => array(
                    'src'     => get_theme_file_uri('/assets/vendor/fancybox/fancybox.css'),
                    'deps'    => array('plyr'),
                    'version' => '4.0.10',
                    'has_rtl' => false,
                )
            );
            foreach ($register_styles as $name => $props) {
                self::register_style($name, $props['src'], $props['deps'], $props['version'], 'all', $props['has_rtl']);
            }
        }

        /**
         * Register/queue backend scripts.
         */
        public static function admin_scripts($hook_suffix)
        {
            self::register_scripts();
            self::register_styles();
            // Styles.
            if (($hook_suffix === 'post-new.php' || $hook_suffix === 'post.php')) {
                self::enqueue_style('theme-edit-post');
            }
            self::enqueue_style('font-awesome');
            self::enqueue_style('main-icon');
            self::enqueue_style('icofont');
            self::enqueue_style('theme-admin');
            // Script.
            self::enqueue_script('theme-admin');
        }

        public static function dequeue_scripts()
        {
            global $post;
            /* DEQUEUE SCRIPTS - OPTIMIZER */
            if (is_a($post, 'WP_Post') && !has_shortcode($post->post_content, 'contact-form-7')) {
                wp_dequeue_style('contact-form-7');
                wp_dequeue_script('contact-form-7');
            }
            /* WOOCOMMERCE */
            if (class_exists('WooCommerce')) {
                if (class_exists('YITH_WCQV_Frontend')) {
                    wp_dequeue_style('yith-quick-view');
                }
                if (defined('YITH_WCWL')) {
                    $page_id = yith_wcwl_object_id(get_option('yith_wcwl_wishlist_page_id'));
                    if (!is_page($page_id)) {
                        wp_dequeue_script('prettyPhoto');
                        wp_dequeue_script('jquery-selectBox');
                        wp_dequeue_style('woocommerce_prettyPhoto_css');
                        wp_dequeue_style('jquery-selectBox');
                        wp_dequeue_style('yith-wcwl-main');
                        wp_dequeue_style('yith-wcwl-user-main');
                    }
                    wp_dequeue_style('yith-wcwl-font-awesome');
                }
                /* PLUGIN SIZE CHART */
                if (class_exists('Size_Chart_For_Woocommerce')) {
                    $size_chart = false;
                    if (is_product()) {
                        $size_chart = get_post_meta($post->ID, 'prod-chart', true);
                    }
                    if (!$size_chart) {
                        wp_dequeue_style('size-chart-for-woocommerce');
                        wp_dequeue_style('size-chart-for-woocommerce-select2');
                        wp_dequeue_style('size-chart-for-woocommerce-jquery-modal');
                        wp_dequeue_style('size-chart-for-woocommerce-jquery-modal-default-theme');
                        wp_dequeue_script('size-chart-for-woocommerce');
                        wp_dequeue_script('size-chart-for-woocommerce-jquery-select2');
                        wp_dequeue_script('size-chart-for-woocommerce-jquery-modal');
                        wp_dequeue_script('size-chart-for-woocommerce-jquery-editable-js');
                        wp_dequeue_script('size-chart-for-woocommerce-jquery-modal-default-theme');
                    }
                }
                if (class_exists('Vc_Manager')) {
                    wp_dequeue_script('vc_woocommerce-add-to-cart-js');
                }
            }
        }

        /**
         * Register/queue frontend scripts.
         */
        public static function load_scripts()
        {
            self::register_scripts();
            self::register_styles();
            // Global frontend scripts.
            if (!class_exists('Ovic_Addon_Toolkit')) {
                self::enqueue_style('mobile-menu');
                self::enqueue_script('mobile-menu');
            }
            self::enqueue_style('fancybox');
            if (!theme_is_mobile()) {
                self::enqueue_style('scrollbar');
                self::enqueue_script('scrollbar');
            }
            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }
            self::enqueue_script('theme-frontend');
            // Add edit link style
            if (is_super_admin()) {
                self::enqueue_style('theme-edit-link');
            }
            // Add inline script
            $ace_script = get_theme_option('ace_script', '');
            if (!empty($ace_script)) {
                wp_add_inline_script('theme-frontend', $ace_script);
            }
            // CSS Styles.
            $enqueue_styles = self::get_styles();
            if (!empty($enqueue_styles)) {
                foreach ($enqueue_styles as $handle => $args) {
                    if (!isset($args['has_rtl'])) {
                        $args['has_rtl'] = false;
                    }
                    self::enqueue_style($handle, $args['src'], $args['deps'], $args['version'], $args['media'], $args['has_rtl']);
                }
            }
            // Optimizer scripts
            self::dequeue_scripts();
        }

        /**
         * Localize a Intn script once.
         *
         * @since 2.3.0 this needs less wp_script_is() calls due to https://core.trac.wordpress.org/ticket/28404 being added in WP 4.0.
         *
         * @param string $handle Script handle the data will be attached to.
         */
        private static function localize_script($handle)
        {
            if (!in_array($handle, self::$wp_localize_scripts, true) && wp_script_is($handle)) {
                $data = self::get_script_data($handle);
                if (!$data) {
                    return;
                }
                $name                        = str_replace('-', '_', $handle) . '_params';
                self::$wp_localize_scripts[] = $handle;
                wp_localize_script($handle, $name, apply_filters($name, $data));
            }
        }

        /**
         * Return data for script handles.
         *
         * @param string $handle Script handle the data will be attached to.
         *
         * @return array|bool
         */
        private static function get_script_data($handle)
        {
            switch ($handle) {
                case 'theme-frontend':
                    $params = array(
                        'ajaxurl'      => admin_url('admin-ajax.php'),
                        'security'     => wp_create_nonce('theme_ajax_frontend'),
                        'ajax_url'     => Theme_Ajax::get_endpoint('%%endpoint%%'),
                        'ajax_comment' => get_theme_option('enable_ajax_comment'),
                        'tab_warning'  => sprintf('<strong>%s</strong> %s', esc_html__('Warning!', 'ictu'), esc_html__('Can not Load Data.', 'ictu')),
                    );
                    break;
                case 'theme-admin':
                    $params = array('security' => wp_create_nonce('theme_ajax_admin'));
                    break;
                default:
                    $params = false;
            }

            return apply_filters('theme_get_script_data', $params, $handle);
        }

        /**
         * Localize scripts only when enqueued.
         */
        public static function localize_printed_scripts()
        {
            foreach (self::$scripts as $handle) {
                self::localize_script($handle);
            }
        }
    }

    Theme_Assets::init();
}