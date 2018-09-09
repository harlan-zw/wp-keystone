<?php
namespace App;


use Illuminate\Config\Repository;

app()->singleton('config', function() {
	$files = collect(get_files_recursive(ROOT_DIR . '/config/app/', '/\.php$/'))
		->mapWithKeys(function($file) {
			return [basename($file, '.php') => require $file];
		})->toArray();
	return new Repository($files);
});

/**
 * Get the specified configuration value.
 ***
 * @return Repository
 */
function config() {
	return app('config');
}
