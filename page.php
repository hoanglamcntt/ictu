<?php
get_header();
$page_layout      = theme_page_layout();
$has_sidebar      = $page_layout['layout'] != 'full';
/* CLASS */
$main_class   = array("container", "site-content");
$main_class[] = $has_sidebar ? "sidebar-{$page_layout['layout']}" : 'no-sidebar';
if (!empty($meta_class)) {
    $main_class[] = $meta_class;
}
?>
    <!-- page banner -->
<?php theme_page_banner_template(); ?>
    <div class="breadcrumb-container" style="background-image: url(<?= esc_url( get_theme_file_uri( '/assets/images/bg.jpg' ) ) ?>);">
        <div class="container"><?php theme_breadcrumb(); ?></div>
    </div>
    <!-- .site-content-contain -->
    <div id="content" class="<?php echo implode(' ', $main_class); ?>">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-content">
                            <?php
                            the_content();
                            wp_link_pages(
                                array(
                                    'before'      => '<div class="post-pagination"><span class="title">' . esc_html__('Pages:', 'ictu') . '</span>',
                                    'after'       => '</div>',
                                    'link_before' => '<span>',
                                    'link_after'  => '</span>',
                                )
                            );
                            ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post-## -->
                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                endwhile; // End of the loop.
                ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php if ($has_sidebar) : ?>
            <aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e('Page Sidebar', 'ictu'); ?>">
                <?php do_action('theme_before_dynamic_sidebar'); ?>
                <?php dynamic_sidebar($page_layout['sidebar']); ?>
                <?php do_action('theme_after_dynamic_sidebar'); ?>
            </aside><!-- #secondary -->
        <?php endif; ?>
    </div><!-- .site-content-contain -->

<?php
get_footer();
