<?php defined('ABSPATH') || exit;

class Shortcode_Ovic_Showcase extends Ovic_Addon_Shortcode {

    public $shortcode = 'ovic_showcase';

    public function content($atts, $content = null)
    {
        $class = array('');
        $list  = $atts['list'];
        ob_start(); ?>
        <div class="ovic-block-showcase">
            <?php if (!empty($list)): ?>
                <ul class="ovic-block-showcase__list">
                    <?php foreach ($list as $item): ?>
                        <li class="ovic-block-showcase__elm">
                            <?php if ($item['link']): ?>
                                <a href="<?php echo esc_url($item['link']) ?>" class="ovic-block-showcase__inner">
                                    <?php if ($item['image']): ?>
                                        <figure class="ovic-block-showcase__wrap-media">
                                            <img class="ovic-block-showcase__media" src="<?php echo esc_url($item['image']['url']) ?>" alt="<?php echo esc_attr($item['image']['alt']) ?>">
                                        </figure>
                                    <?php endif; ?>
                                    <?php if ($item['text']): ?>
                                        <b class="ovic-block-showcase__text"><?php echo wp_specialchars_decode($item['text']) ?></b>
                                    <?php endif; ?>
                                </a>
                            <?php else: ?>
                                <div class="ovic-block-showcase__inner">
                                    <?php if ($item['image']): ?>
                                        <figure class="ovic-block-showcase__wrap-media">
                                            <img class="ovic-block-showcase__media" src="<?php echo esc_url($item['image']['url']) ?>" alt="<?php echo esc_attr($item['image']['alt']) ?>">
                                        </figure>
                                    <?php endif; ?>
                                    <?php if ($item['text']): ?>
                                        <b class="ovic-block-showcase__text"><?php echo wp_specialchars_decode($item['text']) ?></b>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}