<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Exceptions;

/**
 * MyFuses Action Exception - MyFusesActionException.php
 *
 * Exception thrown on errors while handling actions.
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      c5fec49e04ae09fefb514b4e47ed24d324b6231e
 */
class ActionException extends RuntimeException
{
    /**
     * Non-existent circuit contant <br>
     * value 1
     * 
     * @var integer
     */
    const NON_EXISTENT_CIRCUIT = 1;

    /**
     * Exception constructor
     *
     * @param array $params
     * @param integer $operation
     */
    public function __construct($params, $operation)
    {
        $msg = null;
        $detail = null;
        if ($operation == self::NON_EXISTENT_FUSEACTION) {
            list($msg, $detail) = $this->getNonExistentFuseActionMessage(
                $params);
        }
        parent::__construct($msg, $detail,
            MyFusesException::NON_EXISTENT_FUSEACTION);
    }

    /**
     * Return an array with message and datails of a non-existent 
     * circuit exception
     *
     * @param array $params
     * @return array
     */
    private function getNonExistentFuseActionMessage($params)
    {
        return array(
	        0 => "Could not find the FuseAction \"" . $params['actionName'] .
	            "\" in circuit \"" . $params['circuit']->getName() .
	            "\" in application \"" . $params['application']->getName() .
	            "\".",
	        1 => "The FuseAction  \"" . $params['actionName'] .
	            "\" wasn't found in circuit \"" . 
	            $params['circuit']->getName()  . "\" in application \"" .
	            $params['application']->getName()  . "\". " .
	            "You can check if the FuseAction exists in " .
	            "circuit file \"" . 
	            $params['circuit']->getCompleteFile() . "\"." );
    }
}
