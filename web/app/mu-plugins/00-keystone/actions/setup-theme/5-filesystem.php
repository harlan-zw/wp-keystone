<?php
namespace App;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;

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
 * Our file system for our runtime directory
 *
 * @return FileSystem
 */
function runtime() {
	/** @var MountManager $fs */
	$fs = app('filesystem');
	return $fs->getFilesystem('runtime');
}


/**
 * Our file system for our uploaded files on our local file system
 *
 * @return Filesystem
 */
function files_uploads() {
	$fs = app('filesystem');
	return $fs->getFilesystem('uploads');
}
