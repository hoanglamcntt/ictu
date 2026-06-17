<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Intn
 * @since 1.0
 * @version 1.0
 */

get_header();
?>
    <div id="content" class="container site-content error-404" style="display: block; padding: 30px 0 60px;">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <section class=" not-found">
                    <h1 class="title"><?php echo esc_html__( 'We are sorry.', 'ictu' ); ?></h1>
                    <h3 class="subtitle"><?php echo esc_html__( 'The page you\'ve requested is not available.', 'ictu' ); ?></h3>
                    <figure>
                        <img src="<?php echo get_theme_file_uri( '/assets/images/404.png' ); ?>" alt="404">
                    </figure>
                    <a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php echo esc_html__( 'Return to home', 'ictu' ); ?>
                    </a>
                </section><!-- .error-404 -->
            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->
<?php
get_footer();
