<?php defined( 'ABSPATH' ) || exit; ?>
<?php

$university             = get_theme_option( 'university_name', '' );
$university_address     = get_theme_option( 'university_address', '' );
$university_phone       = get_theme_option( 'university_phone', '' );
$university_fax         = get_theme_option( 'university_fax', '' );
$chief_editor_label     = get_theme_option( 'chief_editor_label', '' );
$chief_editor_name      = get_theme_option( 'chief_editor_name', '' );
$chief_editor_phone     = get_theme_option( 'chief_editor_phone', '' );
$chief_editor_email     = get_theme_option( 'chief_editor_email', '' );
$chief_editor_note      = get_theme_option( 'chief_editor_note', '' );
$chief_editor_note_link = get_theme_option( 'chief_editor_note_link', '#' );
$copyright_text         = get_theme_option( 'copyright_text', '© copyright 2021' );
$organizations_logo     = get_theme_option( 'organizations_logo', '' );
$gallery_ids            = $organizations_logo ? explode( ',', $organizations_logo ) : [];
?>
<footer id="footer" class="footer footer-style-01">
    <div class="container">
        <div class="footer-inner footer-inner--top">
            <div class="footer-content__left">
                <?php if ( $university ) : ?>
                    <h4 class="footer-content__block-title"><?php echo esc_html( $university ) ?></h4>
                <?php endif; ?>
                <?php if ( $university_address ) : ?>
                    <p class="footer-content__text"><?php echo esc_html( $university_address ) ?></p>
                <?php endif; ?>
                <?php if ( $university_phone ) : ?>
                    <p class="footer-content__text"><?php echo esc_html( $university_phone ) ?></p>
                <?php endif; ?>
                <?php if ( $university_fax ) : ?>
                    <p class="footer-content__text"><?php echo esc_html( $university_fax ) ?></p>
                <?php endif; ?>
                <?php if ( $chief_editor_label ) : ?>
                    <h4 class="footer-content__block-title"><?php echo esc_html( $chief_editor_label ) ?></h4>
                <?php endif; ?>
                <?php if ( $chief_editor_name ) : ?>
                    <p class="footer-content__text"><?php echo esc_html( $chief_editor_name ) ?></p>
                <?php endif; ?>
                <?php if ( $chief_editor_phone ) : ?>
                    <p class="footer-content__text"><?php echo esc_html( $chief_editor_phone ) ?></p>
                <?php endif; ?>
                <?php if ( $chief_editor_email ) : ?>
                    <p class="footer-content__text"><?php echo esc_html( $chief_editor_email ) ?></p>
                <?php endif; ?>
                <?php if ( $chief_editor_note ) : ?>
                    <p class="footer-content__text content-note"><a href="<?php echo esc_url( $chief_editor_note_link ) ?>" target="_blank"><?php echo esc_html( $chief_editor_note ) ?></a></p>
                <?php endif; ?>
                <?php if ( !empty( $gallery_ids ) ) : ?>
                    <ul class="footer-list-brands">
                        <?php foreach ( $gallery_ids as $id ): ?>
                            <?php $url = wp_get_attachment_url( $id ) ?>
                            <li class="footer-list-brands__elm">
                                <figure>
                                    <img src="<?php echo $url ? esc_url( $url ) : esc_url( 'https://placeholder.pics/svg/88x88/dedede/dedede' ); ?>" alt="">
                                </figure>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="footer-content__center">
                <?php if ( has_nav_menu( 'footer-center' ) ) : ?>
                    <h4 class="footer-content__block-title"><?php echo wp_get_nav_menu_name( 'footer-center' ); ?></h4>
                    <?php wp_nav_menu(
                        array(
                            'menu'            => 'footer-center',
                            'theme_location'  => 'footer-center',
                            'depth'           => 1,
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => 'footer-menu footer-menu__center'
                        )
                    );
                    ?>
                <?php endif; ?>
                <?php if ( has_nav_menu( 'footer-center-bottom' ) ) : ?>
                    <h4 class="footer-content__block-title"><?php echo wp_get_nav_menu_name( 'footer-center-bottom' ); ?></h4>
                    <?php wp_nav_menu(
                        array(
                            'menu'            => 'footer-center-bottom',
                            'theme_location'  => 'footer-center-bottom',
                            'depth'           => 1,
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => 'footer-menu footer-menu__center footer-menu__center--bottom'
                        )
                    );
                    ?>
                <?php endif; ?>
            </div>
            <div class="footer-content__right">
                <?php if ( has_nav_menu( 'footer-right' ) ) : ?>
                    <h4 class="footer-content__block-title"><?php echo wp_get_nav_menu_name( 'footer-right' ); ?></h4>
                    <?php wp_nav_menu(
                        array(
                            'menu'            => 'footer-right',
                            'theme_location'  => 'footer-right',
                            'depth'           => 1,
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => 'footer-menu footer-menu__right'
                        )
                    );
                    ?>
                <?php endif; ?>
                <?php if ( has_nav_menu( 'footer-right-bottom' ) ) : ?>
                    <h4 class="footer-content__block-title"><?php echo wp_get_nav_menu_name( 'footer-right-bottom' ); ?></h4>
                    <?php wp_nav_menu(
                        array(
                            'menu'            => 'footer-right-bottom',
                            'theme_location'  => 'footer-right-bottom',
                            'depth'           => 1,
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => 'footer-menu footer-menu__right footer-menu__right--bottom'
                        )
                    );
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <p class="footer-copyright__text"><?php echo wp_specialchars_decode( $copyright_text ) ?></p>
    </div>
</footer>