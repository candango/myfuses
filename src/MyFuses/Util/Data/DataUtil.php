<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Util\Data;

/**
 * DataUtil  - DataUtil.php
 * 
 * This is utility class has some methdos that handles basic php transforming 
 * and encoding.
 *
 * @category   data
 * @package    Candango.MyFuses.Util.Data
 * @author     Flavio Garcia <piraz at candango.org>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @since      18a7520a16fec0da6c6e570d12aea982299779c0
 */
class DataUtil
{
    /**
     * Transform php objects to array
     *
     * @param object $item
     * @param boolean $assoc
     * @return array
     */
    public static function objectToArray($item, $assoc=false)
    {
        $itemArray = array();

        $refClass = new \ReflectionClass($item);

        foreach ($refClass->getProperties() as $property) {
            if ($property->isPublic()) {
                $itemArray[$property->name] = $item->{$property->name};

                if (is_object($itemArray[$property->name])) {
                    $itemArray[$property->name] = self::objectToArray(
                        $itemArray[$property->name], true);
                }
            }
        }

        foreach ($refClass->getMethods() as $method) {
            if ($method->isPublic()) {
                if (substr( $method->getName(), 0, 3 ) == "get" ||
                    substr( $method->getName(), 0, 2 ) == "is") {

                    $subInit = substr($method->getName(),0,4);
                    $subFinal = substr($method->getName(),4);
                    $subInit = str_replace(array( "get", "is" ), "", $subInit);

                    $property = $subInit.$subFinal;

                    $property = strtolower(substr($property, 0, 1)) .
                        substr($property, 1, strlen($property));

                    $itemArray[$property] = $item->{$method->getName()}();

                    if (is_object($itemArray[$property])) {
                        $itemArray[$property] = self::objectToArray(
                            $itemArray[$property], true);
                    }
                }
            }
        }

        return $itemArray;
    }
}
