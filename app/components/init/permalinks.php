<?php
namespace App;

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