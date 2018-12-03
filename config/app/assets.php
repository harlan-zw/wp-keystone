<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Assets Manifest
    |--------------------------------------------------------------------------
    |
    | Your asset manifest is used by Sage to assist WordPress and your views
    | with rendering the correct URLs for your assets. This is especially
    | useful for statically referencing assets with dynamically changing names
    | as in the case of cache-busting.
    |
    */

    'manifest' => WEB_DIR.'/mix-manifest.json',

    /*
    |--------------------------------------------------------------------------
    | Assets Path URI
    |--------------------------------------------------------------------------
    |
    | The asset manifest contains relative paths to your assets.
    |
    */

    'uri' => WP_HOME.'/dist',

    /*
    |--------------------------------------------------------------------------
    | Public Assets Key
    |--------------------------------------------------------------------------
    |
    | The naming of the main.css and main.js, this can be changed to anything
    |
    */

    'public_assets_key' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Admin Assets Key
    |--------------------------------------------------------------------------
    |
    | The naming of the admin.css and admin.js, this can be changed to anything
    |
    */

    'admin_assets_key' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Defer All Scripts
    |--------------------------------------------------------------------------
    |
    | Make sure we only start loading our scripts when the rest of the page
    | has finished loading
    |
    */

    'defer_scripts' => true,
];
