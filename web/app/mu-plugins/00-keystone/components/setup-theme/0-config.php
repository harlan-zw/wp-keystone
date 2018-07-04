<?php
namespace App;

// require our application configuration
use Roots\Sage\Config;

app()->singleton('config', function() {
	$files = collect(get_files_recursive(ROOT_DIR . '/config/app/', '/\.php$/'))
		->mapWithKeys(function($file) {
			return [basename($file, '.php') => require $file];
		})->toArray();
	return new Config($files);
});

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @throws \Illuminate\Container\EntryNotFoundException
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null) {
	if (null === $key) {
		return app('config');
	}
	if (\is_array($key)) {
		return app('config')->set($key);
	}
	return app('config')->get($key, $default);
}
