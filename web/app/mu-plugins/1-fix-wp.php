<?php
/*
Plugin Name:  Fix Wordpress common issues
Plugin URI:   http://www.4mation.com.au/
Description:  Fixes common Wordpress issues, these are found from wpfixme and other sources.
Version:      1.0.0
Author:       Harlan Wilton
Author URI:   http://www.4mation.com.au/
License:      MIT License
*/

class FixMyWP {

    // Stop images getting wrapped up in p tags when they get dumped out with the_content() for easier theme styling
    public static function remove_img_ptags(){
        add_filter('the_content', function($content) {
            return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
        });
    }

// Call the google CDN version of jQuery for the frontend
// Make sure you use this with wp_enqueue_script('jquery'); in your header
    public static  function jquery_enqueue() {
        add_action("wp_enqueue_scripts", function() {
            if(is_admin() || is_login_page()) {
                return;
            }
            wp_deregister_script('jquery');
            wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js", false, null, true);
            wp_enqueue_script('jquery');
        }, 11);
    }

 /**
     * WordPress will try and be cool by modifying output, fixing new lines into paragraph tags, etc.
     * This breaks our styling sometimes we so need to disable it. This in particular breaks one of the
     * gravity forms fields by wrapping the span tags in p tags..
     */
    public static function fix_gravityforms_output() {
        add_filter('pre_do_shortcode_tag', function($unused, $tag) {
            if ($tag != 'gravityform') {
                return false;
            }
            remove_filter( 'the_content', 'wptexturize');
            remove_filter( 'the_content', 'wpautop');
            return false;
        }, 10, 2);
    }

// Call Googles HTML5 Shim, but only for users on old versions of IE
    public static  function IEhtml5_shim () {
        global $is_IE;
        add_action('wp_head', function() use($is_IE) {
            if ($is_IE)
                echo '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
        });
    }

    public static function remove_script_versions() {

        $strip_var = function($src) {
            $parts = explode( '?ver', $src );
            return $parts[0];
        };

        add_filter( 'script_loader_src', $strip_var, 15, 1 );
        add_filter( 'style_loader_src', $strip_var, 15, 1 );
    }

    public static  function disable_emojis() {
        /* Disable emoji scripts */
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    }

    public static  function clean_head() {
        /* remove useless header tags */
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');

        remove_action('wp_head', 'index_rel_link');
        remove_action( 'wp_head', 'feed_links_extra' );
    }

    public static  function force_footer_scripts() {
        /* move scripts to footer */
        remove_action('wp_head', 'wp_print_scripts');
        add_action('wp_footer', 'wp_print_scripts', 5);
    }

    public static function force_oembed_width() {
        global $content_width;
        // Set a maximum width for Oembedded objects
        if ( ! isset( $content_width ) )
            $content_width = 660;
    }

    public static function remove_wp_version() {
        remove_action('wp_head', 'wp_generator');
    }


}

function dd() {
    pre_dump(func_get_args());
    die(1);
}

function pre_dump() {
    array_map(function ($x) {
        echo '<pre style="background-color: white; padding: 0.5em; border: 1px solid #f0f0f0;">';
        var_dump($x);
        echo '</pre>';
        echo '<script> console.log(' . json_encode(print_r($x, true)) . ');</script>';
    }, func_get_args());
}

/* utility functions */
function is_env_dev() {
    return WP_ENV == 'development';
}
function is_env_production() {
    return !is_env_dev() && !is_env_staging();
}
function is_env_staging() {
    return WP_ENV == 'staging';
}

function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

// Put in the functions we want to run here
FixMyWP::remove_img_ptags();
FixMyWP::jquery_enqueue();
FixMyWP::IEhtml5_shim();
FixMyWP::remove_script_versions();
FixMyWP::disable_emojis();
FixMyWP::clean_head();
FixMyWP::force_footer_scripts();
FixMyWP::force_oembed_width();
FixMyWP::remove_wp_version();
FixMyWP::fix_gravityforms_output();
