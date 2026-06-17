<!-- ========================================= NEWS ========================================= -->
<div class="ovic-section section-blog">
    <div class="container">
        <?= do_shortcode( '[ictu_heading heading="news_title"]' ); ?>
        <div class="block-1">
            <?= do_shortcode( '[ictu_blog limit="4"]' ); ?>
        </div>
        <div class="block-2">
            <?= do_shortcode( '[ictu_blog style="style-02" limit="4"]' ); ?>
        </div>
        <div class="button-wrap">
            <a href="<?= esc_url( get_theme_option( 'news_button_link' ) ) ?>" class="button style-02">Xem thêm</a>
        </div>
    </div>
</div>
<?php
$tab_news = [
    [
        'title'    => 'Tiêu điểm',
//                'category' => 73,
        'category' => 5,
    ],
    [
        'title'    => 'Tin tức - Sự kiện',
//                'category' => 22,
        'category' => 6,
    ],
    [
        'title'    => 'Thông báo',
//                'category' => 4,
        'category' => 7,
    ]
];
?>
<div class="ovic-section section-general">
    <div class="container">
        <div class="block-1">
            <div class="ovic-tab">
                <ul class="tab-nav">
                    <?php foreach ( $tab_news as $key => $value ) { ?>
                        <li class="nav-item <?= $key === array_key_first( $tab_news ) ? 'active' : '' ?>" data-id="tab<?= esc_attr( $key ) ?>">
                            <a href="javascript:void(0)"><?= esc_html( $value['title'] ) ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <?php foreach ( $tab_news as $key => $value ) {
                    $term_link = get_term_link( (int)$value['category'] );
                    ?>
                    <div class="tab-content <?= $key === array_key_first( $tab_news ) ? 'active' : '' ?>" data-id="tab<?= esc_attr( $key ) ?>">
                        <div class="tab-heading">
                            <?php if ( !is_wp_error( $term_link ) ) {
                                echo '<a href="' . esc_url( $term_link ) . '" class="link">Xem thêm<span class="icon fa fa-angle-double-right"></span></a>';
                            } ?>
                        </div>
                        <?= do_shortcode( '[ictu_blog style="style-03" list_style="grid" category="' . $value['category'] . '" limit="5" excerpt_number="50"]' ); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="block-2">
            <?= do_shortcode( '[ictu_banner banner="news_banner"]' ); ?>
        </div>
    </div>
</div>

<!-- ========================================= VISION ========================================= -->
<?php
$vision_bg     = get_theme_option( 'vision_bg' );
$vision_bg_url = wp_get_attachment_image_url( $vision_bg, 'full' );
?>
<div class="ovic-section section-vision light" style="background-image: url(<?= esc_url( $vision_bg_url ) ?>);">
    <div class="container">
        <?= do_shortcode( '[ictu_heading heading="vision_title"]' ); ?>
        <?= do_shortcode( '[ictu_vision]' ); ?>
    </div>
</div>
<!-- ========================================= DISCOVER ========================================= -->
<div class="ovic-section section-discover">
    <div class="container">
        <?= do_shortcode( '[ictu_heading heading="discover_title"]' ); ?>
        <?= do_shortcode( '[ictu_discover]' ); ?>
    </div>
</div>