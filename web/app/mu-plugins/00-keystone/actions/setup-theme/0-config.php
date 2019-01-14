<?php

namespace App;

use Illuminate\Config\Repository;

/*
 * Register a config container so we can read our configuration files
 */

app()->singleton('config', function () {
    $files = collect(get_files_recursive(ROOT_DIR.'/config/app/', '/\.php$/'))
        ->mapWithKeys(function ($file) {
            return [basename($file, '.php') => require $file];
        })->toArray();

    return new Repository($files);
});

/**
 * Get the specified configuration value.
 *
 * @param bool $index
 *
 * @return Repository
 */
function config($index = false)
{
    if (!empty($index)) {
        return app('config')->get($index);
    }

    return app('config');
}
