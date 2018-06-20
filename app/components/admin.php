<?php

namespace App;

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('mcgrath/admin.css', asset_path('styles/admin.css'), false, null);
    wp_enqueue_script('mcgrath/admin.js', asset_path('scripts/admin.js'), ['jquery'], null, true);
}, 100);

/**
 * Make the admin login screen slightly nicer and branded
 */
add_action('login_enqueue_scripts', function() {
    $field = get_option_page_value(OPTION_PAGE_MAIN_LOGO);
    $src = wp_get_attachment_image_src($field['ID'], ['110', '67'], false);
    ?>
    <style type="text/css">
        body.login {
            background-color: #<?= COLOUR_HOT_PINK ?>;
        }

        body.login div#login h1 a {
            background-image: url(<?= $src[0] ?>);
            background-size: 80% 80%;
            width: 100%;
            background-position: 25px;
            height: 100px;
        }

        .login #nav a, .login #backtoblog a {
            color: white !important;
        }
    </style>
    <?php
});


/**
 * Hide the menus that aren't needed for the existing User
 **/
add_action('admin_menu', function() {
	// If not super admin - hide pages they don't need
	if (!is_super_admin()) {
		remove_menu_page('plugins.php');                //Plugins
		remove_menu_page('options-general.php');        //Settings
		remove_menu_page('tools.php');        //Settings
		remove_menu_page('acf-field-group.php');        //ACF
	}
	// Always hide posts and comments
	remove_menu_page('edit-comments.php'); // Post Comments
});

/**
 * Hide ACF
 */
add_filter('acf/settings/show_admin', function() {
	return current_user_can('manage_options');
});
