<?php
/**
 * Name: impression shortcode
 **/
function ictu_impression_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'impression' => 'impression',
    ), $atts, 'ictu_impression' );

    $impression = get_theme_option( $atts['impression'] );

    $placeholder    = get_theme_file_uri( '/assets/images/placeholder-626x400.png' );
    $data           = array(
        'placeholder' => esc_url( $placeholder ),
        'active'      => '',
        'galleries'   => array(),
    );
    $carousel_cells = array();
    $years          = array();
    $navs           = array();
    $descriptions   = array();

    $count_teachers = array();
    $count_programs = array();
    $count_students = array();
    $count_labs     = array();

    $img_teacher  = get_theme_file_uri( '/assets/images/count-teacher.jpg' );
    $img_programs = get_theme_file_uri( '/assets/images/count-programs.jpg' );
    $img_student  = get_theme_file_uri( '/assets/images/count-students.jpg' );
    $img_labs     = get_theme_file_uri( '/assets/images/count-labs.jpg' );

    $text_trans_teacher  = __( 'CBGV trình độ cao', 'ictu' );
    $text_trans_programs = __( 'Ngành đào tạo', 'ictu' );
    $text_trans_students = __( 'Quy mô đào tạo ĐHCQ', 'ictu' );
    $text_trans_labs     = __( 'Diện tích sàn XD', 'ictu' );

    if ( !empty( $impression ) ) {
        $last_position = count( $impression ) - 1;
        foreach ( $impression as $index => $node ) {
            $id       = $index + 1;
            $elm_id   = esc_attr( $id );
            $_y_class = ( $index === $last_position ) ? 'ovic-rty__year --element-activated' : 'ovic-rty__year';
            $_n_class = ( $index === $last_position ) ? 'btn ovic-impression__btn-nav --element-activated' : 'btn ovic-impression__btn-nav';
            $_d_class = ( $index === $last_position ) ? 'ovic-rty__desc --element-activated intro-y' : 'ovic-rty__desc intro-y';

            if ( $index === $last_position ) {
                $data['active'] = $id;
            }
            if ( !empty( $node['video_thumb'] ) && !empty( $node['video_src'] ) ) {
                $data['galleries'][ $id ][] = array(
                    'id'       => 0000000,
                    'url'      => $node['video_src'],
                    'type'     => 'video',
                    'poster'   => esc_attr( $node['video_src'] ),
                    'provider' => $node['video_provider'],
                    'caption'  => $node['video_caption']
                );
            }
//            if ( !empty( $node['gallery'] ) ) {
//                foreach ( explode( ",", $node['gallery'] ) as $gallery ) {
//                    $data['galleries'][ $id ][] = array(
//                        'id'       => $gallery,
//                        'url'      => wp_get_attachment_image_url( $gallery, 'full' ),
//                        'type'     => 'image',
//                        'poster'   => '',
//                        'provider' => '',
//                        'caption'  => wp_get_attachment_caption( $gallery )
//                    );
//                }
//            }

            if ( $node['number'] ) {
                $years[] = '<b class="' . $_y_class . '" data-element="' . $elm_id . '">' . esc_html( $node['number'] ) . '</b>';
                $navs[]  = '<li><span></span><button class="' . $_n_class . '" type="button" data-element="' . $elm_id . '">' . esc_html( $node['number'] ) . '</button></li>';
            }

            if ( $node['desc'] ) {
                $descriptions[] = '<p class="' . $_d_class . '" data-element="' . $elm_id . '">' . esc_html( $node['desc'] ) . '</p>';
            }

            $number_of_teacher = $node['teacher'] ? esc_html( $node['teacher'] ) : '';
            $number_of_program = $node['training_programs'] ? esc_html( $node['training_programs'] ) : '';
            $number_of_student = $node['total_students'] ? esc_html( $node['total_students'] ) : '';
            $number_of_lab     = $node['total_labs'] ? esc_html( $node['total_labs'] ) : '';

            $_counter_classes = $index === $last_position ? 'ovic-impression__counter --element-activated' : 'ovic-impression__counter';
            $count_teachers[] = '<div class="' . $_counter_classes . '" data-element="' . $elm_id . '"><div class="ovic-impression__counter__media"><figure><img src="' . esc_url( $img_teacher ) . '" loading="lazy" alt="" width="139" height="139"></figure></div><div class="ovic-impression__counter__wrap-info"><h4 class="ovic-impression__counter__number">' . $number_of_teacher . '</h4><b class="ovic-impression__counter__name">' . $text_trans_teacher . '</b></div></div>';
            $count_programs[] = '<div class="' . $_counter_classes . '" data-element="' . $elm_id . '"><div class="ovic-impression__counter__media"><figure><img src="' . esc_url( $img_programs ) . '" loading="lazy" alt="" width="139" height="139"></figure></div><div class="ovic-impression__counter__wrap-info"><h4 class="ovic-impression__counter__number">' . $number_of_program . '</h4><b class="ovic-impression__counter__name">' . $text_trans_programs . '</b></div></div>';
            $count_students[] = '<div class="' . $_counter_classes . '" data-element="' . $elm_id . '"><div class="ovic-impression__counter__media"><figure><img src="' . esc_url( $img_student ) . '" loading="lazy" alt="" width="139" height="139"></figure></div><div class="ovic-impression__counter__wrap-info"><h4 class="ovic-impression__counter__number">' . $number_of_student . '</h4><b class="ovic-impression__counter__name">' . $text_trans_students . '</b></div></div>';
            $count_labs[]     = '<div class="' . $_counter_classes . '" data-element="' . $elm_id . '"><div class="ovic-impression__counter__media"><figure><img src="' . esc_url( $img_labs ) . '" loading="lazy" alt="" width="139" height="139"></figure></div><div class="ovic-impression__counter__wrap-info"><h4 class="ovic-impression__counter__number">' . $number_of_lab . '</h4><b class="ovic-impression__counter__name">' . $text_trans_labs . '</b></div></div>';
        }
    }

    $slide_attrs   = array(
        'autoPlay'        => true,
        'freeScroll'      => false,
        'wrapAround'      => false,
        'prevNextButtons' => true,
        'pageDots'        => false,
        'draggable'       => true,
        'imagesLoaded'    => true,
        'adaptiveHeight'  => false,
        'contain'         => true,
        'fade'            => true,
        'percentPosition' => true,
        'lazyLoad'        => true,
        'arrowShape'      => array( 'x0' => 15, 'x1' => 60, 'y1' => 50, 'x2' => 65, 'y2' => 45, 'x3' => 25 ),
    );
    $slide_configs = json_encode( $slide_attrs );
    $_data         = json_encode( $data, JSON_UNESCAPED_UNICODE );

    ob_start();
    ?>

    <div class="ovic-impression">
        <div class="ovic-impression__wrap-head">
            <div class="ovic-impression__left">
                <div class="ovic-impression__media-container wrap-ovic-slide-block" style="overflow: hidden">
                    <i class="fa fa-circle-o-notch fa-spin"></i>
                    <div class="ovic-impression__media-control" data-configs="<?= esc_js( $slide_configs ) ?>" data-store="<?= esc_js( $_data ) ?>"></div>
                </div>
            </div>
            <div class="ovic-impression__right">
                <div class="ovic-impression__year-number"><?= wp_kses_post( implode( '', $years ) ); ?></div>
                <div class="ovic-impression__descriptions"><?= wp_kses_post( implode( '', $descriptions ) ); ?></div>
                <div class="ovic-impression__wrap-counters">
                    <div class="ovic-impression__counter-container ovic-impression__counter-container--teacher">
                        <?= wp_kses_post( implode( '', $count_teachers ) ) ?>
                    </div>
                    <div class="ovic-impression__counter-container ovic-impression__counter-container--labs">
                        <?= wp_kses_post( implode( '', $count_labs ) ) ?>
                    </div>
                    <div class="ovic-impression__counter-container ovic-impression__counter-container--programs">
                        <?= wp_kses_post( implode( '', $count_programs ) ) ?>
                    </div>
                    <div class="ovic-impression__counter-container ovic-impression__counter-container--students">
                        <?= wp_kses_post( implode( '', $count_students ) ) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="ovic-impression__wrap-navs">
            <ul><?= wp_kses_post( implode( '', $navs ) ) ?></ul>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode( 'ictu_impression', 'ictu_impression_shortcode' );