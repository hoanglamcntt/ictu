<div class="post-inner">
    <div class="thumb-wrap">
        <?php theme_post_thumbnail( 420, 364 ); ?>
        <a class="post-date" href="<?php echo theme_post_link( 'date' ); ?>">
            <span class="day"><?php echo get_the_date( 'dS' ); ?></span>
            <span class="month"><?php echo get_the_date( 'M' ); ?></span>
        </a>
    </div>
    <div class="post-info">
        <?php theme_post_title(); ?>
        <div class="post-meta">
            <?php theme_post_author(
                esc_html__( 'by', 'ictu' )
            ); ?>
        </div>
        <?php
        theme_post_excerpt( 21 );
        ?>
    </div>
</div>