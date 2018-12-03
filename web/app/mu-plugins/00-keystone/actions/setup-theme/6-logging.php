<?php
namespace App;

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
 * Logger instance for writing logs
 * @return Writer
 */
function log() {
	return app('logger');
}
