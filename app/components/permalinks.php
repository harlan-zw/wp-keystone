<?php

namespace App;

/**
 * Make links relative - Make sure this occurs after the template redirect to avoid any weird redirections
 */
use WPSEO_Redirect_Manager;

/**
 * Checks within Yoast SEO if a requested URI is for a 410 page
 * @return bool
 */
function is_410_request() {
    if (!class_exists('WPSEO_Redirect_Manager')) {
        return false;
    }
    $redirect_manager = new WPSEO_Redirect_Manager('plain');
    $redirect = $redirect_manager->get_redirect($_SERVER['REQUEST_URI']);
    if (empty($redirect)) {
        return false;
    }
    return $redirect->get_type() == 410;
}

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
 *  Add 410 response header if 410 page
 */
add_action('wp', function() {

    if (is_410_request()) {
        http_response_code(410);
        return;
    }

}, 1);


/**
 * Need to modify the loading of yoast redirects to achieve:
 * - redirects need to be sorted for hierarchy to avoid early parent redirections
 */
add_action('wpseo_premium_get_redirects', function($redirects) {
	if (empty($redirects)) {
		return $redirects;
	}
    // change all to regex
    $redirects = collect($redirects)->sortByDesc(function($redirect) {
        // assume hierarchy by count of forward slashes
        return substr_count($redirect['origin'], '/');
    })->toArray();

    return $redirects;
});

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
