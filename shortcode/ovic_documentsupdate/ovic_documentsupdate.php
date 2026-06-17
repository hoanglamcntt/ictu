<?php defined( 'ABSPATH' ) || exit;

class Shortcode_Ovic_Documentsupdate extends Ovic_Addon_Shortcode
{

    public $shortcode = 'ovic_documentsupdate';

    public $enqueue = true;

    public function content( $atts, $content = null )
    {
        $document_types = ictu_document_type();
        unset( $document_types['vb0'] );
        $document_types['vb0'] = __( 'Khác', 'ictu' );
        $menu_seconds          = [];
        $has_filter            = false;
        $heading               = !empty( $atts['heading'] ) ? $atts['heading'] : "Lĩnh vực";
        $post_type             = !empty( $atts['post_type'] ) ? $atts['post_type'] : "post";
        $taxonomy              = !empty( $atts['taxonomy'] ) ? $atts['taxonomy'] : "category";
        $cat                   = ( isset( $atts['category'] ) && $atts['category'] ) ? get_term_by( 'slug', $atts['category'], $taxonomy ) : null;
        $per_page              = !empty( $atts['per_page'] ) ? $atts['per_page'] : 20;
        $children              = !empty( $atts['children'] ) ? $atts['children'] : 0;
        $item_icon             = !empty( $atts['item_icon'] ) ? $atts['item_icon'] : "fa fa-file-text-o";
        $item_count            = !empty( $atts['item_count'] ) ? $atts['item_count'] : 1;
        $post_style            = !empty( $atts['post_style'] ) ? $atts['post_style'] : "style-01";
        $is_dev                = !empty( $atts['is_dev'] ) ? $atts['is_dev'] : 0;
        $paged                 = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args                  = array(
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'paged'          => $paged
        );
        $parsed_args           = array(
            'type'             => $post_type,
            'child_of'         => !empty( $cat->term_id ) ? $cat->term_id : 0,
            'hide_empty'       => true,
            'hierarchical'     => true,
            'taxonomy'         => $taxonomy,
            'depth'            => 3,
            'echo'             => false,
            'separator'        => '',
            'show_count'       => $item_count,
            'title_li'         => '',
            'current_category' => 0,
        );
        $get_term              = isset( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';
        $loc_theo_term         = '';
        $term_loc_theo_term    = '';
        if ( $get_term ) {
            $arr_get_term        = explode( '/', $get_term );
            $arr_get_term_filter = array_filter( $arr_get_term );
            $loc_theo_term       = array_pop( $arr_get_term_filter );
            if ( $loc_theo_term ) {
                $has_filter         = true;
                $term_loc_theo_term = get_term_by( 'slug', $loc_theo_term, $taxonomy );
            }
        }
        if ( $term_loc_theo_term ) {
            if ( $children == 1 ) {
                $args['tax_query'] = [ // this part will get the category and its childs
                    [
                        'taxonomy' => $taxonomy,
                        'terms'    => [ $term_loc_theo_term->term_id ], // parent category id
                        'field'    => 'term_id',
                    ],
                ];
            } else {
                $args['category__in'] = array( $term_loc_theo_term->term_id );
            }
            $parsed_args['current_category'] = $term_loc_theo_term->term_id;
        } elseif ( $cat ) {
            $args['tax_query'] = [ // this part will get the category and its childs
                [
                    'taxonomy' => $taxonomy,
                    'terms'    => [ $cat->term_id ], // parent category id
                    'field'    => 'term_id',
                ],
            ];
        }
        if ( $post_type == 'baochi' ) {
            $args['meta_key'] = 'custom_baochi_order';
            $args['orderby']  = [
                'meta_value_num' => 'ASC',
                'date'           => 'DESC',
            ];
        } else {
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
        }
        $custom_query = new WP_Query( $args );
        $all_cat_link = wp_list_categories( $parsed_args );
        if ( empty( $cat ) ) {
            $cat_link = home_url( '/' . $taxonomy . '/' );;
        } else {
            $cat_link = get_category_link( $cat );
        }
        $pattern       = str_replace( '_^_', '', add_query_arg( array( 'term' => '_^_' ), get_permalink() ) );
        $menu_template = str_replace( $cat_link, $pattern, $all_cat_link );
        $classes       = 'ovic-document-list' . ' ' . $post_style;
        $item_classes  = 'article-in-loop document-item' . ' ' . $post_style;
        if ( $post_type == 'baochi' ) {
            $item_classes .= ' post-item-type--baochi';
        } else {
            $item_classes .= ' post-item-type--document post-item-type--link-cover';
        }
        if ( $is_dev == 1 ) {
        }
        ob_start() ?>
        <div class="site-content sidebar-left single-post-page">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <div class="<?php echo esc_attr( $classes ) ?>">
                        <?php if ( $custom_query->have_posts() ): ?>
                            <?php while ( $custom_query->have_posts() ) : ?>
                                <?php $custom_query->the_post(); ?>
                                <?php
                                $order = 1;
                                if ( $post_type == 'baochi' ) {
                                    $baochi_order = get_post_meta( get_the_ID(), 'custom_baochi_order', true );
                                    $order        = !empty( $baochi_order ) ? $baochi_order : 1;
                                }
                                ?>
                                <article <?php post_class( $item_classes ); ?> data-order="<?php echo $order ?>">
                                    <?php
                                    $this->get_template( "layout/{$post_style}.php",
                                        array(
                                            'atts'         => $atts,
                                            'item_classes' => $item_classes,
                                            'item_icon'    => $item_icon,
                                        )
                                    );
                                    ?>
                                </article>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                            <?php
                            global $wp_query;
                            $args_paginate = array(
                                'base'      => add_query_arg( 'paged', '%#%' ),
                                'total'     => $custom_query->max_num_pages,
                                'current'   => $paged,
                                'prev_text' => '&laquo;',
                                'next_text' => '&raquo;'
                            ); ?>
                            <div class="ovic-document__pagination">
                                <?php echo paginate_links( $args_paginate ); ?>
                            </div>
                        <?php else: ?>
                            <p class="post-item-type--empty-document">Không có kết quả nào, vui lòng thử lại.</p>
                        <?php endif; ?>
                    </div>
                </main>
            </div>
            <aside id="secondary-section-menu" class="widget-area widget-area-in-document-shortcode">
                <?php if ( $menu_template ): ?>
                    <div class="widget widget_nav_menu">
                        <div class="widget-block-title">
                            <h2 class="widget-title"><?php echo esc_html( $heading ) ?><span class="arrow"></span></h2>
                            <div class="title-hr"></div>
                        </div>
                        <div class="ovic-menu-wapper horizontal">
                            <ul id="ovic_document_list_v2" class="menu ovic-menu ovic-document-list">
                                <?php echo wp_specialchars_decode( $menu_template ); ?>
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