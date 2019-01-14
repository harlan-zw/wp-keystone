<?php

namespace App;

use Roots\Sage\Assets\JsonManifest;

/*
 * Setup utilities to find versioned assets
 */

/*
 * Add JsonManifest to Sage container
 */
app()->singleton('assets', function () {
    return new JsonManifest(config('assets.manifest'), config('assets.uri'));
});

/**
 * @return JsonManifest
 */
function assets()
{
    return app('assets');
}

/**
 * @param $asset
 *
 * @return string
 */
function asset_path($asset)
{
    return assets()->getUri($asset);
}
