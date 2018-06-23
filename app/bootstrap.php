<?php

namespace App;

use Roots\Sage\Config;
use Roots\Sage\Container;

require APP_DIR . '/helpers.php';


$preload_files = collect(get_files_recursive(APP_DIR . '/components', '/\.php$/'));

// We only pre-load our commands if the WP_CLI constant exists
if (\defined('WP_CLI') && WP_CLI) {
	$preload_files = $preload_files->merge(get_files_recursive(APP_DIR . '/commands', '/\.php$/'));
}
 $preload_files
	->map(function ($file) {
		return str_replace([ '.php', APP_DIR ], '', $file);
	})
	->filter(function ($f) {
		return !empty($f);
	})
	->push('setup')
	->each(function($file) {
		require_once APP_DIR . "/{$file}.php";
	});


// require components


Container::getInstance()
         ->bindIf('config', function () {
	        $files = collect(get_files_recursive(ROOT_DIR . '/config/app/', '/\.php$/'))->mapWithKeys(function($file) {
	        	return [basename($file, '.php') => require $file];
	        })->toArray();
	         return new Config($files);
         }, true);
