<?php
/*
Plugin Name: Whoops Error Handling
Plugin URI: 4mation
Description: Nicer errors
Version: 1.3
Author: harlan
*/

/**
 * This uses Whoops. See here http://filp.github.io/whoops/
 *
 * On the event of an error we show a nicer error screen. Only for staging and development.
 *
 * Note: This may have collisions will any sort of logging or bug reporting library.
 */
if (is_env_production()) {
    return;
}

$whoops = new \Whoops\Run();

$handler = new \Whoops\Handler\PrettyPageHandler();

$handler->setPageTitle(get_bloginfo('name') . ' - Whoops an Error!');

$whoops->pushHandler($handler);
$whoops->register();