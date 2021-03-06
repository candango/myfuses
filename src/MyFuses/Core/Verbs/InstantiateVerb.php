<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core\Verbs;

use Candango\MyFuses\Core\AbstractVerb;

/**
 * InstantiateVerb - InstantiateVerb.php
 *
 * This verb instantiate one object by a given class or webservice.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      195974621ca2e59668492bc79113b161f1910dc1
 */
class InstantiateVerb extends AbstractVerb
{
    /**
     * Name of the class that the verb will instantiate.
     * 
     * @var string
     */
    private $class;

    /**
     * Nome of the instance that the verb will instantiate.
     * 
     * @var string
     */
    private $object;

    /**
     * Wsdl path.<br>
     * When developer inform the wsld, class will be ignored, and the verb will
     * instantiate a new SoapClient.
     * 
     * @var string
     */
    private $webservice;

    /**
     * Arguments used at object construction
     *
     * @var array
     */
    private $arguments;

    /**
     * Child arguments used at object construction
     * 
     * @var array
     */
    private $childArguments;

    /**
     * Returnt the verb class
     * 
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the verb class
     * 
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Return verb object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set verb object
     *
     * @param string $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * Return verb webservice link
     *
     * @return string
     */
    public function getWebservice()
    {
        return $this->webservice;
    }

    /**
     * Set verb webservice link
     *
     * @param string $webservice
     */
    public function setWebservice($webservice)
    {
        $this->webservice = $webservice;
    }

    /**
     * Return the arguments to instantiate te object
     *
     * @return string
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set the arguments to instantiate the object
     *
     * @param string $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Return all verb arguments
     *
     * @return array
     */
    public function getChildArguments()
    {
        return $this->childArguments;
    }

    /**
     * Set an array of arguments in this verb
     *
     * @param array $childArguments
     */
    public function setChildArguments($childArguments)
    {
        $this->childArguments = $childArguments;
    }

    /**
     * Return o new string with all arguments separated by a ',' or the legacy
     * arguments parameters set on the instantiate.
     *
     * The arguments parameter will take priority over the child of arguments.
     *
     * @return string
     */
    private function getArgumentString()
    {
        if (!is_null($this->getArguments()))
        {
            return $this->getArguments();
        }
        $strOut = "";
        if (
            !is_null($this->getChildArguments()) &&
            count($this->getChildArguments())
        ) {
            foreach ($this->getChildArguments() as $key => $argument)
            {
                $strOut .= ($key == 0 ? "": " , ") . "\"" . $argument .  "\"";
            }
        }
        return $strOut;
    }

    public function getData()
    {
        $data = parent::getData();

        if (!is_null($this->getArguments())) {
            $data['attributes']['arguments'] = $this->getArguments();
        }

        if (!is_null($this->getClass())) {
            $data['attributes']['class'] = $this->getClass();
        }

        $data['attributes']['object'] = $this->getObject();

        if (!is_null($this->getWebservice())) {
            $data['attributes']['webservice'] = $this->getWebservice();
        }

        if (!is_null($this->getChildArguments())) {
            foreach ($this->getChildArguments() as $argument) {
                $child = array();
                $child['name'] = 'argument';
                $child['namespace'] = 'myfuses';
                $child['attributes'][ 'value' ] = $argument;
                $data['children'][] = $child;
            }
        }
        return $data;
    }

    public function setData($data)
    {
        parent::setData($data);

        if (isset($data['attributes']['arguments'])) {
            $this->setArguments($data['attributes']['arguments']);
        }

        if (isset($data['attributes']['webservice'])) {
            $this->setWebservice($data['attributes']['webservice']);
        }

        if (isset($data['attributes']['class'])) {
            $this->setClass($data['attributes']['class']);
        }

        if (isset($data['attributes']['object'])) {
            $this->setObject($data['attributes']['object']);
        }

        if (isset($data['children'])) {
            foreach ($data['children'] as $child) {
                if ($child['name'] == "argument") {
                    if (isset($child['attributes']['value'])) {
                        $this->childArguments[] = $child['attributes']['value'];
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

	    $resolvedClass = $this->getAction()->getCircuit()->getApplication(
            )->getClass($this->getClass());

	    $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();

	    $classCall =  $controllerClass . "::getInstance()->getApplication(\"" .
            $appName . "\")->getClass(\"" . $this->getClass() .
            "\")";

	    $fileCall = $classCall . "->getCompletePath()";
	    $strOut = parent::getParsedCode($commented, $identLevel);
	    $strOut .= str_repeat("\t", $identLevel);
        $contextClass = "Candango\\MyFuses\\Process\\Context";
	    if (is_null($this->getWebservice()))
	    {
	        if($resolvedClass->hasNamespace())
	        {
                $strOut .= $contextClass . "::setVariable( \"" .
                    $this->getObject() . "\", new " .
                    $resolvedClass->getNamespace() . "(" .
                    $this->getArgumentString() . "));\n\n";
            } else {
                $strOut .= "if (file_exists(" . $fileCall . " )) {\n";
                $strOut .= str_repeat("\t", $identLevel + 1);
                $strOut .= "require_once( " . $fileCall . " );\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "}\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= $contextClass . "::setVariable( \"" .
                    $this->getObject() . "\", new " . $this->getClass() .
                    "( " . $this->getArgumentString() . " ) );\n\n";
            }
            $strOut .= self::getContextRestoreString();
	    } else {
	        $strOut .= $contextClass . "::setVariable( \"" .
                $this->getObject() . "\", new SoapClient" .
                "( \"" . $this->getWebservice() . "\" ) );\n\n";
            $strOut .= self::getContextRestoreString();
	        // FIXME use Context::setVariable in here
	    }
	    return $strOut;
	}
}
