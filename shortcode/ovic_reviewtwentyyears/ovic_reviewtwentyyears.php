<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Reviewtwentyyears extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_reviewtwentyyears';

    public function content($atts, $content = null)
    {
// wp_enqueue_script('fancybox');
// wp_enqueue_style('fancybox');
//        $width          = 626;
//        $height         = 400;
//        $placeholder    = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22{$width}%22%20height%3D%22{$height}%22%20viewBox%3D%220%200%20{$width}%20{$height}%22%3E%3C%2Fsvg%3E";
        $placeholder    = get_theme_file_uri('/assets/images/placeholder-626x400.png');
        $data           = array(
            'placeholder' => esc_url($placeholder),
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

        $img_teacher  = get_theme_file_uri('/assets/images/count-teacher.jpg');
        $img_programs = get_theme_file_uri('/assets/images/count-programs.jpg');
        $img_student  = get_theme_file_uri('/assets/images/count-students.jpg');
        $img_labs     = get_theme_file_uri('/assets/images/count-labs.jpg');

        $text_trans_teacher  = __('CBGV trình độ cao', 'ictu');
        $text_trans_programs = __('Ngành đào tạo', 'ictu');
        $text_trans_students = __('Quy mô đào tạo ĐHCQ', 'ictu');
        $text_trans_labs     = __('Diện tích sàn XD', 'ictu');

        if (!empty($atts['list'])) {
            $last_position = count($atts['list']) - 1;
            foreach ($atts['list'] as $index => $node) {
                $id       = $node['_id'] ? $node['_id'] : $index;
                $elm_id   = esc_attr($id);
                $_y_class = ($index === $last_position) ? 'ovic-rty__year --element-activated' : 'ovic-rty__year';
                $_n_class = ($index === $last_position) ? 'btn ovic-review-twenty-years__btn-nav --element-activated' : 'btn ovic-review-twenty-years__btn-nav';
                $_d_class = ($index === $last_position) ? 'ovic-rty__desc --element-activated intro-y' : 'ovic-rty__desc intro-y';

                if ($index === $last_position) {
                    $data['active'] = $id;
                }
                if (!empty($node['video_thumb']) && !empty($node['video_src'])) {
                    $data['galleries'][$id][] = array(
                        'id'       => 0000000,
                        'url'      => $node['video_src'],
                        'type'     => 'video',
                        'poster'   => esc_attr($node['video_src']),
                        'provider' => $node['video_provider'],
                        'caption'  => $node['video_caption']
                    );
                }
                if (!empty($node['gallery'])) {
                    foreach ($node['gallery'] as $gallery) {
                        $data['galleries'][$id][] = array(
                            'id'       => $gallery['id'],
                            'url'      => $gallery['url'],
                            'type'     => 'image',
                            'poster'   => '',
                            'provider' => '',
                            'caption'  => wp_get_attachment_caption($gallery['id'])
                        );
                    }
                }

                if ($node['number']) {
                    $years[] = '<b class="' . $_y_class . '" data-element="' . $elm_id . '">' . esc_html($node['number']) . '</b>';
                    $navs[]  = '<li><span></span><button class="' . $_n_class . '" type="button" data-element="' . $elm_id . '">' . esc_html($node['number']) . '</button></li>';
                }

                if ($node['desc']) {
                    if ($node['link'] && $node['link'] !== '#') {
                        $descriptions[] = '<a href="' . esc_url($node['link']) . '"><p class="' . $_d_class . '" data-element="' . $elm_id . '">' . esc_html($node['desc']) . '</p></a>';
                    } else {
                        $descriptions[] = '<p class="' . $_d_class . '" data-element="' . $elm_id . '">' . esc_html($node['desc']) . '</p>';
                    }
                }

                $number_of_teacher = $node['teacher'] ? esc_html($node['teacher']) : '';
                $number_of_program = $node['training_programs'] ? esc_html($node['training_programs']) : '';
                $number_of_student = $node['total_students'] ? esc_html($node['total_students']) : '';
                $number_of_lab     = $node['total_labs'] ? esc_html($node['total_labs']) : '';

                $_counter_classes = $index === $last_position ? 'ovic-review-twenty-years__counter --element-activated' : 'ovic-review-twenty-years__counter';
                $count_teachers[] = '<div class="' . $_counter_classes . '" data-element="' . $elm_id . '"><div class="ovic-review-twenty-years__counter__media"><figure><img src="' . esc_url($img_teacher) . '" alt="" width="139" height="139"></figure></div><div class="ovic-review-twenty-years__counter__wrap-info"><h4 class="ovic-review-twenty-years__counter__number">' . $number_of_teacher . '</h4><b class="ovic-review-twenty-years__counter__name">' . $text_trans_teacher . '</b></div></div>';
                $count_programs[] = '<div class="' . $_counter_classes . '" data-element="' . $elm_id . '"><div class="ovic-review-twenty-years__counter__media"><figure><img src="' . esc_url($img_programs) . '" alt="" width="139" height="139"></figure></div><div class="ovic-review-twenty-years__counter__wrap-info"><h4 class="ovic-review-twenty-years__counter__number">' . $number_of_program . '</h4><b class="ovic-review-twenty-years__counter__name">' . $text_trans_programs . '</b></div></div>';
                $count_students[] = '<div class="' . $_counter_classes . '" data-element="' . $elm_id . '"><div class="ovic-review-twenty-years__counter__media"><figure><img src="' . esc_url($img_student) . '" alt="" width="139" height="139"></figure></div><div class="ovic-review-twenty-years__counter__wrap-info"><h4 class="ovic-review-twenty-years__counter__number">' . $number_of_student . '</h4><b class="ovic-review-twenty-years__counter__name">' . $text_trans_students . '</b></div></div>';
                $count_labs[]     = '<div class="' . $_counter_classes . '" data-element="' . $elm_id . '"><div class="ovic-review-twenty-years__counter__media"><figure><img src="' . esc_url($img_labs) . '" alt="" width="139" height="139"></figure></div><div class="ovic-review-twenty-years__counter__wrap-info"><h4 class="ovic-review-twenty-years__counter__number">' . $number_of_lab . '</h4><b class="ovic-review-twenty-years__counter__name">' . $text_trans_labs . '</b></div></div>';
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
            'arrowShape'      => array('x0' => 15, 'x1' => 60, 'y1' => 50, 'x2' => 65, 'y2' => 45, 'x3' => 25),
        );
        $slide_configs = json_encode($slide_attrs);
        $_data         = json_encode($data, JSON_UNESCAPED_UNICODE);
        ob_start() ?>
        <div class="ovic-review-twenty-years">
            <div class="ovic-review-twenty-years__wrap-head">
                <div class="ovic-review-twenty-years__left">
                    <div class="ovic-review-twenty-years__media-container wrap-ovic-slide-block" style="overflow: hidden">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                        <div class="ovic-review-twenty-years__media-control" data-configs="<?php echo esc_js($slide_configs) ?>" data-store="<?php echo esc_js($_data) ?>"></div>
                    </div>
                </div>
                <div class="ovic-review-twenty-years__right">
                    <div class="ovic-review-twenty-years__year-number"><?php echo implode('', $years); ?></div>
                    <div class="ovic-review-twenty-years__descriptions"><?php echo implode('', $descriptions); ?></div>
                    <div class="ovic-review-twenty-years__wrap-counters">
                        <div class="ovic-review-twenty-years__counter-container ovic-review-twenty-years__counter-container--teacher">
                            <?php echo implode('', $count_teachers) ?>
                        </div>
                        <div class="ovic-review-twenty-years__counter-container ovic-review-twenty-years__counter-container--labs">
                            <?php echo implode('', $count_labs) ?>
                        </div>
                        <div class="ovic-review-twenty-years__counter-container ovic-review-twenty-years__counter-container--programs">
                            <?php echo implode('', $count_programs) ?>
                        </div>
                        <div class="ovic-review-twenty-years__counter-container ovic-review-twenty-years__counter-container--students">
                            <?php echo implode('', $count_students) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ovic-review-twenty-years__wrap-navs">
                <ul><?php echo implode('', $navs) ?></ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}