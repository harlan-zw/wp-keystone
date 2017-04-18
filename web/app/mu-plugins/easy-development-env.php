<?php
/*
Plugin Name:  Easy Dev Logins
Plugin URI:   http://www.4mation.com.au/
Description:  Lets us log in to the backend with any password if the environment is on development
Version:      1.0.0
Author:       Harlan Wilton
Author URI:   http://www.4mation.com.au/
License:      MIT License
*/

if (is_env_dev()) {

    // password is always correct !
    add_filter('check_password', function() {
        return true;
    });

    add_action('init', function() {
        flush_rewrite_rules();
    }, 0);

}
