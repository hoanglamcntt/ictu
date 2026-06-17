<div class="post-inner">
    <?php theme_post_thumbnail( 450, 300 ); ?>
    <div class="post-info">
        <?php theme_post_title(); ?>
        <div class="post-meta">
            <?php theme_post_author(); ?>
            <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
                <?php echo get_the_date( 'F d, Y' ); ?>
            </a>
        </div>
        <?php theme_post_excerpt( 20 ); ?>
    </div>
</div>