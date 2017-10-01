<?php
namespace App\Tests;

use App\Tests\Helpers\BehatUtility;
use Behat\Gherkin\Cache\FileCache;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Cache;
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;
Use PaulGibbs\WordpressBehatExtension\Util;


/**
 * ThemeContext is for any functions that are directly linked to our theme and cannot be used on any other code base.
 */
class ThemeContext extends RawWordpressContext {


}
