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


	'compiled' => RUNTIME_DIR . '/blade',
];
