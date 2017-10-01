<?php
namespace App\Tests\Helpers;

class BehatUtility {

	public static function spins(callable $closure, $wait = 60, $step = 250000)
	{
		$error     = null;
		$stop_time = time() + $wait;

		while (time() < $stop_time) {
			try {
				$response = call_user_func($closure);
				if ($response) {
					return;
				}
			} catch (\Exception $e) {
				$error = $e;
			}

			usleep($step);
		}

		throw $error;
	}

}