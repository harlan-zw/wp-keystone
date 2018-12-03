/*
 |--------------------------------------------------------------------------
 | Laravel Mix
 |--------------------------------------------------------------------------
 |
 | All assets are built using Laravel Mix. For full details on how to use it
 | checkout the documentation:
 |
 | https://laravel.com/docs/5.6/mix
 |
 */

const mix = require('laravel-mix');
// custom packages
const StyleLintPlugin = require('stylelint-webpack-plugin');

/*
 |--------------------------------------------------------------------------
 | Configure Mix
 |--------------------------------------------------------------------------
 |
 | Mix does about 80% of what we need it to do. Here we customise our paths
 | and fill in the gaps for what mix isn't doing. Namely the jQuery dependency
 | because it's loaded by WordPress and for simplicity we won't change this.
 |
 | We also setup style linting for our scss files.
 |
 */
mix
// set web root
    .setPublicPath('web')
    // which files to pass to ProvidePlugin
    .autoload({
        jquery: ['$','jQuery', 'window.jQuery']
    })
    // extend webpack and put our own configuration
    .webpackConfig({
        // which js files are loaded outside of webpack
        externals: {
            // jQuery is loaded through WP
            "jquery": "jQuery"
        },
        plugins: [
            // make sure our scss is consistent
            new StyleLintPlugin({
                files: './resources/assets/scss/**/*.scss',
            }),
        ]
    });

/*
 |--------------------------------------------------------------------------
 | Mix configured based on environment
 |--------------------------------------------------------------------------
 |
 | For our production environment we version our files so that we can implement
 | cache bursting. We disable notifications as production servers will not have
 | a gui.
 |
 | For our non-production environments we generate source maps so that we can
 | debug our assets easier.
 |
 */
if (mix.inProduction()) {
    mix.version();
    mix.disableNotifications();
} else {
    mix.sourceMaps();
}

/*
 |--------------------------------------------------------------------------
 | Mix Build Assets
 |--------------------------------------------------------------------------
 |
 | Build the assets now that we have configured mix. By default we have our
 | main js/scss which is loaded by the frontend and the admin js/scss which
 | is loaded when you are inside wp-admin.
 |
 */
const distPath = 'web/dist';
const assetPath = 'resources';

// frontend assets
mix
    .js(assetPath + '/js/main.js', distPath)
    .sass(assetPath + '/scss/main.scss', distPath);

// backend assets
mix
    .js(assetPath + '/js/admin.js', distPath)
    .sass(assetPath + '/scss/admin.scss', distPath);
