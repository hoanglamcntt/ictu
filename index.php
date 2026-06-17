<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link       https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    WordPress
 * @subpackage Theme
 * @since      1.0
 * @version    1.0
 */
get_header();
$page_layout  = theme_page_layout();
$main_class   = [ "container", "site-content", "use-page-head--1" ];
$path         = 'blog/blog-content/content';
$has_sidebar  = $page_layout['layout'] != 'full';
$main_class[] = $has_sidebar ? "sidebar-{$page_layout['layout']}" : 'no-sidebar';
$format       = 'grid';
$post_options = array();
if ( is_single() ) {
    $main_class[] = 'single-post-page';
    $path         = 'blog/blog-single/content';
    $post_options = get_post_meta( get_the_ID(), '_custom_metabox_post_options', true );
    if ( !empty( $post_options['type'] ) ) {
        $format = $post_options['type'];
    }
} else {
    $main_class[] = 'blog-page';
    $term         = get_queried_object();
    if ( $term && $term->taxonomy === 'category' ) {
        $custom_template = get_term_meta( $term->term_id, 'blog_layout', true );
        if ( $custom_template ) {
            $format = $custom_template;
        }
    }
}
?>
<?php theme_page_banner_template(); ?>
    <div class="breadcrumb-container" style="background-image: url(<?= esc_url( get_theme_file_uri( '/assets/images/bg.jpg' ) ) ?>);">
        <div class="container"><?php theme_breadcrumb(); ?></div>
    </div>
    <!-- .site-content-contain -->
    <div id="content" class="<?php echo implode( ' ', $main_class ); ?>">
<!--        --><?php //if ( is_single() ) { ?>
<!--            <div class="single-post__head">-->
<!--                --><?php //the_title( '<h1 class="single-post__title">', '</h1>' ); ?>
<!--                <span class="post-date"><span class="icon fa fa-calendar"></span>--><?php //echo get_the_date( 'd/m/Y' ); ?><!--</span>-->
<!--            </div>-->
<!--        --><?php //} ?>
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php
                if ( have_posts() ) {
                    get_template_part( "templates/{$path}", $format, $post_options );
                    wp_reset_postdata();
                } else {
                    get_template_part( 'content', 'none' );
                }
                ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php if ( $has_sidebar && $format !== 'calendar' ) : ?>
            <aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Page Sidebar', 'ictu' ); ?>">
                <?php do_action( 'theme_before_dynamic_sidebar' ); ?>
                <?php dynamic_sidebar( $page_layout['sidebar'] ); ?>
                <?php do_action( 'theme_after_dynamic_sidebar' ); ?>
            </aside><!-- #secondary -->
        <?php endif; ?>
    </div><!-- .site-content-contain -->
<?php
get_footer();
