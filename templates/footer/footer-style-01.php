<?php defined( 'ABSPATH' ) || exit; ?>
<?php

$footer_bg          = get_theme_option( 'footer_bg', '' );
$university_name    = get_theme_option( 'university_name', '' );
$university_address = get_theme_option( 'university_address', '' );
$university_phone   = get_theme_option( 'university_phone', '' );
$university_fax     = get_theme_option( 'university_fax', '' );
$chief_editor_label = get_theme_option( 'chief_editor_label', '' );
$chief_editor_name  = get_theme_option( 'chief_editor_name', '' );
$chief_editor_phone = get_theme_option( 'chief_editor_phone', '' );
$chief_editor_email = get_theme_option( 'chief_editor_email', '' );
//$chief_editor_note      = get_theme_option( 'chief_editor_note', '' );
//$chief_editor_note_link = get_theme_option( 'chief_editor_note_link', '#' );
$copyright_text     = get_theme_option( 'copyright_text', '© copyright 2021' );
$organizations_logo = get_theme_option( 'organizations_logo', '' );
$gallery_ids        = $organizations_logo ? explode( ',', $organizations_logo ) : [];
$bg                 = !empty( $footer_bg ) ? wp_get_attachment_image_url( $footer_bg, 'full' ) : '';
?>
<footer id="footer" class="footer footer-style-01" style="background-image: url(<?= !empty( $bg ) ? esc_url( $bg ) : '' ?>);">
    <div class="container">
        <div class="footer-inner footer-inner--top">
            <div class="column-1">
                <?php if ( $university_name ) : ?>
                    <h4 class="footer-content__block-title"><?= esc_html( $university_name ) ?></h4>
                <?php endif; ?>
                <?php if ( $university_address ) : ?>
                    <p class="footer-content__text"><span class="icon main-icon-home"></span><?= esc_html( $university_address ) ?></p>
                <?php endif; ?>
                <?php if ( $university_phone ) : ?>
                    <p class="footer-content__text"><span class="icon main-icon-phone"></span><?= esc_html( $university_phone ) ?></p>
                <?php endif; ?>
                <?php if ( $university_fax ) : ?>
                    <p class="footer-content__text"><span class="icon main-icon-printer"></span><?= esc_html( $university_fax ) ?></p>
                <?php endif; ?>
                <?php if ( $chief_editor_label ) : ?>
                    <h4 class="footer-content__block-title"><?= esc_html( $chief_editor_label ) ?></h4>
                <?php endif; ?>
                <?php if ( $chief_editor_name ) : ?>
                    <p class="footer-content__text"><span class="icon main-icon-user"></span><?= esc_html( $chief_editor_name ) ?></p>
                <?php endif; ?>
                <?php if ( $chief_editor_phone ) : ?>
                    <p class="footer-content__text"><span class="icon main-icon-phone"></span><?= esc_html( $chief_editor_phone ) ?></p>
                <?php endif; ?>
                <?php if ( $chief_editor_email ) : ?>
                    <p class="footer-content__text"><span class="icon main-icon-mail"></span><?= esc_html( $chief_editor_email ) ?></p>
                <?php endif; ?>
                <!--                --><?php //if ( $chief_editor_note ) : ?>
                <!--                    <p class="footer-content__text content-note"><a href="--><?php //= esc_url( $chief_editor_note_link ) ?><!--" target="_blank">--><?php //= esc_html( $chief_editor_note ) ?><!--</a></p>-->
                <!--                --><?php //endif; ?>
                <?php if ( !empty( $gallery_ids ) ) : ?>
                    <ul class="footer-list-brands">
                        <?php foreach ( $gallery_ids as $id ): ?>
                            <?php $url = wp_get_attachment_url( $id ) ?>
                            <li class="footer-list-brands__elm">
                                <figure>
                                    <img src="<?= !empty( $url ) ? esc_url( $url ) : esc_url( 'https://placeholder.pics/svg/88x88/dedede/dedede' ); ?>" alt="">
                                </figure>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="column-2">
                <?php if ( has_nav_menu( 'footer1' ) ) : ?>
                    <h4 class="footer-content__block-title"><?= wp_get_nav_menu_name( 'footer1' ); ?></h4>
                    <?php wp_nav_menu( [
                        'menu'            => 'footer1',
                        'theme_location'  => 'footer1',
                        'depth'           => 1,
                        'container'       => '',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'footer-menu'
                    ] ); ?>
                <?php endif; ?>
                <?php if ( has_nav_menu( 'footer2' ) ) : ?>
                    <h4 class="footer-content__block-title"><?= wp_get_nav_menu_name( 'footer2' ); ?></h4>
                    <?php wp_nav_menu( [
                        'menu'            => 'footer2',
                        'theme_location'  => 'footer2',
                        'depth'           => 1,
                        'container'       => '',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'footer-menu'
                    ] ); ?>
                <?php endif; ?>
            </div>
            <div class="column-3">
                <?php if ( has_nav_menu( 'footer3' ) ) : ?>
                    <h4 class="footer-content__block-title"><?= wp_get_nav_menu_name( 'footer3' ); ?></h4>
                    <?php wp_nav_menu( [
                        'menu'            => 'footer3',
                        'theme_location'  => 'footer3',
                        'depth'           => 1,
                        'container'       => '',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'footer-menu'
                    ] ); ?>
                <?php endif; ?>
                <?php if ( has_nav_menu( 'footer4' ) ) : ?>
                    <h4 class="footer-content__block-title"><?= wp_get_nav_menu_name( 'footer4' ); ?></h4>
                    <?php wp_nav_menu( [
                        'menu'            => 'footer4',
                        'theme_location'  => 'footer4',
                        'depth'           => 1,
                        'container'       => '',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'footer-menu'
                    ] ); ?>
                <?php endif; ?>
            </div>
            <div class="column-4">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10488.040241453431!2d105.8011320477466!3d21.583324655281345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31352738b1bf08a3%3A0x515f4860ede9e108!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgVGjDtG5nIHRpbiAmIFRydXnhu4FuIHRow7RuZyBUaMOhaSBOZ3V5w6pu!5e0!3m2!1svi!2s!4v1780630045356!5m2!1svi!2s" width="450" height="420" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <p class="footer-copyright__text"><?= wp_specialchars_decode( $copyright_text ) ?></p>
    </div>
</footer>