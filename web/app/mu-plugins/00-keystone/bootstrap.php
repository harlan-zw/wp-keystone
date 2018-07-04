<?php
namespace App;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;

function get_files_recursive($dir, String $filter = '/.*', &$results = []) {
	if (!file_exists($dir)) {
		return [];
	}
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

function load_keystone_directory($directory) {

	// our magic file loader - for each folder in components we map to a wordpress action to load the files within
	$component_path = $directory . '/components';
	collect(get_files_recursive($component_path, '/\.php$/'))
		// clean the files / paths
		->map(function($file) use ($component_path) {
			return str_replace([ '.php', $component_path ], '', $file);
		})
		// group them by directory (if they have one)
		->groupBy(function($file) {
			$path_info = pathinfo($file);
			return $path_info['dirname'];
		})
		->each(function(Collection $files, $folder) use($component_path) {
			// need to reverse it so naturally ordered files come first
			$files = $files->reverse();

			$include_files = function() use ($files, $component_path) {
				$files->each(function($file) use ($component_path) {
					include $component_path . $file . '.php';
				});
			};
			// clean folder string
			$action_name = str_replace('-', '_', ltrim($folder, '/'));

			if (empty($action_name)) {
				// always just load them in
				$include_files();
				return;
			}
			// acf uses namespaced actions
			if (starts_with($action_name, 'acf')) {
				$action_name = str_replace('_', '/', $action_name);
			}
			// otherwise we wait until our action runs
			add_action($action_name, $include_files, 1);
		});

	$preload_files = collect();

	// We only pre-load our commands if the WP_CLI constant exists
	if (\defined('WP_CLI') && WP_CLI) {
		$preload_files = $preload_files->merge(get_files_recursive($directory . '/commands', '/\.php$/'));
	}
	$preload_files
		->map(function ($file) use($directory) {
			return str_replace([ '.php', $directory ], '', $file);
		})
		->filter(function ($f) {
			return !empty($f);
		})
		->push('helpers')
		->push('setup')
		->each(function($file) use($directory) {
			if (file_exists($directory . "/{$file}.php")) {
				require_once $directory . "/{$file}.php";
			}
		});

}


/**
 * Get the app container.
 *
 * @param string $abstract
 * @param array $parameters
 * @param Container $container
 * @return Container|mixed
 */
function app($abstract = null, $parameters = [], Container $container = null) {
	$container = $container ?: Container::getInstance();
	if (!$abstract) {
		return $container;
	}
	return $container->makeWith($abstract, $parameters);
}
