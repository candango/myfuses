<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Security;

use Candango\MyFuses\Core\Application;

/**
 * MyFusesSecurityContext - MyFusesSecurityContext.php
 *
 * MyFuses Security Context
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      2e0c26a744b984d6463db487a51387bb4005488e
 */
class SecurityContext
{
	public static function registerApplication(Application $application)
    {
		if( !session_start() ) {
			var_dump( "" );
		}
	}
}
