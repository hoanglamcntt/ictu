<?php /* Template Name: Home Page */ ?>
<?php get_header(); ?>
    <div class="homepage-template">
        <!-- ========================================= SLIDER ========================================= -->
        <div class="ovic-section section-slider">
            <?= do_shortcode( '[ictu_slider]' ); ?>
        </div>
        <!-- ========================================= NEWS ========================================= -->
        <?php
        $news_tab = get_theme_option( 'news_tab' );
        $tab_news = [
            [
                'title'    => @$news_tab['tab1_title'],
                'special'  => @$news_tab['tab1_special_post'],
                'category' => @$news_tab['tab1_posts_category'],
                'ids'      => @$news_tab['tab1_posts_ids'],
                'more'     => @$news_tab['tab1_posts_more'],
            ],
            [
                'title'    => @$news_tab['tab2_title'],
                'special'  => @$news_tab['tab2_special_post'],
                'category' => @$news_tab['tab2_posts_category'],
                'ids'      => @$news_tab['tab2_posts_ids'],
                'more'     => @$news_tab['tab2_posts_more'],
            ],
            [
                'title'            => @$news_tab['tab3_title'],
                'special_img'      => @$news_tab['tab3_special_img'],
                'special_link'     => @$news_tab['tab3_special_link'],
                'special_phone'    => @$news_tab['tab3_special_phone'],
                'special_facebook' => @$news_tab['tab3_special_facebook'],
                'category'         => @$news_tab['tab2_posts_category'],
                'ids'              => @$news_tab['tab2_posts_ids'],
                'more'             => @$news_tab['tab2_posts_more'],
            ]
        ];
        $access   = get_theme_option( 'access' );
        ?>
        <div class="ovic-section section-general">
            <div class="container">
                <div class="section-content">
                    <div class="ovic-tab">
                        <ul class="tab-nav">
                            <?php foreach ( $tab_news as $key => $value ) {
                                $index = $key + 1;
                                ?>
                                <li class="nav-item <?= $key === array_key_first( $tab_news ) ? 'active' : '' ?>" data-id="tab<?= esc_attr( $index ) ?>">
                                    <a href="javascript:void(0)"><?= esc_html( $value['title'] ) ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php foreach ( $tab_news as $key => $value ) {
                            $index = $key + 1;
                            ?>
                            <div class="tab-panel section-general-<?= $index ?> <?= $key === array_key_first( $tab_news ) ? 'active' : '' ?>" data-id="tab<?= esc_attr( $index ) ?>">
                                <div class="tab-content">
                                    <div class="tab-heading">
                                        <h3 class="title"></h3>
                                        <?php if ( !empty( $value['more'] ) ) {
                                            $more = $value['more'];
                                        } else {
                                            $term_link = get_term_link( (int)$value['category'] );
                                            $more      = !is_wp_error( $term_link ) ? $term_link : '#';
                                        } ?>
                                        <a href="<?= esc_url( $more ) ?>" class="link">Xem thêm<span class="icon fa fa-angle-double-right"></span></a>
                                    </div>
                                    <?php if ( $index == 3 ) { ?> <!-- tab3 -->
                                        <!-- special post -->
                                        <div class="special-post">
                                            <?php if ( !empty( $value['special_img'] ) ) { ?>
                                                <div class="special-img swiper light">
                                                    <div class="swiper-wrapper">
                                                        <?php foreach ( explode( ",", $value['special_img'] ) as $img ) { ?>
                                                            <?php if ( get_post_type( $img ) === 'attachment' ) { ?>
                                                                <div class="swiper-slide"><?= wp_get_attachment_image( $img, 'full' ); ?></div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="swiper-pagination"></div>
                                                </div>
                                            <?php } ?>
                                            <div class="special-info">
                                                <?php if ( !empty( $value['special_link'] ) ) { ?>
                                                    <div class="special-link">
                                                        <?php foreach ( $value['special_link'] as $link ) { ?>
                                                            <a href="<?= esc_url( $link['link'] ?? '#' ) ?>"><?= esc_html( $link['title'] ?? '' ) ?></a>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                                <div class="special-phone">
                                                    <p class="title"><?= esc_html__( 'Hotline', 'umeno' ) ?></p>
                                                    <?php if ( !empty( $value['special_phone'] ) ) { ?>
                                                        <?php foreach ( $value['special_phone'] as $phone ) { ?>
                                                            <a href="tel:<?= preg_replace( '/\D/', '', esc_attr( $phone['phone'] ?? '#' ) ) ?>"><?= esc_html( $phone['phone'] ?? '' ) ?></a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="special-facebook">
                                                    <p class="title"><?= esc_html__( 'Facebook', 'umeno' ) ?></p>
                                                    <a href="<?= esc_attr( $value['special_facebook'] ?? '#' ) ?>"><?= esc_html( $value['special_facebook'] ?? '' ) ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- post list -->
                                        <?= do_shortcode( '[ictu_blog classes="list-post" style="style-05" category="' . esc_attr( $value['category'] ) . '" ids="' . esc_attr( $value['ids'] ) . '" limit="7"]' ); ?>
                                    <?php } else { ?> <!-- tab other -->
                                        <!-- special post -->
                                        <?php if ( !empty( $value['special'] ) ) { ?>
                                            <?= do_shortcode( '[ictu_blog classes="special-post" style="style-04" ids="' . esc_attr( $value['special'] ) . '" limit="1" excerpt_number="50"]' ); ?>
                                        <?php } ?>
                                        <!-- post list -->
                                        <?= do_shortcode( '[ictu_blog classes="list-post" style="style-03" category="' . esc_attr( $value['category'] ) . '" ids="' . esc_attr( $value['ids'] ) . '" not_ids="' . esc_attr( $value['special'] ) . '" limit="' . ( $index == 2 ? 6 : 4 ) . '"]' ); ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="ovic-iconbox">
                        <div class="ovic-flexbox">
                            <?php foreach ( $access as $key => $value ) {
                                $index = $key + 1;
                                $link  = !empty( $value['link'] ) ? $value['link'] : '#';
                                if ( $value['link'] === '#lichtuan' ) {
                                    $latest_post = get_posts( [
                                        'category'    => 51,
                                        'numberposts' => 1,
                                    ] );
                                    if ( $latest_post ) {
                                        $post = $latest_post[0];
                                        $link = get_permalink( $post->ID );
                                    }
                                }
                                ?>
                                <a class="item" href="<?= esc_url( $link ) ?>">
                                    <div class="image">
                                        <?php if ( !empty( $value['image'] ) ) {
                                            echo wp_get_attachment_image( $value['image'], 'full' );
                                        } elseif ( $index <= 12 ) {
                                            echo '<img src="' . get_theme_file_uri( '/assets/images/access/' . $index . '.png' ) . '?>" alt="">';
//                                            echo file_get_contents( get_template_directory() . '/assets/images/svg/icon' . $index . '.svg' );
                                        } ?>
                                    </div>
                                    <?php if ( !empty( $value['title'] ) ) { ?>
                                        <div class="title"><?= esc_html( $value['title'] ) ?></div>
                                    <?php } ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========================================= IMPRESSION ========================================= -->
        <div class="ovic-section section-impression">
            <div class="container">
                <?= do_shortcode( '[ictu_heading heading="impression_title"]' ); ?>
                <?= do_shortcode( '[ictu_impression]' ); ?>
                <?= do_shortcode( '[ictu_banner]' ); ?>
            </div>
        </div>
        <!-- ========================================= PROGRAM ========================================= -->
        <div class="ovic-section section-program">
            <div class="container">
                <?= do_shortcode( '[ictu_heading heading="program_title"]' ); ?>
                <?= do_shortcode( '[ictu_program style="style-02"]' ); ?>
            </div>
        </div>
        <!-- ========================================= PRESS ========================================= -->
        <?php
        $press_button_link = get_theme_option( 'press_button_link' );
        ?>
        <div class="ovic-section section-press">
            <div class="container">
                <?= do_shortcode( '[ictu_heading heading="press_title"]' ); ?>
                <?= do_shortcode( '[ictu_press]' ); ?>
                <div class="button-wrap">
                    <a href="<?= esc_url( $press_button_link ) ?>" class="button style-02">Xem thêm</a>
                </div>
            </div>
        </div>
        <!-- ========================================= ACTIVITIES ========================================= -->
        <?php
        $activities_special_post   = get_theme_option( 'activities_special_post' );
        $activities_posts_category = get_theme_option( 'activities_posts_category' );
        $activities_posts_id       = get_theme_option( 'activities_posts_id' );
        $activities_button_link    = get_theme_option( 'activities_button_link' );
        ?>
        <div class="ovic-section section-blog-3">
            <div class="container">
                <?= do_shortcode( '[ictu_heading heading="activities_title"]' ); ?>
                <div class="section-content">
                    <?= do_shortcode( '[ictu_blog classes="special-post" style="style-04" ids="' . $activities_special_post . '" limit="1" excerpt_number="50"]' ); ?>
                    <?= do_shortcode( '[ictu_blog classes="list-post" style="style-03" list_style="grid" category="' . $activities_posts_category . '" ids="' . $activities_posts_id . '" not_ids="' . $activities_special_post . '" limit="4" excerpt_number="50"]' ); ?>
                </div>
                <div class="button-wrap">
                    <a href="<?= esc_url( $activities_button_link ) ?>" class="button style-02">Xem thêm</a>
                </div>
            </div>
        </div>
        <!-- ========================================= STUDENT ========================================= -->
        <div class="ovic-section section-student">
            <div class="container">
                <?= do_shortcode( '[ictu_student]' ); ?>
            </div>
        </div>
        <!-- ========================================= PARTNER ========================================= -->
        <div class="ovic-section section-partner">
            <div class="container">
                <?= do_shortcode( '[ictu_partner]' ); ?>
            </div>
        </div>
        <!-- ========================================= COMMENT ========================================= -->
        <div class="ovic-section section-comment light">
            <div class="container">
                <?= do_shortcode( '[ictu_heading heading="comment_title"]' ); ?>
                <?= do_shortcode( '[ictu_comment]' ); ?>
            </div>
        </div>
    </div>
<?php
get_footer();