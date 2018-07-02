<?php

namespace App;

use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Log\Writer;
use Illuminate\Support\Str;
use League\Flysystem\MountManager;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Container;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Get the sage container.
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
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("sage.{$abstract}", $parameters);
}

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
    if (is_null($key)) {
        return app('config');
    }
    if (is_array($key)) {
        return app('config')->set($key);
    }
    return app('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = []) {
    return app('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = []) {
    return app('blade')->compiledPath($file, $data);
}

/**
 * @return JsonManifest $manifest
 */
function assets() {
	return app('assets');
}
/**
 * @param $asset
 * @return string
 */
function asset_path($asset) {
    return assets()->getUri($asset);
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates($templates) {
    return collect($templates)
        ->map(function ($template) {
            return preg_replace('#\.(blade\.)?php$#', '', ltrim($template));
        })
        ->flatMap(function ($template) {
            $paths = config('view.paths');
            return collect($paths)
                ->flatMap(function ($path) use ($template) {
                    return [
                        "{$path}/{$template}.blade.php",
                        "{$path}/{$template}.php",
                        "{$template}.blade.php",
                        "{$template}.php",
                    ];
                });
        })
        ->filter()
        ->unique()
        ->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates) {
    return \locate_template(filter_templates($templates));
}


/**
 * Recursive function to turn nested arrays into a dot style syntax to the specified depth
 * ['one => ['two' => 'three']] would become ['one.two' => 'three']
 * @param $array
 * @param int $maxDepth
 * @return array
 */
function nested_array_to_dot_syntax($array, $maxDepth = 99) {
    $ritit = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
    $result = [];
    foreach ($ritit as $leafValue) {
        $keys = [];
        foreach (range(0, $ritit->getDepth()) as $depth) {
            if ($depth === $maxDepth) {
                $leafValue = $ritit->getSubIterator($depth - 1)->current();
                break;
            }
            $keys[] = $ritit->getSubIterator($depth)->key();
        }
        $result[join('.', $keys)] = $leafValue;
    }
    return $result;
}
