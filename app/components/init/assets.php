<?php
namespace App;


/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function() {
	wp_enqueue_style('app/main.css', asset_path('styles/main.css'), [], null);
	wp_enqueue_script('app/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

	// add global options to our window.mcgrathSettings
	wp_localize_script('app/main.js', 'appSettings', [
		'distPath' => config('assets.uri')
	]);
}, 100);
