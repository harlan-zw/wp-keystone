<?php

/**
 * Hide the menus that aren't needed for the existing User
 **/
add_action('admin_menu', function () {
    // If not super admin - hide pages they don't need
    if (!is_super_admin()) {
        remove_menu_page('plugins.php');                //Plugins
        remove_menu_page('options-general.php');        //Settings
        remove_menu_page('tools.php');                  //Settings
        remove_menu_page('acf-field-group.php');        //ACF
    }
    // Always hide posts and comments
    remove_menu_page('edit-comments.php');              // Post Comments
});
