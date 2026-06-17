<?php
/**
 * Template Author Social
 *
 * @return string
 * @var $author_id
 *
 */
?>
<ul class="social person-socials">
    <?php if (get_the_author_meta('facebook', $author_id)) : ?>
        <li>
            <a target="_blank" class="facebook" href="<?php echo get_the_author_meta('facebook', $author_id); ?>">
                <i class="fa fa-facebook"></i>
            </a>
        </li>
    <?php endif; ?>
    <?php if (get_the_author_meta('twitter', $author_id)) : ?>
        <li>
            <a target="_blank" class="twitter" href="<?php echo get_the_author_meta('twitter', $author_id); ?>">
                <i class="fa fa-twitter"></i>
            </a>
        </li>
    <?php endif; ?>
    <?php if (get_the_author_meta('google', $author_id)) : ?>
        <li>
            <a target="_blank" class="google-plus" href="<?php echo get_the_author_meta('google', $author_id); ?>">
                <i class="fa fa-google"></i>
            </a>
        </li>
    <?php endif; ?>
    <?php if (get_the_author_meta('instagram', $author_id)) : ?>
        <li>
            <a target="_blank" class="instagram" href="<?php echo get_the_author_meta('instagram', $author_id); ?>">
                <i class="fa fa-instagram"></i>
            </a>
        </li>
    <?php endif; ?>
    <?php if (get_the_author_meta('youtube', $author_id)) : ?>
        <li>
            <a target="_blank" class="youtube" href="<?php echo get_the_author_meta('youtube', $author_id); ?>">
                <i class="fa fa-youtube-play"></i>
            </a>
        </li>
    <?php endif; ?>
    <?php if (get_the_author_meta('linkedin', $author_id)) : ?>
        <li>
            <a target="_blank" class="linkedin" href="<?php echo get_the_author_meta('linkedin', $author_id); ?>">
                <i class="fa fa-linkedin-square"></i>
            </a>
        </li>
    <?php endif; ?>
    <?php if (get_the_author_meta('pinterest', $author_id)) : ?>
        <li>
            <a target="_blank" class="pinterest" href="<?php echo get_the_author_meta('pinterest', $author_id); ?>">
                <i class="fa fa-pinterest-p"></i>
            </a>
        </li>
    <?php endif; ?>
</ul>