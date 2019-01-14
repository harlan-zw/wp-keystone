<?php
namespace App;

// only frontend
if (is_admin()) {
    return;
}

// allow script deferring to be toggled off
$defer_scripts = config('assets.defer_scripts');
if (!$defer_scripts) {
    return;
}

add_filter('script_loader_tag', function ($script, $tag) {
    if ($tag === 'jquery') {
        return $script;
    }
    // if they already have async or defer, then ignore
    if (str_contains($script, 'async') || str_contains($script, 'defer')) {
        return $script;
    }

    return str_replace(' src', ' defer="defer" src', $script);
}, PHP_INT_MAX, 2);
