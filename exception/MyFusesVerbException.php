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
 * MyFusesVerbException - MyFusesVerbException.class.php
 * 
 * Class that handles all verbs exptions.
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      b6271fd21f8e3597665afcc63e9d3fc91a025bf3
 */
class MyFusesVerbException extends MyFusesException
{
    /**
     * Missing require attribute error constant <br>
     * value 1
     * 
     * @var integer
     */
    const MISSING_REQUIRED_ATTRIBUTE = 1;

    /**
     * Missing namespace error constant <br>
     * value 1
     * 
     * @var integer
     */
    const MISSING_NAMESPACE = 2;

    /**
     * Non-existent verb error constant<br>
     * value 2
     * 
     * @var integer
     */
    const NON_EXISTENT_VERB = 3;

    /**
     * Exception constructor
     *
     * @param array $params
     * @param integer $operation
     */
    public function __construct($params, $operation)
    {
        $operationMessageMap = array(
            self::MISSING_REQUIRED_ATTRIBUTE => 
                "getMissingRequiredAttributeMessage",
            self::MISSING_NAMESPACE => "getMissingNamespaceMessage",
            self::NON_EXISTENT_VERB =>
                "getNonExistentVerbMessage"
        );

        list($msg, $detail) = $this->$operationMessageMap[$operation]($params);

        parent::__construct($msg, $detail, self::MISSING_REQUIRED_ATTRIBUTE);
    }

    /**
     * Return an array with message and datails of a non-existent 
     * circuit exception
     *
     * @param array $params
     * @return array
     */
    private function getMissingRequiredAttributeMessage($params)
    {
        return @array(
	        0 => "You have one \"" . $params['verbName'] .
	            "\" verb with a missing \"" . $params['attrName'] .
	            "\" attribute in fuseaction \"" . $params['actionName'] .
	            "\" in circuit \"" . $params['circuitName'] .
	            "\" in application \"" . $params['appName'] .
	            "\".",
	        1 => "Check the  \"" . $params['circuitFile'] .
	            "\" file in fuseaction \"" . $params['actionName'] .
	            "\" and inform the missing \"" . $params['attrName'] .
	            "\" attribute.");
    }

    private function getMissingNamespaceMessage($params)
    {
        return array(
	       0 => "You have one \"" . $params['verbName'] .
	            "\" verb with undefined namespace " .
	            "in fuseaction \"" . $params['actionName'] .
	            "\" in circuit \"" . $params['circuitName'] .
	            "\" in application \"" . $params['appName'] .
	            "\".",
	       1 => "Check your Custom Verb and verify the reason why the " .
	            "namespace wasn't informed.");
    }

    private function getNonExistentVerbMessage($params)
    {
        return array(
	        0 => "You have a non existent \"" . $params['verbName'] .
	            "\" verb with in fuseaction \"" . $params['actionName'] .
	            "\" in circuit \"" . $params['circuitName'] .
	            "\" in application \"" . $params['appName'] .
	            "\".",
	        1 => "Check the  \"" . $params['circuitFile'] .
	            "\" file in fuseaction \"" . $params['actionName'] .
	            "\" and fix this error.");
    }
}
