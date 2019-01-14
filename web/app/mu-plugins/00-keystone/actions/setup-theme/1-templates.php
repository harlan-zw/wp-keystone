<?php

namespace App;

/*
 * Setup templating to use blade files loaded from our own view directory
 */

add_filter('theme_file_path', function () {
    return get_view_path();
}, PHP_INT_MAX);
add_filter('template_directory', function () {
    return get_view_path();
}, PHP_INT_MAX);

/**
 * Get the path to the view files.
 *
 * @return string
 */
function get_view_path()
{
    return ROOT_DIR.'/resources/views';
}

/**
 * @param string|string[] $templates Relative path to possible template files
 *
 * @return string Location of the template
 */
function locate_template($templates)
{
    $templates = filter_templates($templates);

    return \locate_template($templates);
}

/**
 * @param string|string[] $templates Possible template files
 *
 * @return array
 */
function filter_templates($templates)
{
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

/*
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index',
    '404',
    'archive',
    'author',
    'category',
    'tag',
    'taxonomy',
    'date',
    'home',
    'frontpage',
    'page',
    'paged',
    'search',
    'single',
    'singular',
    'attachment',
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__.'\\filter_templates', PHP_INT_MAX);
});

/*
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("template/{$class}/data", $data, $template);
    }, []);
    if ($template) {
        echo template($template, $data);

        return get_stylesheet_directory().'/index.php';
    }

    return $template;
}, PHP_INT_MAX);
