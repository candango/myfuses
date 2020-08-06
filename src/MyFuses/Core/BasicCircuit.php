<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core;

/**
 * BasicCircuit - BasicCircuit.php
 *
 * MyFuses Circuit class
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      0f89550c6be85b53bee3784b73d0961d23f7c2dc
 */
class BasicCircuit implements Circuit
{
    /**
     * Circuit application reference
     *
     * @var Application
     */
    private $application;

    /**
     * Circuit name
     *
     * @var string
     */
    private $name;

    /**
     * Circuit path
     * 
     * @var string
     */
    private $path;

    private $built = false;

    private $verbPaths = array();

    /**
     * Circuit access type
     *
     * @var integer
     */
    private $access;

    /**
     * Circuit permissions paramter
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
     * Cicuit actions
     *
     * @var array
     */
    private $actions = array();

    private $file;
    
    /**
     * Circuit cache data
     *
     * @var array
     */
    private $data = array();

    /**
     * Application parent name
     * 
     * @var string
     */
    private $parentName = "";

    /**
     * Application parent
     * 
     * @var Application
     */
    private $parent;

    /**
     * Last time that circuit was loaded
     *
     * @var integer
     */
    private $lastLoadTime = 0;

    private $preFuseAction;

    private $postFuseAction;

    /**
     * Circuit modified flag
     *
     * @var boolean
     */
    private $modified = false;

    /**
     * Circuit loaded flag
     *
     * @var boolean
     */
    private $loaded = false;

    /**
     * Custom attributes defined by develloper
     * 
     * @var array 
     */
    private $customAttributes = array();

    /**
     * Return circuit application
     *
     * @return Application
     */
    public function &getApplication()
    {
        return $this->application;
    }

    /**
     * Set circuit application
     * 
     * @param Application $application
     */
    public function setApplication(Application &$application)
    {
        $this->application = &$application;
    }

    /**
     * Return the circuit name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the circuit name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return the circuit path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return the circuit complete path
     *
     * @return string
     */
    public function getCompletePath()
    {
        return $this->getApplication()->getPath() . $this->getPath();
    }

    /**
     * Return the cache file complete path
     *
     * @return string
     */
    public function getCompleteCacheFile()
    {
        return $this->getApplication()->getParsedPath() . $this->getName() .
            ".circuit.myfuses.php";
    }

    /**
     * Return the complete path for cache data file
     *
     * @return string
     */
    public function getCompleteCacheDataFile()
    {
        return $this->getApplication()->getParsedPath() . $this->getName() .
            ".circuit.myfuses.data";
    }

    /**
     * Set the circuit path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Return circuit verb paths
     *
     * @return array
     */
    public function getVerbPaths()
    {
        return $this->verbPaths;
    }

    /**
     * Return one verb path
     *
     * @param string $name
     * @return string
     */
    public function getVerbPath($name)
    {
        return $this->verbPaths[$name];
    }

    /**
     * Set circui verb paths
     *
     * @param array $verbPaths
     */
    public function setVerbPaths($verbPaths)
    {
        $verbPaths = unserialize($verbPaths);
        foreach ($verbPaths as $key => $path) {
            $verbPaths[ $key ] = preg_replace_callback(
                '@{([\$?\w+][\:\:\w+\(\)]*[->\w+\(\)]*)}@', 
                array($this, 'evalExpression'), $path);
        }
        $this->verbPaths = $verbPaths;
    }

    /**
     * Return if a given verbPath exists
     * 
     * @param string $verbPath
     * @return boolean
     */
    public function verbPathExists($verbPath)
    {
        return isset($this->verbPaths[$verbPath]);
    }

    /**
     * Return the circuit access
     *
     * @return integer
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Return circuit access name
     *
     * @return string
     */
    public function getAccessName()
    {
        switch ($this->getAccess()) {
            case self::PUBLIC_ACCESS :
                return "public";
            case self::INTERNAL_ACCESS :
                return "internal";
            case self::PRIVATE_ACCESS :
                return "private";
        }
    }

    /**
     * Set the circuit access
     *
     * @param integer $access
     */
    public function setAccess($access = Circuit::PUBLIC_ACCESS)
    {
        $this->access = $access; 
    }

	/**
     * Set the circuit access using a string
     *
     * @param string $accessString
     */
    public function setAccessByString($accessString = "public")
    {
        if ($accessString == "") {
            $accessString = "public";
        }
            
        $accessList = array(
            "public" => self::PUBLIC_ACCESS,
            "internal" => self::INTERNAL_ACCESS
        );
        $this->setAccess($accessList[$accessString]);
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
    public function getSecurity(){
        return $this->security;
    }

    /**
     * Set security mode
     *
     * @param $security
     */
    public function setSecurity($security) {
        $allowedModes = array("optimistic", "pessimistic", "disabled");
        if (in_array($security, $allowedModes)) {
            $this->security = $security;
        } else {
            // Get from the from the application
            $this->security = $this->getApplication()->getSecurity();
        }
    }

    /**
     * Add one action to circuit
     * 
     * @param Action $action
     */
    public function addAction(Action $action)
    {
         $this->actions[$action->getName()] = $action;
    }

    /**
     * Return the action by name
     *
     * @param string $name
     * @return FuseAction
     * @throws MyFusesActionException
     */
    public function getAction($name)
    {
        if (isset($this->actions[$name])) {
    		return $this->actions[$name];
    	}
    	$params = array("actionName" => $name, "circuit" => &$this ,
    	    "application" => $this->getApplication());
    	throw new MyFusesActionException($params,
    	    MyFusesActionException::NON_EXISTENT_FUSEACTION);
    }

    /**
     *
     * Returns the default action if it is defined
     *
     * @return FuseAction
     * @throws MyFusesActionException
     */
    public function getDefaultAction()
    {
        foreach ($this->actions as $name => $action) {
            if ($action->isDefault()) {
                return $this->actions[$name];
            }
        }

        $params = array("actionName" => "Default circuit action",
            "circuit" => &$this ,
            "application" => $this->getApplication());
        throw new MyFusesActionException($params,
            MyFusesActionException::NON_EXISTENT_FUSEACTION);
    }

    /**
     * 
     */
    public function hasAction($name)
    {
       return isset($this->actions[$name]);
    }

    /**
     * Returns true if the circuit has a default action
     *
     * @return bool
     */
    public function hasDefaultAction()
    {
        foreach ($this->actions as $action) {
            if ($action->isDefault()) {
                return true;
            }
        }
        return false;
    }

    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Enter description here...
     *
     * @return CircuitAction
     */
    public function getPreFuseAction()
    {
        return $this->preFuseAction;
    }

	/**
	 * Enter description here...
	 *
	 * @param CircuitAction $action
	 */
	public function setPreFuseAction(CircuitAction $action)
    {
        // TODO: Check if we need to return something here
	    return $this->preFuseAction = $action;
	}

	public function unsetPreFuseAction()
    {
	    $this->preFuseAction = null;
	}

	/**
	 * Enter description here...
	 *
	 * @return CircuitAction
	 */
	public function getPostFuseAction()
    {
	    return $this->postFuseAction;
	}

	/**
	 * Enter description here...
	 *
	 * @param CircuitAction $action
	 */
	public function setPostFuseAction(CircuitAction $action)
    {
        // TODO: Check if we need to return something here
	    return $this->postFuseAction = $action;
	}

    public function unsetPostFuseAction()
    {
	    $this->postFuseAction = null;
	}

    /**
     * Return the circuit complete file
     * 
     * complete path + file
     *
     * @return string
     */
    public function getCompleteFile()
    {
        return $this->getCompletePath() . $this->getFile();
    }

    /**
     * Return the circuit file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the circuit file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

	/**
     * Return the application parent name
     *
     * @return string
     */
    public function getParentName()
    {
        return $this->parentName;
    }

    /**
     * Set the applciation parent name.<br>
     * When parent name is seted the parent reference is seted to null.
     * 
     * @param string $parentName
     */
    public function setParentName($parentName)
    {
        if ($parentName == null) {
            $parentName = "";
        }
        $this->parent = null;
        $this->parentName = $parentName;
    }

    /**
     * Return the application parent
     * 
     * @return Circuit
     */
    public function getParent()
    {
        if ($this->parentName != "") {
            return $this->getApplication()->getCircuit($this->parentName);
        }
        return null;
    }

    /**
     * Set the application parent
     * 
     * @param Circuit $parent
     */
    public function setParent(Circuit $parent)
    {
        $this->parentName = $parent->getName();
        $this->parent = $parent;
    }

    /**
     * Return the circuit last load time
     *
     * @return integer
     */
    public function getLastLoadTime()
    {
        return $this->lastLoadTime;
    }

    /**
     * Sets the circuit last load time
     * 
     * @param integer $lastLoadTime
     */
    public function setLastLoadTime($lastLoadTime)
    {
        $this->lastLoadTime = $lastLoadTime;
    }

    public function isModified()
    {
        return $this->modified;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    /**
     * Return if the circuit was loaded
     *
     * @return boolean
     */
    public function isLoaded()
    {
        return $this->loaded;
    }

    /**
     * Set if the circuit was loaded
     *
     * @param boolean $loaded
     */
    public function setLoaded($loaded)
    {
        $this->loaded = $loaded;
    }

    private function evalExpression($matches)
    {
	    return eval("return " . $matches[1] . ";");
    }

    /**
     * Return if circuit was built
     *
     * @return boolean
     */
    public function wasBuilt()
    {
        return $this->built;
    }

    /**
     * Return the circuit cache data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set circuit cache data
     *
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Set circuit built status
     *
     * @param boolean $built
     */
    public function setBuilt($built)
    {
        $this->built = $built;
    }

    public function setCustomAttribute($namespace, $name, $value)
    {
        $this->customAttributes[$namespace][$name] = $value;
    }

    public function getCustomAttribute($namespace, $name)
    {
        return $this->customAttributes[$namespace][$name];
    }

    public function getCustomAttributes($namespace)
    {
        return $this->customAttributes[$namespace];
    }

    public function getErrorParams()
    {
        $params = array(
            "appName" => $this->getApplication()->getName(),
            "circuitName" => $this->getName(),
            "circuitFile" => $this->getCompleteFile()
        );
        return $params;
    }

    public function getCachedCode()
    {
        $strOut = "\$circuit = " . get_class(
            $this->getApplication()->getController()) .
            "::getApplication(\"" . $this->getApplication()->getName() .
            "\")->getCircuit(\"" . $this->getName() . "\");\n";

        foreach ($this->customAttributes as $namespace => $attributes) {
            foreach ($attributes as $name => $value) {
                $strOut .= "\$circuit->setCustomAttribute(\"" . $namespace .
                    "\", \"" . $name . "\", \"" . $value . "\");\n";
            }
        }
        $strOut .= "\$circuit->setVerbPaths(\"" . addslashes(
            serialize($this->getVerbPaths())) . "\");\n";
        $strOut .= "\$circuit->setAccess(" . $this->getAccess() . ");\n";
        $strOut .= "\$circuit->setPermissions(\"" . $this->getPermissions() .
            "\");\n";
        $strOut .= "\$circuit->setSecurity(\"" . $this->getSecurity() .
            "\");\n";
        $strOut .= "\$circuit->setLastLoadTime(" .
            $this->getLastLoadTime() . ");\n";
        $strOut .= "\$circuit->setParentName(\"" .
            $this->getParentName() . "\");\n";
        if ($this->getName() !== "MYFUSES_GLOBAL_CIRCUIT") {
            $fileHandlerClass = "Candango\\MyFuses\\Util\\FileHandler";
            $strOut .= "\$circuit->setData(unserialize(" .
                $fileHandlerClass . "::readFile(\"" .
                $this->getCompleteCacheDataFile() . "\")));\n";
        } else {
            $strOut .= "\$circuit->setData(array());\n";
            $strOut .= $this->getPreFuseActionCachedCode();
            $strOut .= $this->getPostFuseActionCachedCode();
            $strOut .= $this->getActionsCachedCode();
        }
        return $strOut;
    }

    /**
     * Returns all circuit actions cache code
     * 
     * @return string
     */
    private function getActionsCachedCode()
    {
        $strOut = "\n";
        foreach ($this->actions as $action) {
            $strOut .= $action->getCachedCode();
            $strOut .= "\$circuit->addAction(\$action);\n";
        }
        return $strOut;
    }

    /**
     * Returns pre fuse action cache code
     * 
     * @return string
     */
    private function getPreFuseActionCachedCode()
    {
        $strOut = "";
        if (!(is_null($this->preFuseAction))) {
            $strOut = "\n" . $this->preFuseAction->getCachedCode();
            $strOut .= "\$circuit->setPreFuseAction(\$action);\n";
        }
        return $strOut;
    }

    /**
     * Returns post fuse action cache code
     * 
     * @return string
     */
    private function getPostFuseActionCachedCode()
    {
        $strOut = "";
        if (!( is_null($this->postFuseAction))) {
            $strOut = "\n" . $this->postFuseAction->getCachedCode();
            $strOut .= "\$circuit->setPostFuseAction(\$action);\n";
        }
        return $strOut;
    }

}
