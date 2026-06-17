<?php /* Template Name: Full Width */ ?>
<?php get_header(); ?>
    <div class="fullwidth-template">
        <!-- page banner -->
        <?php theme_page_banner_template(); ?>
        <div class="container">
            <?php while (have_posts()): ?>
                <?php the_post() ?>
                <?php the_content() ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata() ?>
        </div>
    </div>
<?php
get_footer();