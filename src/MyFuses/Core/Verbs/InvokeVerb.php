<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core\Verbs;

use Candango\MyFuses\Core\AbstractVerb;

/**
 * InstantiateVerb - InstantiateVerb.php
 *
 * This verb instantiate one object by a given class or wsdl.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @author     Michael Lins <malvins at gmail.com>
 * @since      ba49707700d67a68e043d1f1e9a817d32805e753
 */
class InvokeVerb extends AbstractVerb
{
    private $class;

    private static $classCall = array();

    private $object;

    private $method;

    private $methodCall;

    private $arguments;

    private $variable;

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getMethodCall()
    {
        return $this->methodCall;
    }

    public function setMethodCall($methodCall)
    {
        $this->methodCall = $methodCall;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function setArguments($arguments)
    {
        $args = "";

        //Verify arguments - Fusebox 5 (strictMode set to true)
        if (!is_null($arguments)) {
            //Gets the last child postition in arguments array
            $lastChildPos = count($arguments) -1;
            //Set the arguments
            foreach ($arguments as $childPos => $atrr) {
                $args .= $atrr["attributes"]["value"];
                if ($childPos !==  $lastChildPos){
                    $args.= ',';
                }
            }
        }
        $this->arguments = $args;
    }

    public function getVariable()
    {
        return $this->variable;
    }

    public function setVariable($variable)
    {
        $this->variable = $variable;
    }

    /**
     * Return o new strin with all arguments separated by a ','
     *
     * @return string
     */
    private function getArgumentString()
    {
        $strOut = "";
        if (count($this->getArguments())) {
            foreach ($this->getArguments() as $key => $argument) {
                $strOut .= ($key == 0 ? "": " , ") . "\"" . $argument .  "\"";
            }
        }
        return $strOut;
    }

    public function getData()
    {
        $data = parent::getData();

        if (!is_null($this->getClass())) {
            $data['attributes']['class'] = $this->getClass();
        }

        if (!is_null($this->getObject())) {
            $data['attributes']['object'] = $this->getObject();
        }

        if (!is_null($this->getMethod())) {
            $data['attributes']['method'] = $this->getMethod();
        }

        if (!is_null($this->getArguments())) {
            foreach ($this->getArguments() as $argument) {
                $child = array();
                $child['name'] = "argument";
                $child['namespace'] = "myfuses";
                $child['attributes']['value'] = $argument;
                $data['children'][] = $child;
            }
        }

        if (!is_null($this->getMethodCall())) {
            $data['attributes']['methodcall'] = $this->getMethodCall();
        }

        if (!is_null($this->getVariable())) {
            $data['attributes']['returnvariable'] = $this->getVariable();
        }
        return $data;
    }

    public function setData($data)
    {
        parent::setData($data);

        if (isset($data['attributes']['class'])) {
            $this->setClass($data['attributes']['class']);
        } else {
            $this->setObject($data['attributes']['object']);
        }

        if (isset($data['attributes']['method'])) {
            $this->setMethod($data['attributes']['method']);
        }

        if (isset($data['children'])) {
            foreach ($data['children'] as $child) {
                if ($child['name'] == "argument") {
                    if (isset($child['attributes']['value'])) {
                        $this->arguments[] = $child['attributes']['value'];
                    } else {
                        $params = $this->getErrorParams();
                        $params['verbName'] = "argument";
                        $params['attrName'] = "value";
                        throw new VerbException($params,
                            VerbException::MISSING_REQUIRED_ATTRIBUTE);
                    }
                }
            }
        }

        if (isset($data['attributes']['methodcall'])) {
            $this->setMethodCall($data['attributes']['methodcall']);
        }

        if (isset($data['attributes']['returnvariable'])) {
            $this->setVariable($data['attributes']['returnvariable']);
        }
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode($commented, $identLevel)
    {
        $appName = $this->getAction()->getCircuit()->
            getApplication()->getName();
        $contextClass = "Candango\\MyFuses\\Process\\Context";
        $strOut = parent::getParsedCode($commented, $identLevel);
        // Make identation
        $strOut .= str_repeat( "\t", $identLevel );

        if (!is_null($this->getClass())) {
            if (!isset(self::$classCall[$this->getClass()])) {
                $appName = $this->getAction()->getCircuit()->
                    getApplication()->getName();

                $controllerClass = $this->getAction()->getCircuit()->
                    getApplication()->getControllerClass();

                $fileCall = $controllerClass . 
                    "::getInstance()->getApplication( \"" . $appName .
                    "\" )->getClass( \"" . $this->getClass() . 
                    "\" )->getCompletePath()";

                $strOut .= "if ( file_exists( " . $fileCall . " ) ) {\n";
                $strOut .= str_repeat("\t", $identLevel + 1);
                $strOut .= "require_once( " . $fileCall . " );\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "}\n";
                $strOut .= str_repeat("\t", $identLevel);

                self::$classCall[$this->getClass()] = "called";
            }
        }

        if (!is_null($this->getVariable())) {
            $strOut .= $contextClass . "::setVariable( \"" .
                $this->getVariable() . "\", ";
        }

        // Begin method call
        if (!is_null($this->getMethod())) {
            if (is_null($this->getClass())) {
                $strOut .= $contextClass . "::getVariable( \"" .
                    $this->getObject() . "\" )->" .
                    $this->getMethod();
            } else {
                $strOut .= $this->getClass() . "::" . 
                    $this->getMethod();
            }
            $strOut .= "( ";

            //TODO: Verify arguments - Fusebox 5 (strictMode set to true)
            if (!is_null($this->getArguments())) {
                $strOut .= $this->getArgumentString();
            }
            // Close method
            $strOut .= " )";
        } else {
            $strOut .= "\$" . $this->getObject() . "->" .
                $this->getMethodCall();
        }

        if (is_null($this->getVariable())) {
            $strOut .= ";\n\n";
        } else {
            $strOut .= " );\n";
            $strOut .= $this->getContextRestoreString();
        }

        return $strOut;
    }
    
    public static function clearClassCall()
    {
        self::$classCall = array();
    }
}
