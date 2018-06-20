<?php
/*
Plugin Name:  Production Only Plugins
Plugin URI:   http://www.4mation.com.au/
Description:  Force some plugins to only be enabled on the production environment
Version:      1.0.0
Author:       Harlan Wilton
Author URI:   http://www.4mation.com.au/
License:      MIT License
*/

/**
 * Force some plugins to only be enabled on the production environment. Useful for instances where having a plugin on
 * causes production behaviour to occur, i.e. sending live emails.
 */
const ENVIRONMENT_BASED_PLUGIN_MAPPING = [
	'mailgun/mailgun.php' => [
		'production'
	],
	'bugsnag/bugsnag.php' => [
		'production',
		'uat'
	],
	'query-monitor/query-monitor.php' => [
		'development',
		'staging'
	],
//	'easy-development/init.php' => [
//		'development'
//	],
	'wordfence/wordfence.php' => [
		'uat',
		'production'
	],
	'w3-total-cache/w3-total-cache.php' => [
		'uat',
		'production'
	]

];

/**
 * Force deactivate the plugins that should only be activated on live site.
 */
add_action('init', function() {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	foreach(ENVIRONMENT_BASED_PLUGIN_MAPPING as $plugin => $environments) {
		if (!in_array(WP_ENV, $environments) && is_plugin_active($plugin)) {
			deactivate_plugins($plugin, true);
		} else if (in_array(WP_ENV, $environments) && !is_plugin_active($plugin)) {
			activate_plugin($plugin);
		}
	}
});
