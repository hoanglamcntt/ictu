<?php defined( 'ABSPATH' ) || exit;
/**
 * Name:  Header style 01
 **/
?>
<header id="header" class="header style-01">
    <div class="header-top light">
<!--        <div class="container">-->
            <div class="header-top__inner">
                <div class="header-top__left">
                    <div class="header-top__logo">
                        <?php get_theme_logo( true ); ?>
                    </div>
                </div>
                <div class="header-top__right-contact">
                    <div class="header-control">
                        <div class="header-middle__block-search">
                            <?php theme_header_search_template(); ?>
                            <a href="#" class="open-mobile-search js_toggle_desktop-search">
                                <svg aria-hidden="true" class="icon icon-search" viewBox="0 0 37 40">
                                    <path d="M35.6 36l-9.8-9.8c4.1-5.4 3.6-13.2-1.3-18.1-5.4-5.4-14.2-5.4-19.7 0-5.4 5.4-5.4 14.2 0 19.7 2.6 2.6 6.1 4.1 9.8 4.1 3 0 5.9-1 8.3-2.8l9.8 9.8c.4.4.9.6 1.4.6s1-.2 1.4-.6c.9-.9.9-2.1.1-2.9zm-20.9-8.2c-2.6 0-5.1-1-7-2.9-3.9-3.9-3.9-10.1 0-14C9.6 9 12.2 8 14.7 8s5.1 1 7 2.9c3.9 3.9 3.9 10.1 0 14-1.9 1.9-4.4 2.9-7 2.9z"></path>
                                </svg>
                                <svg aria-hidden="true" class="icon icon-close" viewBox="0 0 1000 1000">
                                    <g>
                                        <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)">
                                            <path d="M630.2,4489.8L100,3957.7l1917.9-1917.9L3937.7,120L2017.9-1799.8L100-3717.7l530.2-532.1l532.1-530.2l1917.9,1917.9L5000-942.3l1919.8-1919.8L8837.7-4780l532.1,530.2l530.2,532.1L7982.1-1799.8L6062.3,120l1919.8,1919.8L9900,3957.7l-530.2,532.1L8837.7,5020L6919.8,3102.1L5000,1182.3L3080.2,3102.1L1162.3,5020L630.2,4489.8z"/>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                        </div>
                        <div class="header-middle__block-mobile-menu">
                            <a href="#" class="open-mobile-search js_open_mobile_search">
                                <svg aria-hidden="true" class="icon icon-search" viewBox="0 0 37 40">
                                    <path d="M35.6 36l-9.8-9.8c4.1-5.4 3.6-13.2-1.3-18.1-5.4-5.4-14.2-5.4-19.7 0-5.4 5.4-5.4 14.2 0 19.7 2.6 2.6 6.1 4.1 9.8 4.1 3 0 5.9-1 8.3-2.8l9.8 9.8c.4.4.9.6 1.4.6s1-.2 1.4-.6c.9-.9.9-2.1.1-2.9zm-20.9-8.2c-2.6 0-5.1-1-7-2.9-3.9-3.9-3.9-10.1 0-14C9.6 9 12.2 8 14.7 8s5.1 1 7 2.9c3.9 3.9 3.9 10.1 0 14-1.9 1.9-4.4 2.9-7 2.9z"></path>
                                </svg>
                            </a>
                            <a href="#" class="menu-bar menu-toggle"><span class="icon menu-bar"><span></span><span></span><span></span></span></a>
                        </div>
                    </div>
                    <?php theme_header_contacts_template(); ?>
                </div>
            </div>
<!--        </div>-->
    </div>
    <div class="header-middle light">
<!--        <div class="container">-->
            <div class="header-middle__inner">
                <div class="header-middle__block-primary-menu">
                    <?php get_sticky_logo(); ?>
                    <?php if ( has_nav_menu( 'primary' ) ) {
                        wp_nav_menu(
                            array(
                                'menu'            => 'primary',
                                'theme_location'  => 'primary',
                                'depth'           => 3,
                                'container'       => '',
                                'container_class' => '',
                                'container_id'    => '',
                                'menu_class'      => 'theme-nav main-menu header-primary-menu',
                            )
                        );
                    } ?>
                </div>
            </div>
<!--        </div>-->
    </div>
</header>
