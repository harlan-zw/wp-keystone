<?php

namespace App;

/*
 * Anything in the rewrites config is registered
 */
collect(config()->get('rewrites.rules'))->each(function ($route, $regex) {
    add_rewrite_rule($regex, $route, 'top');
});

add_filter('query_vars', function ($vars) {
    return array_merge($vars, config()->get('rewrites.query_vars'));
});
