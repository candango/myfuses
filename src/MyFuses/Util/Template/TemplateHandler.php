<?php
/**
 * TemplateHandler - TemplateHandler.php
 *
 * Handles the Smarty Template Engine
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Util\Template;


abstract class TemplateHandler
{

    const BASIC_TEMPLATE_HANDLER = 0;

    const SMARTY_TEMPLATE_HANDLER = "smarty";

    const XSL_TEMPLATE_HANDLER = 2;

    private $name;

    private $action;

    private static $instances = array();

    private $theme;

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Returns a concrete template handler
     *
     * @access public
     * @param $whichTemplateHandler
     * @param $properties
     * @param string $name
     * @return mixed|null
     */
    public static final function configureHandler(
        $whichTemplateHandler,
        $properties,
        $name = "default"
    ) {

        $whichTemplateHandlerMap = array(
            self::BASIC_TEMPLATE_HANDLER    => "BasicTemplateHandler",
            self::SMARTY_TEMPLATE_HANDLER   => "Candango\\MyFuses\\Util\\" .
                "Template\\Smarty\\SmartyTemplateHandler"
        );

        if(isset($whichTemplateHandlerMap[$whichTemplateHandler]))
        {
            $class = $whichTemplateHandlerMap[$whichTemplateHandler];
            if(!isset(self::$instances[$name]))
            {
                self::$instances[$name] = new $class();
                self::$instances[$name]->setName($name);
                self::$instances[$name]->setProperties($properties);
            }
            return self::$instances[$name];
        } else {
            return null;
        }
    }

    public static final function getInstance($name)
    {
        if(isset(self::$instances[$name]))
        {
            return self::$instances[$name];
        }
        // TODO maybe throw some exception
        return null;
    }

    // it doesn't necessary do setName
    abstract public function setProperties($properties);

    abstract public function addFile($file);

    abstract public function show();

    private function isDeclared($class)
    {
        return class_exists($class);
    }

    public static function toJSON($var)
    {
        return json_encode(self::jsonPrepare($var));
    }

    /**
     * Recursively converts objects into arrays and strings into UTF-8
     * representations, as required by PHP's json_encode
     *
     * @param mixed $var An array, an object, a string, a number, a boolean,
     *                   or null, to be converted
     * @return mixed     A converted value in the same format as the given
     */
    private static function jsonPrepare($var)
    {
        if (is_object($var))
        {
            if (!$var instanceof stdClass)
            {
                $var = self::objectToArray($var);
            }
        }

        if (is_array($var))
        { // objects will also fall here
            foreach ($var as &$item)
            {
                $item = self::jsonPrepare($item);
            }
            return $var;
        }

        if(is_string($var))
        {
            return utf8_encode($var);
        }

        // for all other cases (number, boolean, null), no change

        return $var;
    }

    public static function buildParameter(
        $key, $value)
    {
        $property[ 'name' ] = "property";
        $property[ 'namespace' ] = "smarty";
        $property[ 'attributes' ][ 'name' ] = $key;
        $property[ 'attributes' ][ 'value' ] = $value;
        return $property;
    }

    private static function objectToArray($item, $assoc=false)
    {
        $itemArray = array();

        $refClass = new \ReflectionClass( $item );

        foreach($refClass->getMethods() as $method)
        {
            if($method->isPublic())
            {
                if(substr($method->getName(), 0, 3 ) == "get" ||
                    substr($method->getName(), 0, 2 ) == "is")
                {

                    $subInit = substr($method->getName(),0,4);
                    $subFinal = substr($method->getName(),4);
                    $subInit = str_replace(array( "get", "is" ), "", $subInit );

                    $property = $subInit.$subFinal;

                    $property = strtolower( substr( $property, 0, 1 ) ) .
                        substr( $property, 1, strlen( $property ) );

                    $itemArray[ $property ] = $item->{$method->getName()}();

                    if(is_object($itemArray[$property]))
                    {
                        $itemArray[$property] = self::objectToArray(
                            $itemArray[$property], true);
                    }
                }
            }

        }

        return $itemArray;
    }

}
