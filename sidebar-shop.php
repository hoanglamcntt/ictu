<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Intn
 * @since 1.0
 * @version 1.0
 */

$page_layout = theme_page_layout();
?>
<?php if ($page_layout['layout'] != 'full') : ?>
    <aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e('Shop Sidebar', 'ictu'); ?>">
        <?php do_action('theme_before_dynamic_sidebar'); ?>
        <?php dynamic_sidebar($page_layout['sidebar']); ?>
        <?php do_action('theme_after_dynamic_sidebar'); ?>
    </aside><!-- #secondary -->
<?php endif; ?>
</div><!-- .site-content-contain shop-->
