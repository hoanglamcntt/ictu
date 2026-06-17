<?php
/**
 * Name: program shortcode
 **/
function ictu_program_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'style'   => 'style-01',
        'program' => 'program',
    ), $atts, 'ictu_program' );

    $classes = 'ovic-program ovic-flexbox ' . $atts['style'];
    if ( $atts['style'] == 'style-01' ) {
        $classes .= ' ovic-vision';
    }

    $program = get_theme_option( $atts['program'] );

    ob_start();
    ?>

    <div class="<?= esc_attr( $classes ) ?>">
        <?php if ( !empty( $program ) ) { ?>
            <?php foreach ( $program as $key => $item ) {
                $index = $key + 1;
                ?>
                <div class="item">
                    <?php if ( $atts['style'] == 'style-02' ) { ?>
                        <div class="head">
                            <div class="thumb">
                                <?php if ( !empty( $item['image'] ) ) {
                                    echo wp_get_attachment_image( $item['image'], 'full' );
                                } else {
                                    echo '<img src="' . get_theme_file_uri( '/assets/images/nganh/nganh-' . $index . '.png' ) . '?>" alt="">';
                                } ?>
                            </div>
                            <?php if ( !empty( $item['title'] ) ) { ?>
                                <h3 class="title"><?= esc_html( $item['title'] ) ?></h3>
                            <?php } ?>
                        </div>
                        <?php if ( !empty( $item['text'] ) ) { ?>
                            <ul class="content">
                                <?php foreach ( $item['text'] as $text ) { ?>
                                    <li><a href="<?= !empty( $text['link'] ) ? $text['link'] : '#' ?>"><span class="deco"></span><?= esc_html( $text['text'] ) ?></a></li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="thumb">
                            <?php if ( !empty( $item['image'] ) ) { ?>
                                <?= wp_get_attachment_image( $item['image'], 'full' ) ?>
                            <?php } ?>
                            <?php if ( !empty( $item['text'] ) ) { ?>
                                <ul class="content">
                                    <?php foreach ( $item['text'] as $text ) { ?>
                                        <li><a href="<?= !empty( $text['link'] ) ? $text['link'] : '#' ?>"><?= esc_html( $text['text'] ) ?></a></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                        <h3 class="title"><?= !empty( $item['title'] ) ? esc_html( $item['title'] ) : '' ?></h3>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_program', 'ictu_program_shortcode' );