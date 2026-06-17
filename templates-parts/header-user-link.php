<?php
$account_link = wp_login_url();
$currentUser  = wp_get_current_user();
if (class_exists('WooCommerce')) {
    $account_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
}

if (is_user_logged_in()): ?>
    <div class="block-user-link">
        <a class="woo-user-link" href="<?php echo esc_url($account_link); ?>">
            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            <span class="text"><?php echo esc_html($currentUser->display_name); ?></span>
        </a>
        <?php if (function_exists('wc_get_account_menu_items')): ?>
            <ul class="sub-menu">
                <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
                    <li class="menu-item <?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <ul class="sub-menu">
                <?php wp_register('<li class="menu-item">'); ?>
                <li class="menu-item"><?php wp_loginout(); ?></li>
                <li class="menu-item">
                    <a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>"><?php echo sprintf('%s <abbr title="Really Simple Syndication">%s</abbr>', 'Entries', 'RSS'); ?></a>
                </li>
                <li class="menu-item">
                    <a href="<?php echo esc_url(get_bloginfo('comments_rss2_url')); ?>"><?php echo sprintf('%s <abbr title="Really Simple Syndication">%s</abbr>', 'Comments', 'RSS'); ?></a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
<?php else : ?>
    <a class="woo-user-link" href="<?php echo esc_url($account_link); ?>"><span class="text">Đăng nhập</span></a>/
    <a class="woo-user-link" href="<?php echo esc_url($account_link); ?>"><span class="text">Đăng ký</span></a>
<?php endif;