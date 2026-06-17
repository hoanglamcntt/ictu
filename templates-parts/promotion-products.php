<?php
/**
 * Template Shop Banner
 *
 * @return string
 */
?>
<?php
$page = get_theme_option( 'promotion_products' );
if (is_product_category()){
    $cate = get_queried_object();
    $term_options = get_term_meta($cate->term_id, '_custom_taxonomy_options', true);
    if (!empty($term_options['promotion_products'])){
        $page = $term_options['promotion_products'];
    }else{
        $page = 0;
    }
}
if ( ! empty( $page ) ):
	?>
    <div class="promotion-products">
        <?php theme_edit_link( $page ); ?>
        <?php
        if ( class_exists( '\Elementor\Plugin' ) ) {
            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $page );
        } else {
            $post_id = get_post( $page );
            $content = $post_id->post_content;
            $content = apply_filters( 'the_content', $content );
            $content = str_replace( ']]>', ']]>', $content );
            echo wp_specialchars_decode( $content );
        }
        ?>
    </div>
<?php endif; ?>
