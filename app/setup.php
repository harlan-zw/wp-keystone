<?php

namespace App;

use App\Drivers\TransientStore;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Job;
use App\Models\Partner;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\Testimonial;
use App\Services\CauseviewService;
use App\Services\PaypalService;
use App\Services\SalesforceService;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container as IlluminateContainer;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Container;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

const PRIMARY_NAV_SLUG = 'primary_navigation';
const FOOTER_NAV_SLUG = 'footer_navigation';

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('app/main.css', asset_path('styles/main.css'), [], null);
    wp_enqueue_script('app/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

    // add global options to our window.mcgrathSettings
	wp_localize_script('app/main.js', 'appSettings', [
		'distPath' => config('assets.uri')
	]);
}, 100);


/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
	'index',
	'404',
	'archive',
	'author',
	'category',
	'tag',
	'taxonomy',
	'date',
	'home',
	'frontpage',
	'page',
	'paged',
	'search',
	'single',
	'singular',
	'attachment'
])->map(function($type) {
	add_filter("{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function($template) {
	$data = collect(get_body_class())->reduce(function($data, $class) use ($template) {
		return apply_filters("sage/template/{$class}/data", $data, $template);
	}, []);
	if ($template) {
		echo template($template, $data);
		return get_stylesheet_directory() . '/index.php';
	}
	return $template;
}, PHP_INT_MAX);


/**
 * Theme setup
 */
add_action('after_setup_theme', function() {


	/**
	 * Add JsonManifest to Sage container
	 */
	sage()->singleton('sage.assets', function() {
		return new JsonManifest(config('assets.manifest'), config('assets.uri'));
	});

	/**
	 * Add Blade to Sage container
	 */
	sage()->singleton('sage.blade', function(Container $app) {
		$cachePath = config('view.compiled');
		if (!file_exists($cachePath)) {
			wp_mkdir_p($cachePath);
		}
		(new BladeProvider($app))->register();
		return new Blade($app['view']);
	});

	/**
	 * Create @asset() Blade directive
	 */
	sage('blade')->compiler()->directive('asset', function($asset) {
		return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
	});


	add_action('init', function() {
		/**
		 * Anything in the rewrites config is registered
		 */
		collect(config('rewrites.rules'))->each(function($route, $regex) {
			add_rewrite_rule($regex, $route, 'top');
		});

	});

	add_filter('query_vars', function($vars) {
		return array_merge($vars, config('rewrites.query_vars'));
	});

	/**
	 * Create @dd() Blade directive
	 */
	sage('blade')->compiler()->directive('dd', function($arg) {
		return "<?php dd($arg); ?>";
	});
	/**
	 * Create @template() Blade directive
	 */
	sage('blade')->compiler()->directive('template', function($arg) {
		return "<?= \\App\\template($arg); ?>";
	});

	/**
	 * Our file systems. This controls files in and out of our s3 buckets.
	 */
	sage()->singleton('filesystem', function() {
		$manager = new MountManager();

		collect(config('filesystems.disks'))->each(function($disk, $name) use ($manager) {
			switch ($disk['driver']) {
				case 'local':
					$adapter = new Local($disk['root']);
					break;
				default:
					throw new \Exception('Unhandled disk driver ' . $disk['driver'] . ' please use a supported one.');
			}
			if (!empty($adapter)) {
				$manager->mountFilesystem($name, new Filesystem($adapter, [
					'visibility' => $disk['visibility'] ?? AdapterInterface::VISIBILITY_PUBLIC
				]));
			}
		});

		return $manager;
	});

	/**
	 * ===========
	 * Logging
	 * ===========
	 *
	 * Load the log configuration from the config/logging.php file. We setup a new logger instance for each channel
	 * we have at the container singleton of logger.<channel-name>.
	 */

	foreach(config('logging.channels') as $name => $channel) {
		sage()->singleton('logger.' . $name, function() use ($name, $channel) {
			$log = new \Illuminate\Log\Writer(new \Monolog\Logger($name));
			switch($channel['driver']) {
				case 'single':
					$log->useFiles($channel['path'], $channel['level']);
					break;
				case 'daily':
					$log->useDailyFiles($channel['path'], $channel['days'], $channel['level']);
					break;
			}
			return $log;
		});
	}

	// set the default logger
	sage()->singleton('logger', function() {
		return sage('logger.' . config('logging.default'));
	});

	/**
	 * Instantiate the Salesforce service
	 */
	sage()->singleton('cache', function() {
		$container = new IlluminateContainer;
		$config = ['cache' => config('cache')];
		$container['config'] = nested_array_to_dot_syntax($config, 3);

		$cacheManager = new CacheManager($container);

		/** If we want file based caching */
		$container['files'] = new \Illuminate\Filesystem\Filesystem();

		$cacheManager->extend('transient', function ($app) use ($cacheManager) {
			/** Closure used to instatiate the transient store only if it is used */
			return $cacheManager->repository(new TransientStore('mcf_'));
		});

		return $cacheManager;
	});


	/**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));


    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        PRIMARY_NAV_SLUG => 'Primary Navigation',
        FOOTER_NAV_SLUG => 'Footer Navigation',
    ]);



}, 20);


/**
 * Register sidebars
 */
add_action('widgets_init', function() {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ];
    register_sidebar([
            'name' => __('Primary', 'sage'),
            'id' => 'sidebar-primary'
        ] + $config);
    register_sidebar([
            'name' => __('Footer', 'sage'),
            'id' => 'sidebar-footer'
        ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function($post) {
    sage('blade')->share('post', $post);
});
