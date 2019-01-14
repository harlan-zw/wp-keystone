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

/**
 * Gets to the absolute home url link.
 *
 * @param bool $with_slash
 *
 * @return string
 */
function home_url_abs($with_slash = true)
{
    return WP_HOME.($with_slash ? '/' : '');
}

/* utility functions */
function is_env_dev()
{
    return WP_ENV === 'development';
}

function is_env_production()
{
    return !is_env_dev() && !is_env_staging();
}

function is_env_staging()
{
    return WP_ENV === 'staging';
}

function is_login_page()
{
    return in_array($GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php']);
}
