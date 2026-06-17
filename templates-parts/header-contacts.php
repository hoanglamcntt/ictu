<?php
/**
 * Template header contacts
 */
$contacts = get_theme_option('header_contacts', []);
?>
<?php if (is_array($contacts) && !empty($contacts)): ?>
    <ul class="header-contact-list">
        <?php foreach ($contacts as $item): ?>
            <?php
            $href = $item['link'] ? $item['link'] : '#';
            $src  = $item['type'] === 'image' && $item['image'] ? wp_get_attachment_image_url($item['image'], 'full') : null;
            ?>
            <li class="header-contact-list__elm">
                <a href="<?php echo esc_url($href); ?>" class="header-contact-list__link header-contact-list__link--<?php echo esc_attr($item['type'])?>">
                    <?php if ($item['type'] === 'icon' && $item['icon']): ?>
                        <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                    <?php endif; ?>
                    <?php if ($src): ?>
                        <img src="<?php echo esc_url($src); ?>" alt="">
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>