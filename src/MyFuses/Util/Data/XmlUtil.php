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
 * MyFusesXmlUtil  - MyFusesXmlUtil.php
 * 
 * This is utility class has some methdos that handles xml to php transforming
 * and encoding.
 *
 * @category   util
 * @package    util.data
 * @author     Flavio Garcia <piraz at candango.org>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @since      c27bd97ef31dce175ab66eed0fc0a7d86f95bb8e
 */
class XmlUtil
{
    /**
     * Transforms one php structure to xml.
     * Encloses the data xml representation by a given root tag.
     *
     * @param mixed $data
     * @param string $root
     * @return string
     */
    public static function toXml($data, $root = "myfuses_xml")
    {
        $strXml = "<" . $root . ">\n";
        $strXml .= self::doXmlTransformation($data);
        $strXml .= "</" . $root . ">";
        return $strXml;
    }

    /**
     * Transforms any php structure to xml string
     *
     * @param mixed $data
     * @param integer $level
     * @param string $tagName
     * @return string
     */
    private static function doXmlTransformation($data, $level=1, $tagName = "")
    {
        $strXml = "";

        if (is_object($data)) {
            $strXml .= self::getObjectXml($data, $level);
        } elseif (is_array($data)) {
            if ($tagName === "") {
                $tagName = "array";
            }
            $strXml .= str_repeat( "\t", $level ) . "<" . $tagName . ">\n";
            foreach ($data as $items) {
                $strXml .= self::doXmlTransformation($items, $level+1);
            }
            $strXml .= str_repeat( "\t", $level ) . "</" . $tagName . ">\n";
        } else {
            if (is_bool($data)) {
                $strXml .= str_repeat( "\t", $level ) . "<" . $tagName . ">";
                $strXml .= $data ? "true" : "false";
                $strXml .= "</" . $tagName . ">\n";
            } else {
                if (is_null($data)) {
                    $strXml .= str_repeat( "\t", $level ) . "<" . 
                        $tagName . "/>\n";    
                } else {
                    $strXml .= str_repeat( "\t", $level ) . "<" .
                        $tagName . ">";
                    $strXml .= $data;
                    $strXml .= "</" . $tagName . ">\n";
                }
            }
        }
        return $strXml;
    }

    /**
     * Return xml representation form
     *
     * @param Object $object
     * @param integer $level
     * @return string
     */
    private static function getObjectXml($object, $level)
    {
        $tagName = get_class($object);

        $refClass = new ReflectionClass($object);

        $strXml = str_repeat("\t", $level) . "<" . $tagName . ">\n";

        foreach ($refClass->getMethods() as $method) {
            if ($method->isPublic()) {
                if (substr($method->getName(), 0, 3) == "get" ||
                    substr($method->getName(), 0, 2) == "is" ) {
                    $methodName = $method->getName();
                    $subInit = substr($methodName, 0 , 4);
                    $subFinal = substr($methodName, 4);
                    $subInit = str_replace(array("get", "is"), "", $subInit);

                    $property = strtolower($subInit) . $subFinal;

                    $value = $object->$methodName();
                    
                    $strXml .= self::doXmlTransformation(
                        $value, $level + 1, $property
                    );
                }
            }
        }

        $strXml .= str_repeat("\t", $level) . "</" . $tagName . ">\n";

        return $strXml;
    }

    /**
     * Tra
     *
     * @param mixed $data
     * @return array
     */
    private static function xmlPrepare($data)
    {
        // TODO: monster!? Was that my idea or Daniel's? Seems mine... :)
        $monster = $data;

        if (is_object($data)) {
            $monster = array(
                get_class($data) => DataUtil::objectToArray($data, true)
            );
        }

        if (is_array($data)) { // objects will also fall here
            foreach ($data as $key => $item) {
                if (is_object($item)) {
                    $monster[get_class($item) . "_<s>" ][] =
                        self::xmlPrepare($item);
                    unset($monster[$key]);
                } else {
                    $monster[$key] = self::xmlPrepare($item);
                }
            }

            return $monster;
        }

        if (is_string($data)) {
            return $data;
        }

        return $monster;
    }

    /**
     * Return php structures from xml string
     *
     * @param string $xml
     * @return mixed
     */
    public static function fromXml($xml)
    {
        $document = new SimpleXMLElement($xml);

        return self::fromXmlElement($document->children());
    }

    public static function fromXmlUrl($url)
    {
        return self::fromXml(file_get_contents($url), true);
    }

    private static function fromXmlElement(SimpleXMLElement $element)
    {
        $struct = null;

        if (count($element->children())) {
            $structName = $element->getName();

            if (class_exists($structName, true)) {
                $struct = new $structName();

                $refClass = new ReflectionClass($struct);

                foreach ($element->children() as $key => $item) {
                    $phpValue = self::fromXmlElement($item);

                    try {
                        if ($property = $refClass->getProperty($key)) {
                            if ($property->isPublic()) {
                                $struct->$key = $phpValue;    
                            }
                        }

                        $methodName = "set" . strtoupper(substr($key, 0, 1)) .
                            substr($key, 1, strlen($key));

                        if ($method = $refClass->getMethod($methodName)) {
                            if ($method->isPublic()) {
                                $struct->$methodName($phpValue);
                            }
                        }
                    } catch (ReflectionException $re) {
                        switch ($re->getCode()) {
                            // ignoring non existent properties and methods
                            case 0;
                            case 1;
                                break;
                            default:
                                throw $re;
                        }
                    }
                }
            } else {
                $struct = array();
                foreach ($element as $item) {
                    $struct[] = self::fromXmlElement($item);
                }
            }
        } else {
            $struct = "" . $element;    
        }
        return $struct;
    }
}
