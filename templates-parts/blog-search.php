<?php
/**
 * @var $category
 */
$selected = '';
if (isset($_GET['category']) && $_GET['category']) {
    $selected = $_GET['category'];
}
$args = array(
    'show_option_none'  => __('Tất cả thể loại', 'ictu'),
    'class'             => 'category-search-option',
    'hide_empty'        => 1,
    'orderby'           => 'name',
    'order'             => "ASC",
    'tab_index'         => true,
    'hierarchical'      => true,
    'id'                => rand(),
    'name'              => 'category',
    'value_field'       => 'slug',
    'selected'          => $selected,
    'option_none_value' => '0',
);
?>
<div class="block-search sub-menu">
    <div class="dgwt-wcas-search-wrapp dgwt-wcas-has-submit" data-wcas-context="<?php echo substr(uniqid(), 8, 4); ?>">
        <form class="search-form dgwt-wcas-search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="frm-inner">
                <div class="search-input">
                    <input type="search" class="input-text <?php echo esc_attr('dgwt-wcas-search-input'); ?>" name="s" value="<?php echo get_search_query(); ?>" placeholder="Tìm kiếm thông tin các khóa học" autocomplete="off">
                    <div class="dgwt-wcas-preloader"></div>
                </div>
                <div class="category">
                    <?php wp_dropdown_categories($args); ?>
                </div>
                <div class="bottom-wrap">
                    <button type="submit" class="btn-submit dgwt-wcas-search-submit"><span class="bio-icon1"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
