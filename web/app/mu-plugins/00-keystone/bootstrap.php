<?php
namespace App;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

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
        $result[implode('.', $keys)] = $leafValue;
    }
    return $result;
}

function load_keystone_directory($directory) {

    // our magic file loader - for each folder in components we map to a wordpress action to load the files within
    $hooks_path = $directory . '/actions';
    collect(get_files_recursive($hooks_path, '/\.php$/'))
        // clean the files / paths
        ->map(function($file) use ($hooks_path) {
            return str_replace([ '.php', $hooks_path ], '', $file);
        })
        // group them by directory (if they have one)
        ->groupBy(function($file) {
            $path_info = pathinfo($file);
            return $path_info['dirname'];
        })
        ->each(function(Collection $files, $folder) use($hooks_path) {
            // need to sort it so naturally ordered files come first
            $files = $files->sort();

            $include_files = function() use ($files, $hooks_path) {
                $files->each(function($file) use ($hooks_path) {
                    if (file_exists($hooks_path . $file . '.php')) {
                        include $hooks_path . $file . '.php';
                    }
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
    // components directory
    add_action('init', function() use ($directory) {
        $components_path = $directory . '/components';
        collect(get_files_recursive($components_path, '/\.php$/'))
            // clean the files / paths
            ->map(function($file) use ($components_path) {
                return str_replace([ '.php', $components_path ], '', $file);
            })->each(function($file) use ($components_path) {
                if (file_exists($components_path . "{$file}.php")) {
                    require_once $components_path . "{$file}.php";
                }
            });
    });


    $preload_files
        ->map(function ($file) use ($directory) {
            return str_replace([ '.php', $directory ], '', $file);
        })
        ->filter(function ($f) {
            return !empty($f);
        })
        ->push('helpers')
        ->push('setup')
        ->each(function($file) use ($directory) {
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
