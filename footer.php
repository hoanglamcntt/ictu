<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Intn
 * @since 1.0
 * @version 1.2
 */
?>
<?php if (get_theme_option('enable_back_to_top') == 1): ?>
    <a href="#" class="backtotop"><i class="icon-top main-icon-chevrons-up"></i></a>
<?php endif; ?>
<?php do_action('ovic_footer_content_after'); ?>
<?php //do_action( 'ovic_footer_content' ); ?>
<?php do_action('theme_footer_content'); ?>
<?php theme_popup_newsletter(); ?>
</div><!-- #page -->
<div class="template-search-for-mobile">
    <?php theme_header_search_template(); ?>
</div>
<div class="section-footer-mobile-bar">
    <div class="inner">
        <a href="<?php echo esc_url(home_url('/')) ?>">
            <i class="fa fa-home" aria-hidden="true"></i>
            <span><?php esc_html_e('Home', 'ictu') ?></span>
        </a>
        <a href="#" class="js_open_mobile_search">
            <i class="fa fa-search" aria-hidden="true"></i>
            <span><?php esc_html_e('Search', 'ictu') ?></span>
        </a>
<!--        <a href="#" class="js_footer_mobile_button_toggle_sidebar">-->
<!--            <i class="fa fa-sliders" aria-hidden="true"></i>-->
<!--            <span>--><?php //esc_html_e('Sidebar', 'ictu') ?><!--</span>-->
<!--        </a>-->
        <a href="#" class="menu-toggle">
            <i class="fa fa-bars" aria-hidden="true"></i>
            <span><?php esc_html_e('Menu', 'ictu') ?></span>
        </a>
        <?php if (get_theme_option('enable_back_to_top') == 1): ?>
            <a href="#" class="backtotop">
                <i class="icon-top main-icon-chevrons-up"></i>
                <span><?php esc_html_e('Top', 'ictu') ?></span>
            </a>
        <?php endif; ?>
    </div>
</div>
<div class="ictu-social-button">
    <a class="button dang-ky" href="https://tuyensinh.ictu.edu.vn" target="_blank">
        <span class="text"><?php echo esc_html__( 'Tuyển sinh', 'ictu' ) ?></span>
    </a>
    <a class="button tham-quan" href="https://thamquan.ictu.edu.vn/" target="_blank">
        <span class="text"><?php echo esc_html__( 'Tham quan', 'ictu' ) ?></span>
    </a>
</div>
<?php wp_footer(); ?>
</body>
</html>
