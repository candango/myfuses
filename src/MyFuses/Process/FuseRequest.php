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

namespace Candango\MyFuses\Process;

use Candango\MyFuses\Controller;
use Candango\MyFuses\Core\Application;
use Candango\MyFuses\Core\FuseAction;
use Candango\MyFuses\Exceptions\ActionException;
use Candango\MyFuses\Exceptions\ApplicationException;
use Candango\MyFuses\Exceptions\CircuitException;

/**
 * FuseRequest - FuseRequest.php
 *
 * Current MyFuses request based on the application.circuit.action routed by
 * the Framework.
 *
 * @category   controller
 * @package    myfuses.process
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      bd76f1de003a2310a34a51d59726d23d99c31a08
 */
class FuseRequest
{
    /**
     * Application handled by request
     *
     * @var Application
     */
    private $application;

    /**
     * Circuit name informed
     *
     * @var String
     */
    private $circuitName;

    /**
     * Action name informed
     *
     * @var String
     */
    private $actionName;

    private $validFuseactionName;

    /**
     * Queue the process must resolve alter request resolution
     *
     * @var FuseQueue
     */
    private $fuseQueue;

    /**
     * Extra parameters returned by the the rest of the path that wasn't
     * converted to an application and/or circuit and/or action
     *
     * @var array
     */
    private $extraParams = array();

    public function __construct($applicationName = null)
    {
        Controller::getInstance()->setCurrentPhase(Lifecycle::BUILD_PHASE);

        if (is_null($applicationName)) {
            $this->application = Controller::getInstance()->getApplication();
        }
        else {
            $this->application = Controller::getInstance()->getApplication(
                $applicationName);
        }

        $defaultFuseaction = $this->application->getDefaultFuseAction();

        $fuseactionVariable = $this->application->getFuseactionVariable();

        if (Controller::isRewriting()) {
            $root = dirname($_SERVER[ 'SCRIPT_NAME']);
            $pathX = explode("?", $_SERVER['REQUEST_URI']);
            $path = $pathX[0];

            if ($root != "/") {
                $path = str_replace($root, "", $path);
            }

            // FIXME Very very strange. Must research more about this.
            $path = str_replace("myfuses.xml", "myfuses", $path);

            if ($this->hasPathFuseactionVariable($path)) {
                if (Controller::getApplication()->ignoreFuseactionVariable()) {
                    $urlRedirect = Controller::getMySelf();
                    if (substr($urlRedirect, -1) != "/"){
                        $urlRedirect .= "/";
                    }
                    $urlRedirect .= str_replace(
                        "/" . $fuseactionVariable . "/", "", $path);
                    if (!empty($_GET)) {
                        $urlRedirect .= "?" . http_build_query($_GET);
                    }
                    Controller::sendToUrl($urlRedirect);
                }
            }

            if (substr($path, -1) == "/") {
                $path = substr($path, 0, strlen($path) - 1);
            }

            $path = substr($path, 1, strlen($path));

            $pathX = explode("/", $path);

            $this->validFuseactionName = $this->resolvePath($pathX);
        } else {
            if (isset($_GET[$fuseactionVariable])
                && $_GET[$fuseactionVariable] != '') {
                $this->validFuseactionName = $_GET[$fuseactionVariable];
            }

            if (isset($_POST[$fuseactionVariable])
               && $_POST[$fuseactionVariable] != '') {
                $this->validFuseactionName = $_POST[$fuseactionVariable];
            }
        }

        if (count(explode(".", $this->validFuseactionName)) > 2) {
            list($appName, $circuitName, $actionName) =
                explode('.', $this->validFuseactionName);

            $this->application = Controller::getInstance()->getApplication(
                $appName);

            if (is_null($this->application)) {
                $params = array("applicationName" => $appName);
                throw new ApplicationException($params,
                    ApplicationException::NON_EXISTENT_APPLICATION);
            }

            $this->validFuseactionName = $circuitName . "." . $actionName;
        }

        if (is_null($this->validFuseactionName)) {
            $this->validFuseactionName = $defaultFuseaction;
        }

        list($this->circuitName, $this->actionName) =
            explode('.', $this->validFuseactionName);
    }

    /**
     * Return application handled by request
     *
     * @return Application
     */
    function getApplication()
    {
        return $this->application;
    }

    /**
     * Return fuseaction handle by request
     *
     * @return FuseAction
     */
    public function getAction()
    {
        $action = null;

        $circuit = $this->application->getCircuit($this->circuitName);

        $action = $circuit->getAction($this->actionName);
        return $action;
    }

    function getCircuitName()
    {
        return $this->circuitName;
    }

    function getActionName()
    {
        return $this->actionName;
    }

    function getFuseActionName()
    {
        return $this->getCircuitName() . "." . $this->getActionName();
    }

    function getValidFuseactionName()
    {
        return $this->validFuseactionName;
    }

    public function getXFAs()
    {
        return $this->application->getCircuit($this->circuitName)->getAction(
            $this->actionName)->getXFAs();
    }

    public function &retrieveGetVars()
    {
        return $_GET;
    }

    public function &retrievePostVars()
    {
        return $_POST;
    }

    public function &retrieveRequestVars()
    {
        return $_REQUEST;
    }

    public function &retrieveSessionVars()
    {
        return $_SESSION;
    }

    /**
     * Return the Request FuseQueue
     *
     * TODO: Isn't that supposed to be static?
     * 
     * @return FuseQueue
     */
    public function &getFuseQueue()
    {
        if(is_null($this->fuseQueue)) {
            $this->fuseQueue = new FuseQueue($this);
        }
        return $this->fuseQueue;
    }

    public function __toString() {
        return get_class($this) . "('" . $this->getFuseActionName() . "')";
    }

    /**
     * Resolve an array of paths returning the valid fuseaction and storing
     * extra parameters.
     *
     * @param string $path
     * @return string
     * @throws ActionException
     */
    public function resolvePath($path)
    {
        $resolvedPath = "";

        $fuseactionVariable =
            $this->getApplication()->getFuseactionVariable();

        if ($path[0] == $fuseactionVariable || $path[0] == "") {
            $path = array_slice($path, 1, count($path));
        }

        if (count($path) == 0) {
            return $this->getApplication()->getName() . "." . 
                $this->getApplication()->getDefaultFuseaction();
        }

        if (count($path) == 1) {
            try {
                $circuit = $this->getApplication()->getCircuit($path[0]);

                $resolvedPath = $circuit->getName();

                foreach ($circuit->getActions() as $action) {
                    if ($action->isDefault()) {
                        return $circuit->getApplication()->getName() . "." . 
                            $resolvedPath . "." . $action->getName();
                    }
                    $params = array(
                        'actionName' => "default",
                        'circuit' => $circuit ,
                        'application' => $this->getApplication()
                    );
                    throw new ActionException($params,
                        ActionException::NON_EXISTENT_FUSEACTION);
                }
            } catch (CircuitException $ce) {
                try {
                    $application = MyFuses::getApplication($path[0]);
                    return $application->getName() . "." .
                        $application->getDefaultFuseaction();    
                } catch (ApplicationException $ae) {
                    $this->setExtraParams($path);
                    return $this->getApplication()->getName() . "." . 
                        $this->getApplication()->getDefaultFuseaction();  
                }
            }
        } elseif (count($path) > 1) {
            try {
                $circuit = $this->getApplication()->getCircuit( $path[ 0 ] );
            
                $resolvedPath = $circuit->getName();
                
                try {
                    $action = $circuit->getAction( $path[ 1 ] );
                    if( count( $path > 2 ) ) {
                        $this->setExtraParams( array_slice( 
                                $path, 2, count( $path ) ) );
                    }
                    return $circuit->getApplication()->getName() . "." . 
                            $resolvedPath . "." . $action->getName();
                }
                catch (ActionException $ae) {
                    foreach( $circuit->getActions() as $action ) {
                        if( $action->isDefault() ) {
                            $this->setExtraParams( array_slice( 
                                $path, 1, count( $path ) ) );
                            return $circuit->getApplication()->getName() . "." . 
                                $resolvedPath . "." . $action->getName();
                        }
                    }
                }
                
            } catch (CircuitException $ce) {
                try {
                    $application = MyFuses::getApplication($path[0]);

                    $resolvedPath = $application->getName();

                    try {
                        $circuit = $application->getCircuit($path[1]);

                        $resolvedPath = $resolvedPath . "." .
                                    $circuit->getName();

                        if (count($path) > 2) {
                            try {
                                $action = $circuit->getAction($path[2]);

                                if( count( $path > 3 ) ) {
                                    $this->setExtraParams(
                                        array_slice($path, 3, count($path))
                                    );
                                }

                                return $resolvedPath . "." .
                                    $action->getName();
                            } catch (ActionException $mffae) {
                                foreach ($circuit->getActions() as $action){
                                    if($action->isDefault()){
                                        $this->setExtraParams(
                                            array_slice($path, 2,
                                                count($path))
                                        );
                                        return $resolvedPath . "." .
                                            $action->getName();
                                    }
                                }
                            }
                        } else {
                            foreach($circuit->getActions() as $action) {
                                if($action->isDefault())
                                {
                                    return $resolvedPath . "." .
                                        $action->getName();
                                }
                            }
                        }
                    } catch(CircuitException $ce) {
                        $this->setExtraParams(
                            array_slice($path, 1, count($path))
                        );
                        return $application->getName() . "." .
                            $application->getDefaultFuseaction();
                    }
                } catch(ApplicationException $ae) {
                    $this->setExtraParams($path);
                    return $this->getApplication()->getName() . "." .
                        $this->getApplication()->getDefaultFuseaction();
                }
            }
        }
    }

    /**
     * Returns true if path has the fuseaction variable
     *
     * @param $path
     * @return boolean
     */
    public function hasPathFuseactionVariable($path)
    {
        $fuseactionVariable = $this->getApplication()->getFuseactionVariable();
        if (strpos($path, $fuseactionVariable) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Return all the extra params from the url path if any
     *
     * @return array
     */
    public function getExtraParams()
    {
        return $this->extraParams;
    }

    /**
     * Set the extra params
     *
     * @param array $extraParams
     */
    private function setExtraParams($extraParams)
    {
        $this->extraParams = $extraParams;
    }
}
