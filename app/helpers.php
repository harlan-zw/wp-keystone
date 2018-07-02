<?php

namespace App;

use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Log\Writer;
use Illuminate\Support\Str;
use Roots\Sage\Assets\JsonManifest;

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
        return sage('config');
    }
    if (is_array($key)) {
        return sage('config')->set($key);
    }
    return sage('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = []) {
    return sage('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = []) {
    return sage('blade')->compiledPath($file, $data);
}

/**
 * @return JsonManifest $manifest
 */
function assets() {
	return sage('assets');
}
/**
 * @param $asset
 * @return string
 */
function asset_path($asset) {
    return assets()->getUri($asset);
}



/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates) {
    return \locate_template(filter_templates($templates));
}



/**
 * Page titles
 * @return string
 */
function title() {
    if (is_home()) {
        if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
        }
        return __('Latest Posts', 'sage');
    }
    if (is_archive()) {
        return get_the_archive_title();
    }
    if (is_search()) {
        return sprintf(__('Search Results for %s', 'sage'), get_search_query());
    }
    if (is_404()) {
        return __('Not Found', 'sage');
    }
    return get_the_title();
}

/**
 * Uses the Yoast SEO configuration to find which social medias are linked and return them
 * @return array
 */
function get_social_medias() {
    $social_profiles = [
        'fa-facebook-square' => 'facebook_site',
        'fa-twitter-square' => 'twitter_site',
        'fa-instagram' => 'instagram_url',
        'fa-linkedin' => 'linkedin_url',
        'fa-google-plus' => 'google_plus_url',
        'myspace_url',
        'fa-youtube-square' => 'youtube_url',
        'fa-pinterest-square' => 'pinterest_url',
    ];

    $social_medias = get_option('wpseo_social');
    $active = [];

    foreach ($social_profiles as $icon => $profile) {
        if (!empty($social_medias[$profile])) {
            $url = $social_medias[$profile];
            if ($profile === 'twitter_site') {
                $url = 'https://twitter.com/' . $url;
            }
            $active[] = [
                'url' => $url,
                'icon' => $icon
            ];
        }
    }
    return $active;
}

function get_main_logo_markup($class) {
    $field = get_option_page_value(OPTION_PAGE_MAIN_LOGO);
    return wp_get_attachment_image($field['ID'], ['110', '67'], false, [
        'class' => 'logo ' . $class
    ]);
}

function get_alt_logo_markup($class) {
    $field = get_option_page_value(OPTION_PAGE_ALT_LOGO);
    return wp_get_attachment_image($field['ID'], ['110', '67'], false, [
        'class' => 'logo ' . $class
    ]);
}


/**
 * Format a phone number to be tel URI scheme consistent
 *
 * 1. Strip all non-digits
 * 2. Only prefix with a + if the phone number already contains a prefix.
 *
 * @param string $phone_number
 * @param string $prefix
 * @return string
 */
function get_URI_formatted_phone_number($phone_number) {

    // always strip spaces
    $phone_number = str_replace(' ', '', $phone_number);
    // Only prefix it if it doesn't start with a 1300
    if (!Str::startsWith($phone_number, '1300')
        && !Str::startsWith($phone_number, '0800')
        && !Str::startsWith($phone_number, '+')) {
        $URI_formatted_phone_number = $phone_number;
        // if first character is a 0 - we drop it
        if (Str::startsWith($phone_number, '0')) {
            $URI_formatted_phone_number =
                Str::substr($URI_formatted_phone_number, 1, strlen($URI_formatted_phone_number) - 1);
        }
        // get rid of spaces and non digits
        $URI_formatted_phone_number = preg_replace('/[^\d]/', '', $URI_formatted_phone_number);
        return get_option_page_value(OPTION_PAGE_PHONE_PREFIX) . $URI_formatted_phone_number;
    }

    return $phone_number;
}


function get_option_page_value($option) {
    return get_field($option, 'option');
}




/**
 * Our file system for our uploaded files on our local file system
 *
 * @return Filesystem
 */
function files_uploads() {
    $fs = sage('filesystem');
    return $fs->getFilesystem('uploads');
}

/**
 * Our file system for our runtime directory
 *
 * @return FileSystem
 */
function runtime() {
    /** @var MountManager $fs */
    $fs = sage('filesystem');
    return $fs->getFilesystem('runtime');
}

/**
 * Logger instance for writing logs
 * @return Writer
 */
function log() {
	return sage('logger');
}


/**
 * Get our cache service
 *
 * @return \Illuminate\Contracts\Cache\Repository
 */
function cache() {
    /** @var CacheManager $cache_manager */
    return sage('cache')->store();
}
