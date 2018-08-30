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

    'manifest' => WEB_DIR . '/mix-manifest.json',

    /*
    |--------------------------------------------------------------------------
    | Assets Path URI
    |--------------------------------------------------------------------------
    |
    | The asset manifest contains relative paths to your assets.
    |
    */

    'uri' => WEB_DIR . '/dist',
];
