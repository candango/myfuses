<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core;

/**
 * Plugin  - Plugin.php
 * 
 * This is MyFuses plugin interface. Defines how one interfece must to be.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      7705af2489d62aa077eeb5885a29b46a36170361
 */
interface Plugin extends IParseable, ICacheable
{
	/**
     * Fuseaction exception fase constant<br>
     * Value "fuseactionException"
     * 
     * @var string
     */
    const FUSEACTION_EXCEPTION_PHASE = "fuseactionException";

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
     * Return the plugin name
     *
     * @return string
     */
    public function getName();

    /**
     * Set the plugin name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Return the plugin file
     *
     * @return string
     */
    public function getFile();

    /**
     * Set the plugin file
     *
     * @param string $file
     */
    public function setFile($file);

    /**
     * Return the plugin template
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Set the plugin template
     *
     * @param string $file
     */
    public function setTemplate($file);

    /**
     * Return the plugin path
     *
     * @return string
     */
    public function getPath();

    /**
     * Set the plugin path
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * Returns the plugin phase
     *
     * @return string
     */
    public function getPhase();

    /**
     * Set the application phase
     *
     * @param string $phase
     */
    public function setPhase($phase);

    /**
     * Returns the plugin index
     *
     * @return integer
     */
    public function getIndex();

    /**
     * Set the plugin index
     *
     * @param integer $index
     */
    public function setIndex($index);

    /**
     * Return plugin application
     *
     * @return Application
     */
    public function getApplication();

    /**
     * Set plugin application
     *
     * @param Application $application
     */
    public function setApplication(Application $application);

    /**
     * Clear application plugin
     */
    public function clearApplication();

    /**
     * Add one parameter to plugin
     *
     * @param string $name
     * @param string $value
     */
    public function addParameter($name, $value);

    /**
     * Get plugins parameters
     * 
     * @return array An array of paramters
     */
    public function getParameters();

    /**
     * Enter description here...
     *
     * @param array $parameters
     */
    public function setParameters($parameters);

    /**
     * Get one parameter by a given name
     * 
     * @return string The parameter name
     */
    public function getParameter($name);

    /**
     * This is the method that runs plugin action.
     *
     */
    public function run();
}
