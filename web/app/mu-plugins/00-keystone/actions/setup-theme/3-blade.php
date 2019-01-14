<?php

namespace App;

use Illuminate\Container\Container;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/*
 * Add Blade to container
 */
app()->singleton('blade', function (Container $app) {
    $cachePath = config('view.compiled');
    if (!file_exists($cachePath)) {
        wp_mkdir_p($cachePath);
    }
    (new BladeProvider($app))->register();

    return new Blade($app['view']);
});

/**
 * @return Blade
 */
function blade()
{
    return app('blade');
}

blade()->compiler()->directive('asset', function ($arg) {
    return '<?= '.__NAMESPACE__."\\asset_path({$arg}); ?>";
});

blade()->compiler()->directive('dd', function ($arg) {
    return "<?php dd($arg); ?>";
});

blade()->compiler()->directive('template', function ($arg) {
    return "<?= \\App\\template($arg); ?>";
});

/*
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    app('blade')->share('post', $post);
});

/**
 * @param string $file
 * @param array  $data
 *
 * @return string
 */
function template($file, $data = [])
{
    return app('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view.
 *
 * @param $file
 * @param array $data
 *
 * @return string
 */
function template_path($file, $data = [])
{
    return app('blade')->compiledPath($file, $data);
}
