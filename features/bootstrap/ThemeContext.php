<?php
namespace App\Tests;

use App\Tests\Helpers\NeedsUtility;
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

/**
 * ThemeContext is for any functions that are directly linked to our theme and cannot be used on any other code base.
 */
class ThemeContext extends RawWordpressContext {

	use NeedsUtility;

}
