<?php
namespace App;

if (is_admin()) {
    // Admin assets
    add_action('admin_enqueue_scripts', function() {
        $admin_assets_key = config('assets.admin_assets_key');
        wp_enqueue_style(
            'main/' . $admin_assets_key . '.css',
            asset_path('styles/' . $admin_assets_key . '.css'),
            false,
            null
        );
        wp_enqueue_script(
            'main/' . $admin_assets_key . '.js',
            asset_path('scripts/' . $admin_assets_key . '.js'),
            ['jquery'],
            null,
            true
        );
    }, 100);

    return;
}

$public_assets_key = config('assets.public_assets_key');

// Frontend assets
wp_enqueue_style(
    'app/' . $public_assets_key . '.css',
    asset_path($public_assets_key . '.css'),
    [],
    null
);
wp_enqueue_script(
    'app/' . $public_assets_key . '.js',
    asset_path($public_assets_key . '.js'),
    ['jquery'],
    null,
    true
);

// add global options to our window.appSettingas
wp_localize_script('app/' . $public_assets_key . '.js', 'appSettings', [
    'distPath' => config()->get('assets.uri')
]);
