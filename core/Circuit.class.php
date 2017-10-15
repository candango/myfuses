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

require_once "myfuses/core/Application.class.php";
require_once "myfuses/core/CircuitAction.class.php";

/**
 * Circuit - Circuit.php
 *
 * MyFuses Circuit interface
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f06b361b3bc6909ebf21f108d42b79a17cfb3924
 */
interface Circuit extends ICacheable
{
    /**
     * Public Access Constant.<br>
     * Value 1
     * 
     * @var int
     */
    const PUBLIC_ACCESS = 1;

    /**
     * Internal Access Constant.<br>
     * Value 2
     * 
     * @var int
     */
    const INTERNAL_ACCESS = 2;

    /**
     * Private Access Constant.<br>
     * Value 2
     * 
     * @var int
     */
    const PRIVATE_ACCESS = 3;

    /**
     * Return circuit application
     *
     * @return Application
     */
    public function &getApplication();

    /**
     * Set circuit application
     * 
     * @param Application $application
     */
    public function setApplication(Application &$application);

    /**
     * Return the circuit name
     *
     * @return string
     */
    public function getName();

    /**
     * Set the circuit name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Return the circuit path
     *
     * @return string
     */
    public function getPath();

    /**
     * Return the circuit complete path
     *
     * @return string
     */
    public function getCompletePath();

    /**
     * Return the complete path for cache file
     *
     * @return string
     */
    public function getCompleteCacheFile();

    /**
     * Set the circuit path
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * Return circuit verb paths
     *
     * @return array
     */
    public function getVerbPaths();

    /**
     * Return one verb path
     *
     * @param string $name
     * @return string
     */
    public function getVerbPath($name);

    /**
     * Set circuit verb paths
     *
     * @param array $verbPaths
     */
    public function setVerbPaths($verbPaths);

    /**
     * Return if a given verbPath exists
     * 
     * @param string $verbPath
     * @return boolean
     */
    public function verbPathExists($verbPath);

    /**
     * Return the circuit access
     *
     * @return integer
     */
    public function getAccess();

    /**
     * Return circuit access name
     *
     * @return string
     */
    public function getAccessName();

    /**
     * Set the circuit access
     *
     * @param integer $access
     */
    public function setAccess($access = Circuit::PUBLIC_ACCESS);

	/**
     * Set the circuit access using a string
     *
     * @param string $accessString
     */
    public function setAccessByString($accessString = "public");

    /**
     * Return the pemissions parameter
     * 
     * @return string
     */
    public function getPermissions();

    /**
     * Set the circuit permissions parameter
     * 
     * @param $permissions
     */
    public function setPermissions($permissions);

    /**
     * Add one action to circuit
     * 
     * @param Action $action
     */
    public function addAction(Action $action);

    /**
     * Return one Circuit by name
     *
     * @param string $name
     * @return FuseAction
     * @throws MyFusesActionException
     */
    public function getAction($name);

    /**
     * 
     */
    public function hasAction($name);

    public function getActions();

    /**
     * Enter description here...
     *
     * @return CircuitAction
     */
    public function getPreFuseAction();

	/**
	 * Enter description here...
	 *
	 * @param CircuitAction $action
	 */
	public function setPreFuseAction(CircuitAction $action);

	public function unsetPreFuseAction();

	/**
	 * Enter description here...
	 *
	 * @return CircuitAction
	 */
	public function getPostFuseAction();

	/**
	 * Enter description here...
	 *
	 * @param CircuitAction $action
	 */
	public function setPostFuseAction(CircuitAction $action);

    public function unsetPostFuseAction();

    /**
     * Return the circuit complete file
     * 
     * complete path + file
     *
     * @return string
     */
    public function getCompleteFile();

    /**
     * Return the circuit file
     *
     * @return string
     */
    public function getFile();

    /**
     * Set the circuit file
     *
     * @param string $file
     */
    public function setFile($file);

	/**
     * Return the application parent name
     *
     * @return string
     */
    public function getParentName();

    /**
     * Set the applciation parent name.<br>
     * When parent name is seted the parent reference is seted to null.
     * 
     * @param string $parentName
     */
    public function setParentName($parentName);

    /**
     * Return the application parent
     * 
     * @return Circuit
     */
    public function getParent();

    /**
     * Set the application parent
     * 
     * @param Circuit $parent
     */
    public function setParent(Circuit $parent);

    /**
     * Return the circuit last load time
     *
     * @return integer
     */
    public function getLastLoadTime();

    /**
     * Sets the circuit last load time
     * 
     * @param integer $lastLoadTime
     */
    public function setLastLoadTime($lastLoadTime);

    public function isModified();

    public function isLoaded();

    public function setLoaded($loaded);

    public function setModified($modified);

    /**
     * Return if circuit was built
     *
     * @return boolean
     */
    public function wasBuilt();

    /**
     * Return the circuit cache data
     *
     * @return array
     */
    public function getData();

    /**
     * Set circuit cache data
     *
     * @param array $data
     */
    public function setData($data);

    /**
     * Set circuit built status
     *
     * @param boolean $built
     */
    public function setBuilt($built);

    public function setCustomAttribute($namespace, $name, $value);

    public function getCustomAttribute($namespace, $name);

    public function getCustomAttributes($namespace);

    public function getErrorParams();

}
