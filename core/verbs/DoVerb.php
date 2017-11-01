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

require_once MYFUSES_ROOT_PATH . "core/verbs/ParameterizedVerb.php";
require_once MYFUSES_ROOT_PATH . "core/verbs/InvokeVerb.php";

/**
 * DoVerb  - DoVerb.php
 *
 * This verb delegates execution to another verb. In fact DoVerb will resolve
 * the verb called until find some "real" verb. 
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      6cea291d61da569150c494630909371bd6ff6e3c
 */
class DoVerb extends ParameterizedVerb
{
    /**
     * Circuit name to be executed
     *
     * @var string
     */
    private $circuitToBeExecutedName;

    private $appName;

    /**
     * Action name to be executed
     *
     * @var string
     */
    private $actionToBeExecutedName;

    /**
     * The include content variable
     * 
     * @var string
     */
    private $contentVariable;

    public function setActionToBeExecuted($actionName)
    {
        $actionNameX = explode('.', $actionName);
        
        $app = "";

        if (count($actionNameX) > 2) {
            list($app, $circuit, $action) = $actionNameX;
            $actionNameX = array($circuit, $action);
        }

        $this->appName = $app;

        if (count($actionNameX) < 2) {
            try {
                $this->circuitToBeExecutedName = 
                    $this->getAction()->getCircuit()->getName();    
            } catch (MyFusesCircuitException $mfe) {
	            $mfe->breakProcess();
	        }
            $this->actionToBeExecutedName = $actionName;
        } else {
			$this->circuitToBeExecutedName = $actionNameX[0];
			$this->actionToBeExecutedName = $actionNameX[1];
        }
    }

    /**
     * Return the content variable
     *
     * @return string
     */
    public function getContentVariable()
    {
        return $this->contentVariable;
    }

    /**
     * Set the content variable
     *
     * @param string $contentVariable
     */
    public function setContentVariable($contentVariable)
    {
        $this->contentVariable = $contentVariable;
    }

    public function getData()
    {
        $data = parent::getData();
        $app = $this->getAction()->getCircuit()->getApplication()->getName();

        if (!is_null($this->getContentVariable())) {
            $data['attributes']['contentvariable'] =
                $this->getContentVariable();
        }
        
        $data['attributes']['action'] = ($this->appName != "" ?
            $this->appName . "." : "") .  $this->circuitToBeExecutedName .
            "." . $this->actionToBeExecutedName;
        return $data;
    }

    public function setData($data)
    {
        parent::setData($data);

        foreach ($data[ 'attributes' ] as $attributeName => $attribute) {
            switch (strtolower($attributeName)) {
                case "action":
                    $this->setActionToBeExecuted($attribute);
                    break;
                case "contentvariable":
                case "variable":
                    $this->setContentVariable($attribute);
                    break;
            }
        }
    }

    public static function doAction(CircuitAction $action)
    {
        $parsedPath = $action->getCircuit()->getApplication()->getParsedPath();

        $actionFile = $parsedPath . $action->getCircuit()->getName() . 
            DIRECTORY_SEPARATOR . $action->getName() . ".action.do.php";    

        if (!is_file($actionFile) || $action->getCircuit()->isModified()) {
            $strOut = $action->getParsedCode($action->getCircuit()->
                getApplication()->isParsedWithComments(), 0);

            $path = $action->getCircuit()->getApplication()->getParsedPath() .
                $action->getCircuit()->getName() . DIRECTORY_SEPARATOR;

            if (!file_exists($path)) {
                MyFusesFileHandler::createPath($path);
                chmod($path, 0755);
            }   

            MyFusesFileHandler::writeFile($actionFile, "<?php\n" .
                MyFusesContext::sanitizeHashedString($strOut));

            MyFuses::getInstance()->getDebugger()->registerEvent(
                new MyFusesDebugEvent(MyFusesDebugger::MYFUSES_CATEGORY,
                    "Fuseaction " . $action->getCircuit()->
                    getApplication()->getName() . "." . 
                    $action->getCircuit()->getName() . "." .
                    $action->getName() . " Compiled"));
        }

        MyFusesContext::includeFile($actionFile);
    }

	/**
     * Return the parsed code
     *
     * @return string
     */
    public function getRealParsedCode($commented, $identLevel)
    {
        InvokeVerb::clearClassCall();
        $appName = $this->appName == "" ? 
            $this->getAction()->getCircuit()->getApplication()->getName() : 
            $this->appName;
        $completeActionName = $appName . "." .
            $this->circuitToBeExecutedName . "." .
            $this->actionToBeExecutedName; 

        try {
            $action = MyFuses::getInstance()->getApplication(
                $appName)->getCircuit($this->circuitToBeExecutedName)->
	            getAction($this->actionToBeExecutedName);
        } catch (MyFusesCircuitException $mfce) {
            $mfce->breakProcess();
        } catch (MyFusesActionException $mffae) {
            $mffae->breakProcess();
        }

        //$this->generateActionFile($action, $commented);
        $strOut = "";

        if (!is_null($this->getContentVariable())) {
            $strOut .= str_repeat("\t", $identLevel);
            $strOut .=  "ob_start();\n";
        }

        $strOut .= str_repeat("\t", $identLevel);

        $action->setCalledByDo(true);

        $strOut .=  $this->getAction()->getCircuit()->getApplication()->
            getControllerClass() . "::doAction( \"" . 
            $completeActionName . "\" );\n";

        $strOut .= self::getContextRestoreString($identLevel);

        if (!is_null($this->getContentVariable())) {
            $strOut .= str_repeat("\t", $identLevel);
            $strOut .= "\$" . $this->getContentVariable() .
                "= ob_get_contents();\n";
            $strOut .= str_repeat("\t", $identLevel);
            $strOut .= "ob_end_clean();\n";
            $strOut .= str_repeat("\t", $identLevel);
        	$strOut .= self::getVariableSetString($this->getContentVariable(),
                "#$" . $this->getContentVariable() . "#");
        }

        $action->setCalledByDo(false);

        return $strOut;
    }

}
