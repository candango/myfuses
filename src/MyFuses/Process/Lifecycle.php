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

use Candango\MyFuses\Core\Application;
use Candango\MyFuses\Core\Circuit;
use Candango\MyFuses\Core\CircuitAction;
use Candango\MyFuses\Engine\BasicBuilder;
use Candango\MyFuses\I18n\I18NHandler;
use Candango\MyFuses\Controller;

use const Candango\MyFuses\ROOT_PATH;

/**
 * Lifecycle - Lifecycle.php
 * 
 * The MyFuses Lifecycle controls all phases of application and request process.
 *
 * @category   controller
 * @package    myfuses.process
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f5a327301f2e1df449f25f07953fcac1689411ab
 */
abstract class Lifecycle
{
    const LOAD_PHASE = "load";

    const BUILD_PHASE = "build";

    const STORE_PHASE = "store";

    /**
     * Process phase constant<br>
     * value "process"
     *
     * @var string
     */
    const PROCESS_PHASE = "process";

    /**
     * Pre process fase constant<br>
     * Value "preProcess"
     * 
     * @var string
     */
    const PRE_PROCESS_PHASE = "preProcess";

    /**
     * Pre fuseaction fase constant<br>
     * Value "preFuseaction"
     * 
     * @var string
     */
    const PRE_FUSEACTION_PHASE = "preFuseaction";

    /**
     * Post fuseaction fase constant<br>
     * Value "postFuseaction"
     * 
     * @var string
     */
    const POST_FUSEACTION_PHASE = "postFuseaction";

    /**
     * Post process fase constant<br>
     * Value "postProcess"
     * 
     * @var string
     */
    const POST_PROCESS_PHASE = "postProcess";

    /**
     * Process error fase constant<br>
     * Value "processError"
     * 
     * @var string
     */
    const PROCESS_ERROR_PHASE = "processError";

    /**
     * Fuseaction exception fase constant<br>
     * Value "fuseactionException"
     * 
     * @var string
     */
    const FUSEACTION_EXCEPTION_PHASE = "fuseactionException";

    /**
     * Lifecycle Phase
     *
     * @var string
     */
    private static $phase;

    /**
     * Lifecycle circuit
     *
     * @var Circuit
     */
    private static $circuit;

    /**
     * Lifecycle action
     *
     * @var CircuitAction
     */
    private static $action;

    public static function configureLocale()
    {
        $handler = I18NHandler::getInstance();

        $handler->configure();
        /*
        I18NHandler::markTimeStamp();

        I18NHandler::setLocale();

        I18NHandler::loadFiles();

        $locale = MyFuses::getApplication()->getLocale();

        bindtextdomain( "myfuses", 
            MyFuses::getApplication()->getParsedPath() . "I18n" );

        textdomain("myfuses");*/
    }

    public static function storeLocale()
    {
        $handler = I18nHandler::getInstance();
        $handler->storeFiles();
    }

    /*public static function configureApplications()
    {
        foreach (MyFuses::getInstance()->getApplications() as
            $index => $application) {
            if($index != Application::DEFAULT_APPLICATION_NAME) {
                self::configureApplication($application);
            }
        }
    }

    public static function configureApplication(Application $application) {}*/

    /**
     * Return the current lifecycle phase
     *
     * @return string
     */
    public static function getPhase()
    {
        return self::$phase;
    }

    /**
     * Set the current lifecycle phase
     *
     * @param string $phase
     */
    public static function setPhase($phase)
    {
        self::$phase = $phase;
    }

    /**
     * Return the current lifecycle action
     *
     * @return CircuitAction
     */
    public static function getAction()
    {
        return self::$action;
    }

    /**
     * Set the current lifecycle action
     *
     * @param CircuitAction $action
     */
    public static function setAction(CircuitAction $action)
    {
        self::$action = $action;
    }

    /**
     * Load all registered applications 
     */
    public static function loadApplications()
    {
        foreach (Controller::getInstance()->getApplications() as
                 $key => $application) {
             if ($key != Application::DEFAULT_APPLICATION_NAME) {
                 self::loadApplication($application);
             }
         } 
    }

    /**
     * Load one application
     *
     * @param Application $application
     */
    public static function loadApplication(Application $application)
    {
        $application->getLoader()->loadApplication();
    }

    /**
     * Builds all applications registered
     */
    public static function buildApplications()
    {
        foreach (Controller::getInstance()->getApplications() as
            $key => $application) {
            if ($key != Application::DEFAULT_APPLICATION_NAME) {
                BasicBuilder::buildApplication($application);
             }
         }
    }

    public static function enableTools()
    {
        if (Controller::getApplication()->isToolsAllowed()) {
            $appReference['path'] = ROOT_PATH . "myfuses_tools/";

            Controller::getInstance()->createApplication("myfuses",
                $appReference);

            self::loadApplication(Controller::getApplication("myfuses"));

            BasicBuilder::buildApplication(
                Controller::getApplication("myfuses")
            );
        }
    }

    public static function checkCircuit(Circuit $circuit)
    {
        if ($circuit->getName() != "MYFUSES_GLOBAL_CIRCUIT") {
            if (!is_null($circuit->getApplication()->getController()->
                getCurrentPhase())) {
                if (!$circuit->isLoaded()) {
                    $circuit->setLoaded(true);
                    $circuit->setData($circuit->getApplication()->getLoader()->
                        loadCircuit($circuit));
                    BasicBuilder::buildCircuit($circuit);
                }
            }
        }
    }
}
