<?php
/*
Plugin Name:  Fix SVG Render
Plugin URI:   http://www.4mation.com.au/
Description:  Fixes the default output of the SVG image type
Version:      1.0.0
Author:       Harlan Wilton
Author URI:   http://www.4mation.com.au/
License:      MIT License
*/


/**
 * Sets a default image size of SVG's other then 1x1 so we can see them and style them appropriately
 */
add_filter('wp_get_attachment_image_src', function($image) {
    // make sure ends in svg
    if (stripos(strrev($image[0]), 'gvs.') === false) {
        return $image;
    }
    // with invalid sizes
    if ($image[1] != '1' && $image[2] != '1') {
        return $image;
    }

    $image[1] = 30;
    $image[2] = 30;
    return $image;
}, 9999);
