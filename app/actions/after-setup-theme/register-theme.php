<?php

namespace App;

/**
 * This sets the default theme to be keystone
 */

\defined('WP_DEFAULT_THEME') || define('WP_DEFAULT_THEME', config('keystone.theme_name'));
