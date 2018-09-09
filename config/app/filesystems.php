<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Filesystem Disks
	|--------------------------------------------------------------------------
	|
	| Here you may configure as many filesystem "disks" as you wish, and you
	| may even configure multiple disks of the same driver. Defaults have
	| been setup for each driver as an example of the required options.
	|
	| Supported Drivers:  "local"
	|
	*/
	'disks' => [

		'uploads' => [
			'driver' => 'local',
			'root' => wp_upload_dir()['basedir'],
		],

        'runtime' => [
            'driver' => 'local',
            'root' => ROOT_DIR . '/runtime'
        ]

	],

];
