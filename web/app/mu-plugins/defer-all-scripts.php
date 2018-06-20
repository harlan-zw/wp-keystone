<?php
/*
Plugin Name: Deferred Scripts
Plugin URI: 4mation
Description: All scripts loaded in are deferred until the DOM is rendered
Version: 1.3
Author: harlan
*/

// only fronte
if (is_admin()) {
    return;
}

add_filter('script_loader_tag', function($script, $tag) {
    if ($tag === 'jquery') {
        return $script;
    }
    // if they already have async or defer, then ignore
    if (str_contains($script,'async') || str_contains($script,'defer')) {
        return $script;
    }
    return str_replace(' src', ' defer="defer" src', $script);
}, PHP_INT_MAX, 2);

