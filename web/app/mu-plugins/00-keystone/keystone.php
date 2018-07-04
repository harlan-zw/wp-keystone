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

require __DIR__ . '/bootstrap.php';

// load in the current directory as a keystone directory - autoload files
load_keystone_directory(__DIR__);
