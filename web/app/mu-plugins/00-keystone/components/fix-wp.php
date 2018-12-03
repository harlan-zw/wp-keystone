<?php
namespace App;

class FixMyWP {

    // Stop images getting wrapped up in p tags when they get dumped out with the_content() for easier theme styling
    public static function remove_img_ptags() {
        add_filter('the_content', function ($content) {
            return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
        });
    }

    /**
     * Replaces the WordPress jQuery version with the latest + the migration dependency.
     */
    public static function jquery_enqueue($config = []) {
        add_action('wp_enqueue_scripts', function () use ($config) {
            if (is_admin() || is_login_page()) {
                return;
            }
            wp_deregister_script('jquery');
            wp_deregister_script('jquery-migrate');
            $jQueryCDN = $config['jquery_cdn_url'];
            $jQueryMigrateCDN = $config['jquery_migrate_cdn_url'];
            if (is_env_dev()) {
                $jQueryCDN = str_replace('min.', '', $jQueryCDN);
                $jQueryMigrateCDN = str_replace('min.', '', $jQueryMigrateCDN);
            }
            wp_register_script('jquery', $jQueryCDN, false, null, true);
            wp_register_script('jquery-migrate', $jQueryMigrateCDN, false, null, true);
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-migrate');
        }, 11);
    }


// Call Googles HTML5 Shim, but only for users on old versions of IE
    public static function IEhtml5_shim() {
        global $is_IE;

        add_action('wp_head', function () use ($is_IE) {
            if ($is_IE) {
                echo '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
            }
        });
    }

    public static function remove_script_versions() {

        $strip_var = function ($src) {
            $parts = explode('?ver', $src);
            return $parts[0];
        };

        add_filter('script_loader_src', $strip_var, 15, 1);
        add_filter('style_loader_src', $strip_var, 15, 1);
    }

    public static function disable_emojis() {
        /* Disable emoji scripts */
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
    }

    public static function clean_head() {
        /* remove useless header tags */
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');

        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'feed_links_extra');
    }

    public static function force_footer_scripts() {
        /* move scripts to footer */
        remove_action('wp_head', 'wp_print_scripts');
        add_action('wp_footer', 'wp_print_scripts', 5);
    }

    public static function remove_wp_version() {
        remove_action('wp_head', 'wp_generator');
    }

	public static function remove_empty_p_tags() {
    	add_filter('the_content', function($content) {
		    $content = force_balance_tags( $content );
		    $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
		    $content = preg_replace( '~\s?<p>(\s| )+</p>\s?~', '', $content );
		    return $content;
	    }, 999, 1);
	}

    public static function include_pollyfill_io() {
        add_action('wp_enqueue_scripts', function () {
            if (is_admin() || is_login_page()) {
                return;
            }
            $min = is_env_dev() ? '' : 'min.';
            wp_register_script('pollyfill.io', 'https://cdn.polyfill.io/v2/polyfill.' . $min . 'js');

            wp_enqueue_script('pollyfill.io');
        });
    }

    public static function nice_body_classes() {
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

		    /** Clean up class names for custom templates */
		    $classes = array_map(function($class) {
			    return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
		    }, $classes);

		    return array_filter($classes);
	    });

    }

}

$fix_wp_config = config('keystone.fix-wp');

// Put in the functions we want to run here
if ($fix_wp_config->get('remove_img_p_tags')) {
    FixMyWP::remove_img_ptags();
}
if ($fix_wp_config->get('jquery.use_cdn')) {
    FixMyWP::jquery_enqueue($fix_wp_config->get('jquery'));
}
if ($fix_wp_config->get('use_html5_shim_for_ie')) {
    FixMyWP::IEhtml5_shim();
}
if ($fix_wp_config->get('remove_script_versions')) {
    FixMyWP::remove_script_versions();
}
if ($fix_wp_config->get('disable_emojis')) {
    FixMyWP::disable_emojis();
}
if ($fix_wp_config->get('clean_head_output')) {
    FixMyWP::clean_head();
}
if ($fix_wp_config->get('force_scripts_in_footer')) {
    FixMyWP::force_footer_scripts();
}
if ($fix_wp_config->get('remove_wp_version')) {
    FixMyWP::remove_wp_version();
}
if ($fix_wp_config->get('use_pollyfill_io')) {
    FixMyWP::include_pollyfill_io();
}
if ($fix_wp_config->get('use_pollyfill_io')) {
    FixMyWP::nice_body_classes();
}
if ($fix_wp_config->get('remove_empty_p_tags')) {
    FixMyWP::remove_empty_p_tags();
}
