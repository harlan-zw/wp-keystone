<?php

namespace App;

/**
 * Page titles.
 *
 * @return string
 */
function title()
{
    if (is_home()) {
        if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
        }

        return __('Latest Posts', 'wp-keystone');
    }
    if (is_archive()) {
        return get_the_archive_title();
    }
    if (is_search()) {
        return sprintf(__('Search Results for %s', 'wp-keystone'), get_search_query());
    }
    if (is_404()) {
        return __('Not Found', 'wp-keystone');
    }

    return get_the_title();
}

/**
 * Uses the Yoast SEO configuration to find which social medias are linked and return them.
 *
 * @return array
 */
function get_social_medias()
{
    $social_profiles = [
        'fa-facebook-square' => 'facebook_site',
        'fa-twitter-square'  => 'twitter_site',
        'fa-instagram'       => 'instagram_url',
        'fa-linkedin'        => 'linkedin_url',
        'fa-google-plus'     => 'google_plus_url',
        'myspace_url',
        'fa-youtube-square'   => 'youtube_url',
        'fa-pinterest-square' => 'pinterest_url',
    ];

    $social_medias = get_option('wpseo_social');
    $active = [];

    foreach ($social_profiles as $icon => $profile) {
        if (!empty($social_medias[$profile])) {
            $url = $social_medias[$profile];
            if ($profile === 'twitter_site') {
                $url = 'https://twitter.com/'.$url;
            }
            $active[] = [
                'url'  => $url,
                'icon' => $icon,
            ];
        }
    }

    return $active;
}

function get_main_logo_markup($class)
{
    $field = get_option_page_value(OPTION_PAGE_MAIN_LOGO);

    return wp_get_attachment_image($field['ID'], ['110', '67'], false, [
        'class' => 'logo '.$class,
    ]);
}

function get_alt_logo_markup($class)
{
    $field = get_option_page_value(OPTION_PAGE_ALT_LOGO);

    return wp_get_attachment_image($field['ID'], ['110', '67'], false, [
        'class' => 'logo '.$class,
    ]);
}

function get_option_page_value($option)
{
    return get_field($option, 'option');
}
