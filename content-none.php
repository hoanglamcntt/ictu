<?php
wp_enqueue_style( 'blog-archive' );
$title      = '';
$excerpt    = '';
$head_title = '';
if ( is_home() && current_user_can( 'publish_posts' ) ) {
    $title   = esc_html__( 'Ready to publish your first post?', 'ictu' );
    $excerpt = '<a href="' . esc_url( admin_url( 'post-new.php' ) ) . '">' . esc_html__( 'Get started here', 'ictu' ) . '</a>';
} else if ( is_search() ) {
    $title      = __( "We couldn't find your content", 'ictu' );
    $head_title = __( 'Search results', 'ictu' );
} else {
    $term       = get_queried_object();
    $head_title = $term->name;
    $title      = __( 'This archive has no posts', 'ictu' );
    $excerpt    = __( 'We has no found your content', 'ictu' ) . ' <a href="' . home_url() . '" class="link-to-home">Back to home</a> or contact administrator';
}
?>
<div class="blog-content blog-standard response-content article-build-in-list">
    <div class="single-post-head-block">
        <h2 class="single-post-head-block__title"><?php echo esc_html( $head_title ) ?></h2>
    </div>
    <div class="theme-list-layout">
        <div class="wrap-blogs">
            <article <?php post_class( 'post-item elm-content-list post-content-none' ); ?>>
                <div class="post-inner">
                    <div class="thumb-wrap text-center">
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bg-content-none.png' ) ); ?>" alt="" class="attachment-post-thumbnail size-post-thumbnail wp-post-image wp-post-image">
                    </div>
                    <div class="post-info text-center">
                        <h3 class="post-title"><span><?php echo esc_html( $title ); ?></span></h3>
                        <?php if ( $excerpt ): ?>
                            <p class="post-excerpt"><?php echo wp_specialchars_decode( $excerpt ) ?></p>
                        <?php endif; ?>
                    </div>
            </article>
        </div>
        <?php theme_post_pagination(); ?>
    </div>
</div>

