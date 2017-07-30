<?php
/*
Plugin Name: Deferred Scripts
Plugin URI: 4mation
Description: All scripts loaded in are deferred until the DOM is rendered
Version: 1.3
Author: harlan
*/

// only frontend
if (is_admin()) {
    return;
}

add_filter('script_loader_tag', function($tag) {
    // if they already have async or defer, then ignore
    if (str_contains($tag,'async') || str_contains($tag,'defer')) {
        return $tag;
    }
    return str_replace(' src', ' defer="defer" src', $tag);
}, PHP_INT_MAX);

