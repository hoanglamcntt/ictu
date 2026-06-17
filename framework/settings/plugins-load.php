<?php
/**
 * Class plugin load.
 */
require_once get_parent_theme_file_path('/framework/classes/class-tgm-plugin-activation.php');
if (!defined('ABSPATH')) {
    exit;
}
if (!function_exists('theme_plugin_load')) {
    function theme_plugin_load()
    {
        /*
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(
            array(
                'name'     => 'Ov Addon Toolkit',
                'slug'     => 'ov-addon-toolkit',
                'source'   => get_theme_file_uri('/plugins/ov-addon-toolkit.zip'),
                'required' => true,
            ),
            array(
                'name' => 'Classic Editor',
                'slug' => 'classic-editor',
            ),
            array(
                'name' => 'Classic Widgets',
                'slug' => 'classic-widgets',
            ),
        );
        /*
         * Array of configuration settings. Amend each line as needed.
         *
         * TGMPA will start providing localized text strings soon. If you already have translations of our standard
         * strings available, please help us make TGMPA even better by giving us access to these translations or by
         * sending in a pull-request with .po file(s) with the translations.
         *
         * Only uncomment the strings in the config array if you want to customize the strings.
         */
        $config = array(
            'id'           => 'theme_plugins',
            'default_path' => '',
            'menu'         => 'ictu-install-plugins',
            'parent_slug'  => 'themes.php',
            'capability'   => 'edit_theme_options',
            'has_notices'  => true,
            'dismissable'  => true,
            'dismiss_msg'  => '',
            'is_automatic' => true,
            'message'      => '',
        );
        tgmpa($plugins, $config);
    }
}
add_action('tgmpa_register', 'theme_plugin_load');
