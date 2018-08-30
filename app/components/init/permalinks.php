<?php

namespace App;

/**
 * Make links relative - Make sure this occurs after the template redirect to avoid any weird redirection
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
 * Anything in the rewrites config is registered
 */
collect(config()->get('rewrites.rules'))->each(function($route, $regex) {
	add_rewrite_rule($regex, $route, 'top');
});


add_filter('query_vars', function($vars) {
	return array_merge($vars, config()->get('rewrites.query_vars'));
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

/**
 * Compare URL against relative URL
 */
function url_compare($url, $rel) {
	$url = trailingslashit($url);
	$rel = trailingslashit($rel);
	return ((strcasecmp($url, $rel) === 0) || relative_url($url) == $rel);
}


function relative_url($input) {
	if (is_feed()) {
		return $input;
	}
	$url = parse_url($input);
	if (!isset($url['host']) || !isset($url['path'])) {
		return $input;
	}
	$site_url = parse_url(network_home_url());  // falls back to home_url
	if (!isset($url['scheme'])) {
		$url['scheme'] = $site_url['scheme'];
	}
	$hosts_match = $site_url['host'] === $url['host'];
	$schemes_match = $site_url['scheme'] === $url['scheme'];
	$ports_exist = isset($site_url['port']) && isset($url['port']);
	$ports_match = ($ports_exist) ? $site_url['port'] === $url['port'] : true;
	if ($hosts_match && $schemes_match && $ports_match) {
		return wp_make_link_relative($input);
	}
	return $input;
}
