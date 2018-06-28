<?php

namespace App;

/**
 * Make links relative - Make sure this occurs after the template redirect to avoid any weird redirections
 */

/**
 * Add leading slashes and make relative links
 */
add_action('template_redirect', function() {
    // Make sure we're not looking at a sitemap
    if (is_admin() || is_feed() || strpos($_SERVER['REQUEST_URI'], 'sitemap.xml') !== false) {
        return;
    }

    collect([
        'bloginfo_url',
        'the_permalink',
        'wp_list_pages',
        'wp_list_categories',
        'wp_get_attachment_url',
        'the_content_more_link',
        'the_tags',
        'get_pagenum_link',
        'get_comment_link',
        'month_link',
        'day_link',
        'year_link',
        'term_link',
        'the_author_posts_link',
        'script_loader_src',
        'style_loader_src',
        'theme_file_uri',
        'parent_theme_file_uri',
    ])->each(function($filter) {
        add_filter($filter, function($link = false) use ($filter) {
            if (is_array($link) && isset($link['permalink'])) {
                $link['permalink'] = relative_url($link['permalink']);
                return $link;
            }
            if (!is_string($link)) {
                return $link;
            }

            return relative_url($link);
        });
    });

}, PHP_INT_MAX);



/**
 * Gets to the absolute home url link
 *
 * @param bool $with_slash
 *
 * @return string
 */
function home_url_abs($with_slash = true) {
    return WP_HOME . ($with_slash ? '/' : '');
}
