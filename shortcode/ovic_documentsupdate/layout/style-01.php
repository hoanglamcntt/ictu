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
<?php $post_options = get_post_meta( get_the_ID(), '_custom_metabox_post_options', true ) ?>
<div class="post-item-type--document__head-name">
    <?php the_title( '<h2 class="post-item-type--document__post-name">', '</h2>' ) ?>
    <?php if ( $post_options && isset( $post_options['doc_code'] ) ): ?>
        <p class="post-item-type--document__post-code"><?php echo esc_html( $post_options['doc_code'] ) ?></p>
    <?php endif; ?>
</div>
<?php if ( !empty($post_options['doc_date_sight']) ) { ?>
    <div class="post-item-type--document__wrap-date">
        <span class="post-item-type--document__date"><?php echo esc_html( $post_options['doc_date_sight'] ) ?></span>
    </div>
<?php } ?>
<div class="post-item-type--document__wrap-link">
    <span class="post-item-type--document__link">
        <i class="<?php echo esc_attr( $item_icon ) ?>" aria-hidden="true"></i>
    </span>
</div>
<a href="<?php echo get_the_permalink() ?>" class="post-item-type--link-cover__link"></a>