<?php
namespace App;


add_filter('pre_option_permalink_structure', function() {
    return '/%postname%/';
});
