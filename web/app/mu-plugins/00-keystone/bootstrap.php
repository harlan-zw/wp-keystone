<?php
namespace App;

use Illuminate\Container\Container;

\define('WP_DEFAULT_THEME', 'main');

function get_files_recursive($dir, String $filter = '/.*', &$results = []) {
	$files = scandir($dir, SCANDIR_SORT_NONE);

	foreach ($files as $key => $value) {
		$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
		if (!is_dir($path)) {
			if (preg_match($filter, $path) !== false) {
				$results[] = $path;
			}
		} else if ($value !== '.' && $value !== '..') {
			get_files_recursive($path, $filter, $results);
		}
	}

	return $results;
}


function load_keystone_directory($directory, Container &$container = null) {

	$preload_files = collect('helpers')
		->merge(get_files_recursive($directory . '/components', '/\.php$/'));

	// We only pre-load our commands if the WP_CLI constant exists
	if (\defined('WP_CLI') && WP_CLI) {
		$preload_files = $preload_files->merge(get_files_recursive($directory . '/commands', '/\.php$/'));
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
			if (file_exists(APP_DIR . "/{$file}.php")) {
				require_once APP_DIR . "/{$file}.php";
			}
		});

	if (null === $container) {
		$container = app();
	}

	// require the config
	$container->bindIf('config', function() {
		$files = collect(get_files_recursive(ROOT_DIR . '/config/app/', '/\.php$/'))
			->mapWithKeys(function($file) {
				return [basename($file, '.php') => require $file];
			})->toArray();
		return new Config($files);
	}, true);
}
