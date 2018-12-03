<?php

namespace App;

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


/* utility functions */
function is_env_dev() {
    return WP_ENV === 'development';
}

function is_env_production() {
    return !is_env_dev() && !is_env_staging();
}

function is_env_staging() {
    return WP_ENV === 'staging';
}

function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}
