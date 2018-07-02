<?php
/**
 * Plugin Name: Keystone
 * Plugin URI: https://github.com/roots/bedrock/
 * Description: Core functions for the keystone boilerplate
 * Version: 1.0.0
 * Author: Roots
 * Author URI: https://roots.io/
 * License: MIT License
 */
namespace App;

use Illuminate\Container\Container;

require __DIR__ . '/bootstrap.php';

global $app;
$app = new Container();

// load in the current directory as a keystone directory - autoload files
load_keystone_directory(__DIR__, $keystone);

/**
 * Get the app container.
 *
 * @param string $abstract
 * @param array $parameters
 * @param Container $container
 * @return Container|mixed
 */
function app($abstract = null, $parameters = [], Container $container = null) {
	$container = $container ?: Container::getInstance();
	if (!$abstract) {
		return $container;
	}
	return $container->bound($abstract)
		? $container->makeWith($abstract, $parameters)
		: $container->makeWith("sage.{$abstract}", $parameters);
}
