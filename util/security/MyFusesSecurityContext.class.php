<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

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
class MyFusesSecurityContext
{
	public static function registerApplication(Application $application)
    {
		if( !session_start() ) {
			var_dump( "" );
		}
	}
}
