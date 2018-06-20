<?php

return [

	/*
	|--------------------------------------------------------------------------
	| View Storage Paths
	|--------------------------------------------------------------------------
	|
	| Most template systems load templates from disk. Here you may specify
	| an array of paths that should be checked for your views.
	|
	*/

	'paths' => [
		ROOT_DIR . '/resources/views',
	],


	/*
	|--------------------------------------------------------------------------
	| Compiled View Path
	|--------------------------------------------------------------------------
	|
	| This option determines where all the compiled Blade templates will be
	| stored for your application. We change this to outside of the webroot for security
	| reasons and to avoid uploading it to our s3 bucket.
	|
	*/

	'compiled' => ROOT_DIR . '/runtime/cache',


	/*
	|--------------------------------------------------------------------------
	| View Namespaces
	|--------------------------------------------------------------------------
	|
	| Blade has an underutilized feature that allows developers to add
	| supplemental view paths that may contain conflictingly named views.
	| These paths are prefixed with a namespace to get around the conflicts.
	| A use case might be including views from within a plugin folder.
	|
	*/

	'namespaces' => [
		/* Given the below example, in your views use something like: @include('WC::some.view.or.partial.here') */
		// 'WC' => WP_PLUGIN_DIR.'/woocommerce/legacy/templates/',
	],
];
