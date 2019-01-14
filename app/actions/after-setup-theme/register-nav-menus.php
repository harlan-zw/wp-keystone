<?php

namespace App;

/**
 * Register the navigation menus. This configuration is loaded from `config/menus.php`.
 */
$menus = config('menus.menus');
// only if menus have been setup in the config
if (!$menus) {
    return;
}

/*
 * Register navigation menus
 */
register_nav_menus($menus);
