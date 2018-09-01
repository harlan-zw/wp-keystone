<?php
namespace App;

wp_enqueue_style('app/main.css', asset_path('styles/main.css'), [], null);
wp_enqueue_script('app/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

// add global options to our window.mcgrathSettings
wp_localize_script('app/main.js', 'appSettings', [
	'distPath' => config()->get('assets.uri')
]);