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

namespace Candango\MyFuses\Core;

use Candango\MyFuses\Exceptions\VerbException;
use Candango\MyFuses\Util\FileHandler;

/**
 * AbstractVerb - AbstractVerb.php
 *
 * AbstractVerb implements various methods defined in Verb interface.
 * All Custom verbs must extend AbstractVerb to be in compliance with the 
 * framework. 
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f58e20e297c17545ad8f76fed4a1f23c35f2e445
 */
abstract class AbstractVerb implements Verb
{
    private static $verbTypes = array(
            "myfuses:do" => "Candango\\MyFuses\\Core\\Verbs\\DoVerb",
            "myfuses:if" => "Candango\\MyFuses\\Core\\Verbs\\IfVerb",
            "myfuses:include" => "Candango\\MyFuses\\Core\\Verbs\\IncludeVerb",
            "myfuses:instantiate" => "Candango\\MyFuses\\Core\\Verbs\\" .
                "InstantiateVerb",
            "myfuses:invoke" => "Candango\\MyFuses\\Core\\Verbs\\InvokeVerb",
            "myfuses:loop" => "Candango\\MyFuses\\Core\\Verbs\\LoopVerb",
            "myfuses:relocate" => "Candango\\MyFuses\\Core\\Verbs\\" .
                "RelocateVerb",
            "myfuses:response" => "Candango\\MyFuses\\Core\\Verbs\\" .
                "ResponseVerb",
            "myfuses:set" => "Candango\\MyFuses\\Core\\Verbs\\SetVerb",
            "myfuses:switch" => "Candango\\MyFuses\\Core\\Verbs\\SwitchVerb",
            "myfuses:xfa" => "Candango\\MyFuses\\Core\\Verbs\\XfaVerb",
            "myfuses:var_dump" => "Candango\\MyFuses\\Core\\Verbs\\VarDumpVerb"
    );

    /**
     * Verb action
     *
     * @var CircuitAction
     */
    private $action;

    /**
     * Verb name
     *
     * @var string
     */
    private $name;

    /**
     * Verb namespace
     *
     * @var string
     */
    private $namespace;

    /**
     * Verb parent
     * 
     * @var Verb
     */
    private $parent;

    /**
     * Return the verb Action
     *
     * @return CircuitAction
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the verb Action
     *
     * @param CircuitAction $action
     */
    public function setAction(CircuitAction $action)
    {
        $this->action = $action;
    }

    /**
     * Return the veb name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the verb name
     *
     * @param string $name
     */
    public function setName( $name)
    {
        $this->name = $name;
    }

    /**
     * Return the veb namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set the verb namespace
     *
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Return the verb parent
     *	
     * @return Verb
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the verb parent
     *
     * @param Verb $parent
     */
    public function setParent(Verb $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Returns a new or existent verb instance
     *
     * @param array $data
     * @param CircuitAction $action
     * @return Verb
     * @throws VerbException
     */
    public static function getInstance(&$data, CircuitAction $action = null)
    {
        //$data = stripslashes( $data );
        //$data = unserialize( $data );
        if (isset(self::$verbTypes[@$data['namespace'] . ":" .
            $data['name']])) {
            $verb = new self::$verbTypes[$data['namespace'] . ":" .
                    $data['name']]();

	        if(!is_null($action)) {
	            $verb->setAction($action);
	        }
	        $verb->setData($data);
	        return $verb;
        } else {
            if($action->getCircuit()->verbPathExists(@$data['namespace'])) {
                $path = $action->getCircuit()->getVerbPath($data['namespace']);
                if(!FileHandler::isAbsolutePath($path)) {
                    if(file_exists($action->getCircuit()->getApplication()
                            ->getPath() . $path)) {
                        $path = $action->getCircuit()->getApplication()
                            ->getPath() . $path;
                    } else {
                        foreach($action->getCircuit()->getApplication()->
                            getController()->getVerbPaths() as $vPath) {
                            $vPath = FileHandler::sanitizePath($vPath);
                            if(file_exists($vPath . $path)) {
                                $path = $vPath . $path;
                            } 
                        }
                    }   
                }

                $className = strtoupper(substr($data['namespace'], 0, 1)) .
                    substr($data['namespace'], 1,
                        strlen($data['namespace']) - 1) .
                    strtoupper(substr($data['name'], 0, 1)) .
                    substr($data['name'], 1, strlen($data['name']) - 1) .
                    "Verb";

                if(!is_file($path. $className . ".php")) {
                    $params = $action->getErrorParams();
	                $params['verbName'] = $data['name'];
	                   throw new VerbException($params,
	                        VerbException::NON_EXISTENT_VERB);
                }

                require_once($path. $className . ".php");

                $verb = new $className();
		        if( !is_null($action)) {
		            $verb->setAction($action);
		        }
		        $verb->setData($data);
		        return $verb;
            } else {
                    $params = $action->getErrorParams();
                    $params['verbName'] = $data['name'];
                    throw new VerbException($params,
                        VerbException::MISSING_NAMESPACE);
            }
        }
        return null;
    }

    private function dataToString($data)
    {
        $strOut = "array(";
        $comma = false;
        foreach($data as $key => $value) {
            $strOut .= $comma ? ", " : "";    
            if(is_array($value)) {
                $strOut .= "'" . $key . "' => " . $this->dataToString($value);
            }
            else {
                $strOut .= "'" . $key . "' => '" . addslashes($value) . "'";
            }
            $comma = true;
        }
        $strOut .= ")";
        return $strOut;
    }

    public function getCachedCode()
    {
        $data = $this->getData();
        $strOut = "\$data = " . $this->dataToString($data) . ";\n";
        $strOut .= "\$verb = AbstractVerb::getInstance(\$data, \$action);\n";
        return $strOut;
    }

	public function getData()
    {
	    if($this->getNamespace() != "myfuses") {
	        $data['name'] = $this->getName();
	    } else {
	        $data['name'] = $this->getName();
	    }
	    $data['namespace'] = $this->getNamespace();
	    return $data;
	}

	public function setData($data)
    {
	    $this->setName($data['name']);
	    $this->setNamespace($data['namespace']);
	}

	/**
	 * Return the parsed code
	 *
	 * @return string
	 */
	public function getParsedCode($commented, $identLevel)
    {
	    $strOut = "";
	    if($commented) {
	        $strOut = $this->getComments($identLevel);
	    }
	    return $strOut;
	}

	public function getTrace($toHtml = false)
    {
	    $data = $this->getData();
	    $strTrace = "<" . $data['namespace'] . ":" . $data['name'];
	    if(isset($data['attributes'])) {
	        foreach($data['attributes'] as $key => $value) {
	            $strTrace .= " " . $key . "=\"" . $value . "\"";
	        }
	    }
	    $strTrace .= ">";
	    if($toHtml) {
	        return htmlentities($strTrace);
	    }
	    return $strTrace;
	}
	
	/**
	 * Return the parsed comments
	 *
	 * @return string
	 */
	public function getComments($identLevel)
    {
	    $fuseactionName = $this->getAction()->getCompleteName();
	    $strOut = str_repeat("\t", $identLevel);
	    $strOut .= "/* " . $fuseactionName . ": " . $this->getTrace() . " */\n";
	    return $strOut;
	}

	public function getErrorParams()
    {
	    $params = $this->getAction()->getErrorParams();
	    $params['verbName'] = $this->getName();
	    return $params;
	}

	protected function getVariableSetString($variable, $value, $identLevel=0)
    {
        // TODO: prepare to concatenation here
        $strOut = str_repeat("\t", $identLevel);
        $contextClass = "Candango\\MyFuses\\Process\\Context";
	    $strOut .= $contextClass . "::setVariable( \"" . $variable . "\", \"" .
            $value . "\" );\n";
        $strOut .= str_repeat("\t", $identLevel);
        $strOut .= "global $" . $variable  . ";\n\n";
        return $strOut;
	}

	protected function getIncludeFileString(
	    $fileName,
        $contentVariable = null,
        $identLevel=0
    ) {
        $contextClass = "Candango\\MyFuses\\Process\\Context";
        $strOut = str_repeat("\t", $identLevel);
	    $strOut .= "if(file_exists(" .
	       $fileName . ")) { \n";
	    if( $contentVariable != null ) {
	        $strOut .= "    ob_start();\n";
	    }
        $strOut .= str_repeat("\t", $identLevel+1);
	    $strOut .= $contextClass . "::includeFile( " . $fileName . ");\n";
        $strOut .= str_repeat("\t", $identLevel+1);
	    $strOut .= self::getContextRestoreString();
	    if( $contentVariable != null ) {
            $strOut .= str_repeat("\t", $identLevel+1);
	        $strOut .= "\$" . $contentVariable . " .= ob_get_contents();" .
                "ob_end_clean();\n";
            $strOut .= str_repeat("\t", $identLevel+1);
            $strOut .= "    " . $contextClass . "::setParameter( \"" .
                $contentVariable . "\", \$" . $contentVariable . " );\n";
        }
        $strOut .= str_repeat("\t", $identLevel);
        $strOut .= "} else {\n";
        $strOut .= str_repeat("\t", $identLevel+1);

        $fileOperationClass = "Candango\\MyFuses\\Exceptions\\" .
            "FileOperationException";
        $strOut .= "throw new " . $fileOperationClass . "(" . $fileName . ", " .
            $fileOperationClass . "::INCLUDE_FILE);\n";
        $strOut .= str_repeat("\t", $identLevel);
        $strOut .= "}\n\n";
        return $strOut;
	}

	protected function getContextRestoreString($identLevel=0)
    {
        $strOut = str_repeat("\t", $identLevel);
        $contextClass = "Candango\\MyFuses\\Process\\Context";
	    $strOut .= "foreach(" . $contextClass .
            "::getContext() as \$value) {";
        $strOut .= "global \$\$value;";
        $strOut .= "}\n";
        return $strOut;
	}
}
