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

use Candango\MyFuses\Controller;
use Candango\MyFuses\Process\Context;
use Candango\MyFuses\Process\Lifecycle;

/**
 * FuseAction  - FuseAction.php
 *
 * FuseAction is the real action executed by one Circuit. When you acess some
 * Circuit.Action MyFuses will resolve some FuseAction in fact. 
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f58e20e297c17545ad8f76fed4a1f23c35f2e445
 */
class FuseAction extends AbstractAction implements CircuitAction
{
    /**
     * Enter description here...
     *
     * @var Circuit
     */
    private $circuit;

    /**
     * Enter description here...
     *
     * @var array
     */
    private $verbs = array();

    private $xfas = array();

    private $calledByDo = false;

    private $path;

    /**
     * Flag that points if circuit is default in fuseaction
     *
     * @var boolean
     */
    private $default = false;

    /**
     * FuseAction permissions parameter
     * 
     * @var string
     */
    private $permissions = "";

    /**
     * Security mode parameter
     *
     * @var string
     */
    private $security = "optimistic";

    /**
     * Call prefuseaction flag
     *
     * @var boolean
     */
    private $callPreFuseaction = true;

    public function __construct(Circuit $circuit)
    {
        $this->setCircuit($circuit);
    }

    /**
     * Return Circuit Action complete name.<br>
     * Complete name is circuit name plus dot plus action name.
     *
     * return string
     */
    public function getCompleteName()
    {
        return $this->getCircuit()->getName() . "." . $this->getName();
    }

	/**
     * Enter description here...
     *
     * @return Circuit
     */
    public function &getCircuit()
    {
        $circuit = $this->circuit;
        Lifecycle::checkCircuit($circuit);
        return $this->circuit;
    }

    /**
     * Enter description here...
     *
     * @param Circuit $circuit
     */
    public function setCircuit(Circuit &$circuit)
    {
        $this->circuit = &$circuit;
    }

    /**
     * Enter description here...
     *
     * @param Verb $verb
     */
    public function addVerb(Verb $verb)
    {
        $this->verbs[] = $verb;
        $verb->setAction($this);
    }

    /**
     * Enter description here...
     *
     * @param string $name
     * @return Verb
     */
    public function getVerb($name)
    {
        return $this->verbs[$name];
    }

    /**
     * Enter description here...
     *
     * @return array
     */
    public function &getVerbs()
    {
        return $this->verbs;
    }

    public function getXFAs()
    {
        return $this->xfas;
    }

    public function addXFA($name, $value)
    {
        // TODO: this needs to go if enforced strict mode
        $XFA = null;
        if (!$XFA = Context::getVariable("XFA")) {
            $XFA = array();
        }
        $XFA[$name] = $value;
        Context::setVariable("XFA", $XFA);
        global $XFA;
        $this->xfas[$name] = $value;
    }

    public function getXfa($name)
    {
        return $this->xfas[$name];
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Return if the fuseaction must call prefusection
     *
     * @return boolean
     */
    public function mustCallPreFuseaction()
    {
        return $this->callPreFuseaction;
    }

    /**
     * Set if the fuseaction must call prefuseaction
     *
     * @param boolean $callPreFuseaction
     */
    public function setCallPreFuseaction($callPreFuseaction)
    {
        $this->callPreFuseaction = $callPreFuseaction;
    }

    public function getParsedCode($comented, $identLevel)
    {
        $strOut = "";

        $application = $this->getCircuit()->getApplication();

        $controllerClass = $this->getCircuit()->
            getApplication()->getControllerClass();

        $myFusesString = $controllerClass . "::getInstance()";

        $actionString = "\"" . $this->circuit->getApplication()->getName() .
            "." . $this->circuit->getName() .
            "." . $this->getName() . "\"";

        $inTheTry = false;
        if ($this->getCircuit()->getName() != "MYFUSES_GLOBAL_CIRCUIT") {
            if ($this->getName() != "prefuseaction" &&
                $this->getName() != "postfuseaction") {
                $strOut = str_repeat("\t", $identLevel);
                $strOut .= "try {\n\n";

                $inTheTry = true;

                $strOut .= str_repeat("\t", $identLevel+1);
                $strOut .= $myFusesString . "->setCurrentProperties(\"" .
                        Lifecycle::PRE_FUSEACTION_PHASE . "\", "  .
                        $actionString . ");\n\n";

                // parsing pre fuseaction plugins
                if (count($this->getCircuit()->getApplication()->getPlugins(
                    Plugin::PRE_FUSEACTION_PHASE))) {
                    $pluginsStr = $controllerClass . 
                        "::getInstance()->getApplication(\"" .
                        $application->getName() . "\")->getPlugins(" .
                        "\"" . Plugin::PRE_FUSEACTION_PHASE . "\")";
                    $strOut .= str_repeat("\t", $identLevel+1);
                    $strOut .= "foreach (" . $pluginsStr . " as \$plugin) {\n";
                    $strOut .= "\t\$plugin->run();\n}\n\n";
                }
                //end parsing pre fuseaction plugins
            }
        }
        $action = null;
        if (!is_null($action)) {
            $strOut .= $action->getParsedCode($comented,
                $identLevel + ($inTheTry ? 1 : 0));
        }

        $request = Controller::getInstance()->getRequest();

        if (($this->getCircuit()->getName() == $request->getCircuitName()) &&
            ($this->getName() == $request->getActionName())) {
            $strOut .= str_repeat("\t", $identLevel + ($inTheTry ? 1 : 0));
            $strOut .= $myFusesString . "->setCurrentProperties(\"" .
                Lifecycle::PROCESS_PHASE . "\", "  .
                $actionString . ");\n\n";
        }

        if (get_class($this) != "Candango\\MyFuses\\Core\\FuseAction" ) {
            $strOut .= str_repeat("\t", $identLevel + ($inTheTry ? 1 : 0));
            $strOut .= $actionString . "->doAction();\n";    
        }

        foreach ($this->verbs as $verb) {
            $strOut .= $verb->getParsedCode($comented,
                $identLevel + ($inTheTry ? 1 : 0));
        }

        if ($this->getCircuit()->getName() != "MYFUSES_GLOBAL_CIRCUIT") {
            if ($this->getName() != "prefuseaction" &&
                $this->getName() != "postfuseaction") {
                $strOut .= str_repeat("\t", $identLevel+1);
                $strOut .= $myFusesString . "->setCurrentPhase(\"" .
                    Lifecycle::POST_FUSEACTION_PHASE . "\");\n\n";

                if (!is_null($action)) {
                    $strOut .= str_repeat("\t", $identLevel+1);
                    $strOut .= $action->getParsedCode($comented, $identLevel);
                }
                $strOut .= str_repeat("\t", $identLevel+1);
                $strOut .= $myFusesString . "->setCurrentAction("  .
                    $actionString . ");\n\n";

                // parsing post fuseaction plugins
                if (count($this->getCircuit()->getApplication()->getPlugins(
                    Plugin::POST_FUSEACTION_PHASE))) {
                    $pluginsStr = $controllerClass . 
                        "::getInstance->getApplication(\"" .
                        $application->getName() . "\")->getPlugins(" .
                        " \"" . Plugin::POST_FUSEACTION_PHASE . "\" )";
                    $strOut .= "foreach (" . $pluginsStr . " as \$plugin) {\n";
                    $strOut .= "\t\$plugin->run();\n}\n\n";
                }
                $strOut .= str_repeat("\t", $identLevel);
                $faeClass = "Candango\\MyFuses\\Exceptions\\FuseactionException";
                $strOut .= "} catch (" . $faeClass . " \$fae) {\n";

	            if (count($application->getPlugins(
	                Plugin::FUSEACTION_EXCEPTION_PHASE))) {
	                $pluginsStr = $controllerClass . 
	                    "::getInstance()->getApplication( \"" . 
	                    $application->getName() . "\" )->getPlugins(" .
	                    " \"" . Plugin::FUSEACTION_EXCEPTION_PHASE . "\" )";
	                $strOut .= "\tforeach (" . $pluginsStr . " as \$plugin) {\n";
	                $strOut .= "\t\t\$plugin->handle(\$fae);\n\t}\n";
	                $contextClass = "Candango\\MyFuses\\Process\\Context";
	                $strOut .= "\tforeach (" . $contextClass . "::getContext() as " .
	                    " \$key => \$value) {global \$\$value;}\n\n";
	            }
                $strOut .= str_repeat("\t", $identLevel);
	            $strOut .= "}\n\n";
                //end parsing post fuseaction plugins
            }
        }

        return $strOut;
    }

    public function getComments($identLevel)
    {
        return "";
    }

    /**
     * 
     */
    public function wasCalledByDo()
    {
        return $this->calledByDo;
    }

    /**
     * 
     */
    public function setCalledByDo($calledByDo)
    {
        $this->calledByDo = $calledByDo;
    }

    public function getErrorParams()
    {
        $params = $this->getCircuit()->getErrorParams();
        // FIXME CircuitAction must have a name
        $params[ 'actionName' ] = $this->getName();
        return $params;
    }

	public function doAction()
    {
	}

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getCachedCode()
    {
        $strOut = "";
        if (!is_null($this->getPath())) {
            $strOut .= "require_once \"" . $this->getPath() . "\";\n";
        }
        $strOut .= "\$action = new " . get_class($this) . "( \$circuit );\n";
        if (!is_null($this->getPath())) {
            $strOut .= "\$action->setPath( \"" . $this->getPath() . "\" );\n";    
        }
        $strOut .= "\$action->setName(\"" . $this->getName() . "\");\n";
        foreach ($this->customAttributes as $namespace => $attributes) {
            foreach ($attributes as $name => $value) {
                $strOut .= "\$action->setCustomAttribute(\"" . $namespace .
                    "\", \"" . $name . "\", \"" . $value . "\");\n";
            }
        }

        $strOut .= "\$action->setDefault(" . (
            $this->isDefault() ? "true" : "false") . ");\n";
        $strOut .= $this->getVerbsCachedCode();
        return $strOut;
    }

    /**
     * Returns all Action Verbs cache code
     * 
     * @return string
     */
    private function getVerbsCachedCode()
    {
        $strOut = "\n";
        foreach ($this->verbs as $verb) {
            $strOut .= $verb->getCachedCode() . "\n";
            $strOut .= "\$action->addVerb( \$verb );\n\n";
        }
        return $strOut;
    }

    /**
     * Return if the action is default in circuit
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * Set default flag in action. This flag points if the action is default in
     * circuit.
     *
     * @param boolean $default
     */
    public function setDefault($default)
    {
        if (is_null($default)) {
            $this->default = false;
        } else {
            if (is_bool($this->default)) {
                $this->default = $default;
            } else {
                if (strtolower($this->default) == 'true') {
                    $this->default = true;
                } else {
                    $this->default = false;
                }
            }
        }
    }

    /**
     * (non-PHPdoc)
     * @see core/Circuit#getPermissions()
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * (non-PHPdoc)
     * @see core/Circuit#setPermissions()
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * Return security mode
     *
     * @return string
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * Set security mode
     *
     * @param $security
     */
    public function setSecurity($security)
    {
        $allowedModes = array("optimistic", "pessimistic", "disabled");
        if (in_array($security, $allowedModes)) {
            $this->security = $security;
        } else {
            // Get from the from the circuit
            $this->security = $this->getCircuit()->getSecurity();
        }
    }
}
