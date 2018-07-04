<?php
namespace App;

/**
 * Instantiate the Salesforce service
 */
app()->singleton('cache', function() {
	$container = new IlluminateContaine();
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
 * Get our cache service
 *
 * @return \Illuminate\Contracts\Cache\Repository
 */
function cache() {
	/** @var CacheManager $cache_manager */
	return app('cache')->store();
}
