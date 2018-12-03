<?php

namespace App;

/**
 * Page titles.
 *
 * @return string
 */
function title()
{
    if (is_home()) {
        if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
        }

        return __('Latest Posts', 'wp-keystone');
    }
    if (is_archive()) {
        return get_the_archive_title();
    }
    if (is_search()) {
        return sprintf(__('Search Results for %s', 'wp-keystone'), get_search_query());
    }
    if (is_404()) {
        return __('Not Found', 'wp-keystone');
    }

    return get_the_title();
}
