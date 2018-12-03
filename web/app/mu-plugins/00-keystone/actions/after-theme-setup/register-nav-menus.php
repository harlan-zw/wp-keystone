<?php
namespace App;

// allow script deferring to be toggled off
$menus = config('permalinks.use_relative_links');
if (!$menus) {
    return;
}

/**
 * Register navigation menus
 */
register_nav_menus($menus);
