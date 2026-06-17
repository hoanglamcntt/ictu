<?php
/**
 * Define a constant if it is not already defined.
 *
 * @param string $name Constant name.
 * @param string $value Value.
 *
 * @since 3.0.0
 *
 */
if (!function_exists('theme_maybe_define_constant')) {
    function theme_maybe_define_constant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }
}

/**
 * Wrapper for nocache_headers which also disables page caching.
 *
 * @since 3.2.4
 */
if (!function_exists('theme_nocache_headers')) {
    function theme_nocache_headers()
    {
        Theme_Ajax::set_nocache_constants();
        nocache_headers();
    }
}

if (!class_exists('Theme_Ajax')) {
    class Theme_Ajax {
        /**
         * Hook in ajax handlers.
         */
        public static function init()
        {
            add_action('init', array(__CLASS__, 'define_ajax'), 0);
            add_action('template_redirect', array(__CLASS__, 'do_theme_ajax'), 0);
            add_action('after_setup_theme', array(__CLASS__, 'add_ajax_events'));
            add_filter('wcml_multi_currency_ajax_actions', array(__CLASS__, 'add_action_to_multi_currency_ajax'), 10, 1);
        }

        /**
         * Get OVIC Ajax Endpoint.
         *
         * @param string $request Optional.
         *
         * @return string
         */
        public static function get_endpoint($request = '')
        {
            return esc_url_raw(apply_filters('theme_ajax_get_endpoint', add_query_arg('theme-ajax', $request, remove_query_arg(array(), home_url('/', 'relative'))), $request));
        }

        /**
         * Set constants to prevent caching by some plugins.
         *
         * @param mixed $return Value to return. Previously hooked into a filter.
         *
         * @return mixed
         */
        public static function set_nocache_constants($return = true)
        {
            theme_maybe_define_constant('DONOTCACHEPAGE', true);
            theme_maybe_define_constant('DONOTCACHEOBJECT', true);
            theme_maybe_define_constant('DONOTCACHEDB', true);

            return $return;
        }

        /**
         * Set OVIC AJAX constant and headers.
         */
        public static function define_ajax()
        {
            if (!empty($_GET['theme-ajax'])) {
                theme_maybe_define_constant('DOING_AJAX', true);
                theme_maybe_define_constant('OVIC_DOING_AJAX', true);
                $GLOBALS['wpdb']->hide_errors();
                if (!defined('SHORTINIT')) {
                    define('SHORTINIT', true);
                }
            }
        }

        /**
         * Send headers for OVIC Ajax Requests.
         *
         * @since 2.5.0
         */
        private static function theme_ajax_headers()
        {
            send_origin_headers();
            @header('Content-Type: text/html; charset=' . get_option('blog_charset'));
            @header('X-Robots-Tag: noindex');
            send_nosniff_header();
            theme_nocache_headers();
            status_header(200);
        }

        /**
         * Check for OVIC Ajax request and fire action.
         */
        public static function do_theme_ajax()
        {
            global $wp_query;
            if (!empty($_GET['theme-ajax'])) {
                $wp_query->set('theme-ajax', sanitize_text_field(wp_unslash($_GET['theme-ajax'])));
            }
            if (!empty($_GET['theme_raw_content'])) {
                $wp_query->set('theme_raw_content', sanitize_text_field(wp_unslash($_GET['theme_raw_content'])));
            }
            $action  = $wp_query->get('theme-ajax');
            $content = $wp_query->get('theme_raw_content');
            if ($action || $content) {
                self::theme_ajax_headers();
                if ($action) {
                    $action = sanitize_text_field($action);
                    do_action('theme_ajax_' . $action);
                    wp_die();
                } else {
                    remove_all_actions('wp_head');
                    remove_all_actions('wp_footer');
                }
            }
        }

        /**
         * Hook in methods - uses WordPress ajax handlers (admin-ajax).
         */
        public static function add_ajax_events()
        {
            // theme_EVENT => nopriv.
            $ajax_events = array(
                'content_ajax_tabs'     => true,
                'update_wishlist_count' => true,
                'delete_transients'     => false,
            );
            $ajax_events = apply_filters('theme_ajax_event_register', $ajax_events);
            foreach ($ajax_events as $ajax_event => $nopriv) {
                add_action('wp_ajax_theme_' . $ajax_event, array(__CLASS__, $ajax_event));
                if ($nopriv) {
                    add_action('wp_ajax_nopriv_theme_' . $ajax_event, array(__CLASS__, $ajax_event));
                    // OVIC AJAX can be used for frontend ajax requests.
                    add_action('theme_ajax_' . $ajax_event, array(__CLASS__, $ajax_event));
                }
            }
        }

        public function add_action_to_multi_currency_ajax($ajax_actions)
        {
            $ajax_actions[] = 'content_ajax_tabs'; // Add a AJAX action to the array

            return $ajax_actions;
        }

        public static function content_ajax_tabs()
        {
            check_ajax_referer('theme_ajax_frontend', 'security');
            if (!empty($_POST['section'])) {
                foreach ($_POST['section'] as $tag => $atts) {
                    echo theme_do_shortcode($tag, $atts);
                }
            }
            wp_die();
        }

        /**
         * Deletes all transients.
         *
         * @echo int  Number of deleted transient DB entries
         */
        public static function delete_transients()
        {
            global $wpdb;

            $count = $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_%' OR option_name LIKE '\_site\_transient\_%'");

            do_action('ovic_delete_transients', $count);

            echo absint($count);

            wp_die();
        }

        public static function update_wishlist_count()
        {
            if (function_exists('YITH_WCWL')) {
                wp_send_json(YITH_WCWL()->count_products());
            }
            wp_die();
        }
    }

    Theme_Ajax::init();
}