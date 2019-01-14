<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Theme Name
    |--------------------------------------------------------------------------
    |
    | The name of the theme that will be loaded from the themes folder.
    |
    */
    'theme_name' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | The version of Keystone being used
    |
    */
    'version' => '0.6',

    'features' => [
        'disable_comments' => true,
        'bottom_admin_bar' => true
    ],

    'fix_wp' => [
        /*
        |--------------------------------------------------------------------------
        | Remove Image Paragraph Tag.
        |--------------------------------------------------------------------------
        |
        | This will occasionally cause styling issue to occur when an image is
        | inside a paragraph.
        */
        'remove_img_p_tags' => true,

        'jquery' => [
            /*
            |--------------------------------------------------------------------------
            | Use jQuery CDN
            |--------------------------------------------------------------------------
            |
            | To avoid plugin compatibility issues, we need to load in jQuery. But we
            | want to try and avoid packing it with laravel mix or serving it ourselves.
            | We will offload it.
            */
            'use_cdn' => true,

            /*
            |--------------------------------------------------------------------------
            | jQuery CDN URL
            |--------------------------------------------------------------------------
            |
            | Which jQuery version to load and from where. Note that this will try and
            | load in a non minified version if we're on development.
            |
            */
            'jquery_cdn_url' => '//code.jquery.com/jquery-3.2.1.min.js',

            /*
            |--------------------------------------------------------------------------
            | jQuery Migrate CDN URL
            |--------------------------------------------------------------------------
            |
            | Which jQuery migrate version to load and from where. Note that this will
            | try and load in a non minified version if we're on development.
            |
            */
            'jquery_migrate_cdn_url' => '//code.jquery.com/jquery-migrate-3.2.1.min.js',
        ],

        /*
        |--------------------------------------------------------------------------
        | Use HTML5 Shim For IE
        |--------------------------------------------------------------------------
        |
        | This can fix up some IE related issues, it will only load on IE so no harm
        | including this
        |
        */
        'use_html5_shim_for_ie' => true,

        /*
        |--------------------------------------------------------------------------
        | Remove Script Versions
        |--------------------------------------------------------------------------
        |
        | Script versions cause browser caching issues. For simplicity and because
        | we already have cache bursting through laravel mix, we disable versions
        |
        */
        'remove_script_versions' => true,

        /*
        |--------------------------------------------------------------------------
        | Disable Emojis
        |--------------------------------------------------------------------------
        |
        | Why would you want WordPress emojis loaded on every page?
        |
        */
        'disable_emojis' => true,

        /*
        |--------------------------------------------------------------------------
        | Clean Head Output
        |--------------------------------------------------------------------------
        |
        | Removes some of the more junkier tags from the html <head> output
        |
        */
        'clean_head_output' => true,

        /*
        |--------------------------------------------------------------------------
        | Force Scripts In Footer
        |--------------------------------------------------------------------------
        |
        | For performance reasons, we shouldn't really load any scripts before the
        | rest of the HTML has been passed
        |
        */
        'force_scripts_in_footer' => true,

        /*
        |--------------------------------------------------------------------------
        | Remove WordPress Version
        |--------------------------------------------------------------------------
        |
        | This can be a problem when you don't update WordPress, usually a good
        | idea to hide it
        |
        */
        'remove_wp_version' => true,

        /*
        |--------------------------------------------------------------------------
        | Use Polyfill.io
        |--------------------------------------------------------------------------
        |
        | Polyfill.io is a shim as a service website, it will detect any features
        | the users browser is missing and load in a script to fix them. Very good
        | if you're using features not all browsers support.
        |
        */
        'use_pollyfill_io' => true,

        /*
        |--------------------------------------------------------------------------
        | Nice Body Classes
        |--------------------------------------------------------------------------
        |
        | Gets ride of all of the junky classes added the <body>
        |
        */
        'nice_body_classes' => true,

        /*
        |--------------------------------------------------------------------------
        | Remove Empty Paragraph Tags
        |--------------------------------------------------------------------------
        |
        | Will remove <p></p> from WYSIWYG output
        |
        */
        'remove_empty_p_tags' => true,
    ],

];
