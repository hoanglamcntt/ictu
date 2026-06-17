<?php
/**
 * Template Documents Layout
 *
 * @return string
 * @var $atts
 * @var $item_icon
 *
 */
?>
<?php
if ( get_post_type() == 'baochi' ) {
    $categories = get_the_terms( get_the_ID(), 'loaibaochi' );
    $permalink  = get_post_meta( get_the_ID(), 'custom_baochi_link', true );
    $target     = '_blank';
} else {
    $categories = get_the_category();
    $permalink  = get_the_permalink();
    $target     = '_self';
}
?>
<?php if ( has_post_thumbnail() ) : ?>
    <div class="post-thumb">
        <a href="<?php echo esc_url( $permalink ) ?>" target="<?php echo $target ?>" class="thumb-link">
            <?php
            $thumb = theme_resize_image( get_post_thumbnail_id(), 640, 360, true, true );
            echo wp_specialchars_decode( $thumb['img'] );
            ?>
        </a>
    </div>
<?php endif; ?>
<div class="post-info">
    <?php if ( !empty( $categories ) && !is_wp_error( $categories ) && !empty( $categories[0] ) ) : ?>
        <div class="post-category">
            <?php echo !empty( $categories[0]->name ) ? $categories[0]->name : '' ?>
        </div>
    <?php endif; ?>
    <h2 class="post-title"><a href="<?php echo esc_url( $permalink ) ?>" target="<?php echo $target ?>"><?php echo get_the_title() ?></a></h2>
</div>