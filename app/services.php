<?php

namespace App;


/**
 * Theme setup
 */
add_action('after_setup_theme', function() {


	/**
	 * Add JsonManifest to Sage container
	 */
	app()->singleton('sage.assets', function() {
		return new JsonManifest(config('assets.manifest'), config('assets.uri'));
	});

	/**
	 * Add Blade to Sage container
	 */
	app()->singleton('sage.blade', function(Container $app) {
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
	app('blade')->compiler()->directive('asset', function($asset) {
		return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
	});

	/**
	 * Create @dd() Blade directive
	 */
	app('blade')->compiler()->directive('dd', function($arg) {
		return "<?php dd($arg); ?>";
	});
	/**
	 * Create @template() Blade directive
	 */
	app('blade')->compiler()->directive('template', function($arg) {
		return "<?= \\App\\template($arg); ?>";
	});

	/**
	 * Our file systems. This controls files in and out of our s3 buckets.
	 */
	app()->singleton('filesystem', function() {
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
		app()->singleton('logger.' . $name, function() use ($name, $channel) {
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
	app()->singleton('logger', function() {
		return app('logger.' . config('logging.default'));
	});

	/**
	 * Instantiate the Salesforce service
	 */
	app()->singleton('cache', function() {
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


}, 20);
