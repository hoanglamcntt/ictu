<?php

/**
 * Filter to detect Sitemap URL in the Search Console if you are using different plugin for sitemap.
 */
add_filter( 'rank_math/sitemap/index/slug', function( $slug ) {
    return 'sitemap';
});