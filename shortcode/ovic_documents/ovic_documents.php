<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Documents extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_documents';

    public function content($atts, $content = null)
    {
        wp_enqueue_style('ovic-documents');
        $document_types = ictu_document_type();
        unset($document_types['vb0']);
        $document_types['vb0'] = __('Khác', 'ictu');
        $menu_seconds          = [];
        $has_filter            = false;
        $cat                   = (isset($atts['category']) && $atts['category']) ? get_term_by('slug', $atts['category'], 'category') : null;
        $categories            = $cat ? get_categories(array('parent' => $cat->term_id)) : null;
        $paged                 = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args                  = array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 1, 'paged' => $paged);
        $loc_theo_loai_vb      = isset($_GET['loai_vb']) ? sanitize_text_field($_GET['loai_vb']) : '';
        $loc_theo_linh_vuc     = isset($_GET['linh_vuc']) ? sanitize_text_field($_GET['linh_vuc']) : '';
        if ($loc_theo_linh_vuc) {
            $has_filter = true;
            $term       = get_term_by('slug', $loc_theo_linh_vuc, 'category');
            if ($term) {
                $args['category__in'] = array($term->term_id);
            }
        } elseif ($categories) {
            $args['category__in'] = array();
            foreach ($categories as $_cat) {
                $args['category__in'][] = $_cat->cat_ID;
            }
        }
        if ($loc_theo_loai_vb) {
            // $args['meta_query'] = array( array( 'key' => '_custom_metabox_post_options', 'value' => '"doc_type";' . serialize(strval($loc_theo_loai_vb)), 'compare' => 'LIKE'));
            $has_filter         = true;
            $args['meta_query'] = array(
                array(
                    'key'     => '_ictu_doc_type',
                    'value'   => $loc_theo_loai_vb,
                    'compare' => 'LIKE',
                )
            );
        }
        $document_list_class = $has_filter ? 'ovic-document-list ovic-document-list--has-filter' : 'ovic-document-list';
        $custom_query        = new WP_Query($args);
        ob_start() ?>
        <div class="site-content sidebar-left single-post-page">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <div class="<?php echo $document_list_class; ?>">
                        <?php if ($has_filter): ?>
                            <div class="ovic-document-list__show-filter-fields">
                                <?php if ($loc_theo_loai_vb): ?>
                                    <?php $filter_lvb = key_exists($loc_theo_loai_vb, $document_types) ? $document_types[$loc_theo_loai_vb] : '... error filter' ?>
                                    <a class="elm-filter-field-block" href="<?php echo remove_query_arg('loai_vb'); ?>">
                                        <i class="fa fa-filter" aria-hidden="true"></i>
                                        <span>Loại văn bản: <?php echo $filter_lvb ?></span>
                                    </a>
                                <?php endif; ?>
                                <?php if ($loc_theo_linh_vuc && $categories): ?>
                                    <?php $filter_lv = '... error filter' ?>
                                    <?php foreach ($categories as $item) {
                                        if ($item->slug === $loc_theo_linh_vuc) {
                                            $filter_lv = $item->name;
                                        }
                                    } ?>
                                    <a class="elm-filter-field-block" href="<?php echo remove_query_arg('linh_vuc'); ?>">
                                        <i class="fa fa-filter" aria-hidden="true"></i>
                                        <span>Lĩnh vực: <?php echo $filter_lv ?></span>
                                    </a>
                                <?php endif; ?>
                                <a class="elm-filter-field-block--btn-remove-all" href="<?php echo remove_query_arg((array_keys($_GET))); ?>">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if ($custom_query->have_posts()): ?>
                            <?php while ($custom_query->have_posts()) : ?>
                                <?php $custom_query->the_post(); ?>
                                <?php $post_options = get_post_meta(get_the_ID(), '_custom_metabox_post_options', true) ?>
                                <article <?php post_class("article-in-loop post-item-type--document"); ?>>
                                    <div class="post-item-type--document__head-name">
                                        <?php the_title('<h2 class="post-item-type--document__post-name">', '</h2>') ?>
                                        <?php if ($post_options && isset($post_options['doc_code'])): ?>
                                            <p class="post-item-type--document__post-code"><?php echo esc_html($post_options['doc_code']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="post-item-type--document__wrap-date">
                                        <?php if ($post_options && isset($post_options['doc_date_sight'])): ?>
                                            <span class="post-item-type--document__date"><?php echo esc_html($post_options['doc_date_sight']) ?></span>
                                        <?php else: ?>
                                            <span class="post-item-type--document__date">__/__/____</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="post-item-type--document__wrap-link">
                                        <a href="<?php echo get_the_permalink() ?>" class="post-item-type--document__link"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                            <?php
                            global $wp_query;
                            $args_paginate = array(
                                'base'      => add_query_arg('paged', '%#%'),
                                'total'     => $custom_query->max_num_pages,
                                'current'   => $paged,
                                'prev_text' => '&laquo;',
                                'next_text' => '&raquo;'
                            ); ?>
                            <div class="ovic-document__pagination">
                                <?php echo paginate_links($args_paginate); ?>
                            </div>
                        <?php else: ?>
                            <p class="post-item-type--empty-document">Không có tài liệu nào phù hợp với yêu cầu của bạn</p>
                        <?php endif; ?>
                    </div>
                </main>
            </div>
            <aside id="secondary" class="widget-area widget-area-in-document-shortcode">
                <div id="sidebar-head-section">
                    <span class="sidebar-head-section__title">Sidebar</span>
                    <button type="button" class="sidebar-head-section__button">×</button>
                </div>
                <div class="widget widget_nav_menu">
                    <div class="widget-block-title">
                        <h2 class="widget-title">Loại văn bản<span class="arrow"></span></h2>
                        <div class="title-hr"></div>
                    </div>
                    <div class="ovic-menu-wapper horizontal">
                        <ul class="menu ovic-menu ovic-document-list">
                            <?php $count = 0; ?>
                            <?php foreach ($document_types as $type => $name): ?>
                                <?php
                                $query_vars   = $loc_theo_linh_vuc ? array('linh_vuc' => $loc_theo_linh_vuc, 'loai_vb' => $type) : array('loai_vb' => $type);
                                $tag_a_class  = $loc_theo_loai_vb && $loc_theo_loai_vb === $type ? 'ovic-document-list__link --document-active' : 'ovic-document-list__link';
                                $tag_li_class = (++$count < 6 || $loc_theo_loai_vb === $type) ? 'ovic-document-list__elm --elm-always-visible' : 'ovic-document-list__elm --elm-invisible';
                                ?>
                                <li class="<?php echo $tag_li_class ?>">
                                    <a class="<?php echo $tag_a_class ?>" href="<?php echo add_query_arg($query_vars, get_permalink()); ?>"><i class="fa fa-hand-o-right"></i><?php echo esc_html($name) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li class="ovic-document-list__elm --elm-always-visible elm-button-control">
                                <a class="elm-button-control__btn-toggle" href="#"><i class="fa fa-angle-down" aria-hidden="true"></i>
                                    <span class="elm-button-control__lbl-collapsed">Mở rộng</span>
                                    <span class="elm-button-control__lbl-expanded">Thu gọn</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php if ($categories): ?>
                    <div class="widget widget_nav_menu">
                        <div class="widget-block-title">
                            <h2 class="widget-title">Lĩnh vực<span class="arrow"></span></h2>
                            <div class="title-hr"></div>
                        </div>
                        <div class="ovic-menu-wapper horizontal">
                            <ul class="menu ovic-menu ovic-document-list">
                                <?php foreach ($categories as $item): ?>
                                    <?php $query_vars = $loc_theo_loai_vb ? array('linh_vuc' => $item->slug, 'loai_vb' => $loc_theo_loai_vb) : array('linh_vuc' => $item->slug); ?>
                                    <li class="ovic-document-list__elm">
                                        <a class="<?php echo $loc_theo_linh_vuc && $loc_theo_linh_vuc === $item->slug ? 'ovic-document-list__link --document-active' : 'ovic-document-list__link' ?>" href="<?php echo add_query_arg($query_vars, get_permalink()); ?>"><i class="fa fa-hand-o-right"></i><?php echo esc_html($item->name) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
        <?php
        return ob_get_clean();
    }
}