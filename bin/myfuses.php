<?php
/**
 * Carcara (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

if (PHP_SAPI == "cli") {
    echo "Warning: Myfuses should be invoked via the CLI version of PHP, not" .
	    " the " . PHP_SAPI . " SAPI" . PHP_EOL;
    exit(1);
}

echo "The Myfuses application is under construction." . PHP_SAPI . " SAPI" . 
    PHP_EOL;
exit(0);
