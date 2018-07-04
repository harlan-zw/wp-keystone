<?php
namespace App\Commands;

use WP_CLI;
use WP_CLI_Command;

/**
 * Manage the bookings within CanWeBook
 */
class Keystone extends WP_CLI_Command {

	/**
	 * Checks for any bookings that are pending payments and have expired.
	 *
	 * @subcommand version
	 * @when after_wp_load
	 */
	public function version() {
		return WP_CLI::line('Keystone v' . \App\KEYSTONE_VERSION);
	}
}

try {
	WP_CLI::add_command('keystone', Keystone::class);
} catch (\Exception $e ) {
	WP_CLI::error('Failed to initialize keystone commands.');
}
