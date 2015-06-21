<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

error_reporting(E_ALL | E_STRICT);

define('TEST_DIR', __DIR__);
define('TEMP_DIR', TEST_DIR . '/tmp');
define('VENDOR_DIR', dirname(TEST_DIR) . '/vendor');
define('DS', DIRECTORY_SEPARATOR);

if (!file_exists(VENDOR_DIR . '/autoload.php')) {
    exit('Please install Composer in the root folder before running tests!');
}

$loader = require VENDOR_DIR . '/autoload.php';
$loader->add('Titon\\Utility', TEST_DIR);