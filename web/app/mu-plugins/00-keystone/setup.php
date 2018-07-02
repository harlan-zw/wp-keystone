<?php

namespace App;


/**
 * Theme setup
 */
add_action('after_setup_theme', function() {


	/**
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
		'attachment'
	])->map(function($type) {
		add_filter("{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates');
	});

	/**
	 * Render page using Blade
	 */
	add_filter('template_include', function($template) {
		$data = collect(get_body_class())->reduce(function($data, $class) use ($template) {
			return apply_filters("sage/template/{$class}/data", $data, $template);
		}, []);
		if ($template) {
			echo template($template, $data);
			return get_stylesheet_directory() . '/index.php';
		}
		return $template;
	}, PHP_INT_MAX);


}, 20);



add_filter('development-environment/is-local-host', function() {
	return is_env_dev();
});


/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function($post) {
	app('blade')->share('post', $post);
});





add_filter('theme_file_path', 'get_view_path');
add_filter('template_directory', 'get_view_path');

function get_view_path() {
	return ROOT_DIR . '/resources/views';
}


add_action('init', function() {
	/**
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
		'attachment'
	])->map(function($type) {
		add_filter("{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates');
	});

	/**
	 * Render page using Blade
	 */
	add_filter('template_include', function($template) {
		$data = collect(get_body_class())->reduce(function($data, $class) use ($template) {
			return apply_filters("sage/template/{$class}/data", $data, $template);
		}, []);
		if ($template) {
			echo template($template, $data);
			return get_stylesheet_directory() . '/index.php';
		}
		return $template;
	}, PHP_INT_MAX);
});



/**
 * Hooks a single callback to multiple tags
 */
function add_filters($tags, $function, $priority = 10, $accepted_args = 1) {
	foreach ((array)$tags as $tag) {
		add_filter($tag, $function, $priority, $accepted_args);
	}
}



