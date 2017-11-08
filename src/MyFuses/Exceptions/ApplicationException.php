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

namespace Candango\MyFuses\Exceptions;

/**
 * MyFuses Application Exception - MyFusesApplicationException.php
 *
 * Exception thrown on errors while handling applications.
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      077e9521c58e1649e586615446be221f10934b95
 */
class ApplicationException extends Exception
{
    /**
     * Non-existent application contant <br>
     * value 1
     * 
     * @var integer
     */
    const NON_EXISTENT_APPLICATION = 1;

    /**
     * Exception constructor
     *
     * @param array $params
     * @param integer $operation
     */
    public function __construct($params, $operation)
    {
        list($msg, $detail) = $this->getNonExistentApplicationMessage($params);

        parent::__construct($msg, $detail,
            self::NON_EXISTENT_CIRCUIT);
    }

    /**
     * Return an array with message and datails of a non-existent 
     * circuit exception
     *
     * @param array $params
     * @return array
     */
    private function getNonExistentApplicationMessage($params)
    {
        return array(
	        0 => "Could not find the application \"" . 
	           $params['applicationName'] . "\".",
	        1 => "The application  \"" . $params['applicationName'] .
	            "\" wasn't found in MyFuses context." );
    }
}
