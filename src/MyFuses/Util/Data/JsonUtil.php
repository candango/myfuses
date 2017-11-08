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

namespace Candango\MyFuses\Util\Data;

/**
 * MyFusesJsonUtil  - MyFusesJsonUtil.php
 * 
 * This is utility class has some methods that handles json to php transforming
 * and encoding.
 *
 * @category   util
 * @package    Candango.MyFuses.Util.Data
 * @author     Flavio Garcia <piraz at candango.org>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @since      c27bd97ef31dce175ab66eed0fc0a7d86f95bb8e
 */
class JsonUtil
{
    /**
     * This mehtod calls jsonPrepare and encondes the data to json
     *
     * @param mixed $data
     * @return string
     */
    public static function toJson($data)
    {
        return json_encode(self::jsonPrepare($data));
    }

    /**
     * Recursively converts objects into arrays, as required by PHP's
     * json_encode
     * 
     * @param mixed $data An array, an object, a string, a number, a boolean,
     *                    or null, to be converted
     * @return mixed      A converted value in the same format as the given
     */
    private static function jsonPrepare($data)
    {
        if (is_object($data)) {
            if (!$data instanceof stdClass) {
                $className = get_class($data);
                $data = DataUtil::objectToArray($data);

                $data['data_type'] =  "class";

                $data['data_class_name'] =  $className;
            }
        }

        if (is_array($data)) { // objects will also fall here
            foreach ($data as &$item) {
                $item = self::jsonPrepare($item);
            }
            return $data;
        }
        // TODO: we need to test this with different encodes
        // Right now we're working alright for utf-8 back to back
        // for all other cases (number, boolean, null), no change
        return $data;
    }

    public static function fromJson($data)
    {
        return self::toPhp(json_decode($data, true));
    }

    public static function fromJsonUrl($url)
    {
        return self::toPhp(json_decode(file_get_contents($url), true));
    }

    private static function toPhp($data)
    {
        if (isset($data['data_type'])) {
            if ($data['data_type'] == "class") {
                if (class_exists($data['data_class_name'])) {
                    $object = new $data['data_class_name']();

                    $refClass = new ReflectionClass($object);

                    foreach ($data as $key => $value) {
                        $phpValue = null;
                        if (is_scalar($value)) {
                            $phpValue = $value;
                        } else {
                            if (!isset($value['data_type'])) {
                                $phpValue = array();
                                if (!is_null($value)) {
                                    foreach ($value as $v_key => $item) {
                                        $phpValue[$v_key] = self::toPhp($item);
                                    }
                                }
                            } else {
                                $phpValue = self::toPhp($value);
                            }

                        }

                        try {
                            if ($property = $refClass->getProperty($key)) {
                                if ($property->isPublic()) {
                                    $object->$key = $phpValue;
                                }
                            }

                            $methodName = "set" .
                                strtoupper(substr($key,0, 1)) .
                                substr($key, 1, strlen($key));

                            if ($method = $refClass->getMethod($methodName)) {
                                if ($method->isPublic()) {
                                    $object->$methodName($phpValue);
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
                    return $object;
                }
            }
        }

        /*if (is_scalar($data)) {
            var_dump($data);
            die();
        }
        if ($data['data_type'] == "array") {
            foreach ($data as $key => $value) {
                $data[$key] = self::toPhp($value);
            }
            unset($data['data_type']);
            return $data;
        }*/

        return $data;
    }
}
