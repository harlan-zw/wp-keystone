<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Log Channel
	|--------------------------------------------------------------------------
	|
	| This option defines the default log channel that gets used when writing
	| messages to the logs. The name specified in this option should match
	| one of the channels defined in the "channels" configuration array.
	|
	*/
	'default' => 'wp',


	/*
	|--------------------------------------------------------------------------
	| Log Channels
	|--------------------------------------------------------------------------
	|
	| Here you may configure the log channels for your application. Out of
	| the box, Laravel uses the Monolog PHP logging library. This gives
	| you a variety of powerful log handlers / formatters to utilize.
	|
	| Available Drivers: "single", "daily", "slack", "syslog",
	|                    "errorlog", "custom", "stack"
	|
	*/
	'channels' => [


		'wp' => [
			'driver' => 'daily',
			'level' => 'debug',
			'path' => 's3://' . AS3CF_BUCKET . '/logs/wp.log',
			'days' => 3,
		],

		'salesforce' => [
			'driver' => 'daily',
			'level' => 'debug',
			'path' => 's3://' . AS3CF_BUCKET . '/logs/salesforce.log',
			'days' => 14,
		],

	],

];
