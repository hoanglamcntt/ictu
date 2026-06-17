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
    <a href="#" class="backtotop"><i class="icon-top fa fa-angle-up"></i></a>
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
    <div class="section-footer-mobile-bar__container">
        <a href="<?php echo esc_url(home_url('/')) ?>" class="footer-mobile-bar__button">
            <i class="fa fa-home" aria-hidden="true"></i>
            <span><?php esc_html_e('Home', 'ictu') ?></span>
        </a>
        <a href="#" class="footer-mobile-bar__button js_open_mobile_search">
            <i class="fa fa-search" aria-hidden="true"></i>
            <span><?php esc_html_e('Search', 'ictu') ?></span>
        </a>
        <a href="#" class="footer-mobile-bar__button js_footer_mobile_button_toggle_sidebar">
            <i class="fa fa-sliders" aria-hidden="true"></i>
            <span><?php esc_html_e('Sidebar', 'ictu') ?></span>
        </a>
        <a href="#" class="footer-mobile-bar__button menu-toggle">
            <i class="fa fa-bars" aria-hidden="true"></i>
            <span><?php esc_html_e('Menu', 'ictu') ?></span>
        </a>
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
<!-- Load Facebook SDK for JavaScript -->
<!--<div id="fb-root"></div>-->
<!--<script>( function ( d, s, id ) {-->
<!--        var js, fjs = d.getElementsByTagName( s )[ 0 ];-->
<!--        if ( d.getElementById( id ) ) return;-->
<!--        js     = d.createElement( s );-->
<!--        js.id  = id;-->
<!--        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";-->
<!--        fjs.parentNode.insertBefore( js, fjs );-->
<!--    }( document, 'script', 'facebook-jssdk' ) );-->
<!--</script>-->
<!---->
<!-- Load Facebook SDK for JavaScript -->
<!--<div id="fb-root"></div>-->
<!--<script async defer crossorigin="anonymous"-->
<!--        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1-->
<!--             &version={graph-api-version}-->
<!--             &appId={your-facebook-app-id}-->
<!--             &autoLogAppEvents=1"-->
<!--        nonce="FOKrbAYI">-->
<!--</script>-->
</html>
