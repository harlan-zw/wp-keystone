<?php

namespace App;

// links will be slugs e.g. /my-post-name/
add_filter('pre_option_permalink_structure', function () {
    return '/%postname%/';
});

// Set the theme
add_filter('pre_option_current_theme', function () {
    return 'Main Theme';
});
