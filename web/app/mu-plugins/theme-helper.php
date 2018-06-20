<?php
/*
Plugin Name:  Fix Paths
Plugin URI:   http://www.4mation.com.au/
Description:  Fixes common Wordpress issues, these are found from wpfixme and other sources.
Version:      1.0.0
Author:       Harlan Wilton
Author URI:   http://www.4mation.com.au/
License:      MIT License
*/

define('WP_DEFAULT_THEME', 'primary');

add_filter('theme_file_path', 'get_view_path');
add_filter('template_directory', 'get_view_path');

function get_view_path() {
	return ROOT_DIR . '/resources/views';
}
