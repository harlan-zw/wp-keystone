<?php

namespace App;
/**
 * Add <body> classes
 */
add_filter('body_class', function(array $classes) {
	/** Add page slug if it doesn't exist */
	if (is_single() || (is_page() && !is_front_page())) {
		if (!\in_array(basename(get_permalink()), $classes)) {
			$classes[] = basename(get_permalink());
		}
	}

	/** Add class if sidebar is active */
	if (display_sidebar()) {
		$classes[] = 'sidebar-primary';
	}

	/** Clean up class names for custom templates */
	$classes = array_map(function($class) {
		return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
	}, $classes);

	return array_filter($classes);
});




add_filter('development-environment/is-local-host', function() {
	return is_env_dev();
});
