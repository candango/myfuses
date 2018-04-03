<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core;

use Candango\MyFuses\Util\FileHandler;

/**
 * AbstractPlugin  - AbstractPlugin.php
 *
 * This is a functional abstract MyFuses plugin implementation. One concrete
 * Plugin must extends this class.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      7705af2489d62aa077eeb5885a29b46a36170361
 */
abstract class AbstractPlugin implements Plugin
{
    /**
     * Plugin name
     *
     * @var string
     */
    private $name;

    /**
     * Plugin file
     *
     * @var string
     */
    private $file;

    /**
     * Plugin path
     *
     * @var string
     */
    private $path;

    /**
     * Plugin phase
     *
     * @var string
     * @TODO Maybe this attribute will be a class like MyFusesPhase
     */
    private $phase;

    /**
     * Plugin index
     * 
     * @var integer
     */
    private $index;

    /**
     * Plugin application
     * 
     * @var application
     */
    private $application;

    /**
     * Plugins parameters
     *
     * @var array
     */
    private $parameters = array();

    /**
     * Return the plugin name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the plugin name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return the plugin file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the plugin file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

     /**
     * Return the plugin template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->getFile();
    }

    /**
     * Set the plugin template
     *
     * @param string $file
     */
    public function setTemplate($file)
    {
        $this->setFile($file);
    }

    /**
     * Return the plugin path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the plugin path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Returns the plugin phase
     *
     * @return string
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * Set the application fase
     *
     * @param string $phase
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;
    }

    /**
     * Returns the plugin index
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set the plugin index
     *
     * @param integer
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * Return plugin application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set plugin application
     *
     * @param Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Clear application plugin
     */
    public function clearApplication()
    {
        $this->application = null;
    }

    /**
     * Add one parameter to plugin
     *
     * @param string $name
     * @param string $value
     */
    public function addParameter($name, $value)
    {
        $parameter = array("name" => $name, "value" => $value);
        $this->parameters[] = $parameter;
    }

    /**
     * Get plugins parameters
     * 
     * @return array An array of parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Enter description here...
     *
     * @param array $parameters
     */
    public function setParameters( $parameters )
    {
        $this->parameters = $parameters;
    }

    /**
     * Get one parameter by a given name
     * 
     * @return strin The paramter name
     */
    public function getParameter($name)
    {
        $params = array();

        foreach($this->parameters as $param) {
            if($param['name'] == $name) {
                $params[] = $param['value'];
            }
        }
        //TODO throw an exception task #17
        return $params;
    }

    /**
     * Return a new plugin instance
     * 
     * @param Application $application
     * @param string $phase
     * @param string $name
     * @param string $path
     * @param string $file
     * @param array $parameters
     * 
     * @return Plugin
     */
    public static function getInstance(
        Application $application,
        $phase,
        $name,
        $path,
        $file,
        $parameters = array()
    ) {
        $class = $name;

        if(substr($name, -6) !== "Plugin") {
            $class .= "Plugin";   
        }

        if($file == "") {
            if(substr($name, -6) === "Plugin") {
                $file = $name . ".php";
            } else {
                $file = $name . "Plugin.php";
            }
        }

        // FIXME: handle missing included file exception
        if($path == "") {
            foreach ($application->getController()->getPluginPaths() as $path) {
                $path = FileHandler::sanitizePath($path);

                $tmpPath = "";

                if (FileHandler::isAbsolutePath($path)) {
                    $tmpPath = $path;
                } else {
                    $tmpPath = $application->getPath() . $path;
                }
                
                if (file_exists($tmpPath . $file)) {
                    $path = $tmpPath;
                    break;
                }
            }
        }

        require_once FileHandler::sanitizePath($path) . $file;

        $plugin = new $class();
        $plugin->setName($name);
        $plugin->setPath($path);
        $plugin->setFile($file);
        $plugin->setPhase($phase);
        $plugin->setParameters($parameters);

        $application->addPlugin($plugin);

        return $plugin;
    }
    
    public function getParsedCode($comented, $identLevel)
    {
        $controllerClass = $this->getApplication()->getControllerClass();
        $strOut = "\$plugin = " . $controllerClass . "::getApplication( \"" . 
            $this->application->getName() . "\" )->getPlugin(" .
                " \"" . $this->phase . "\", " . $this->index . "  );\n";
        $strOut .= "\$plugin->run();\n\n";
        return $strOut;
    }

    public function getComments($identLevel)
    {
    }

    public function getCachedCode()
    {
        $abstractPluginClass = "Candango\\MyFuses\\Core\\AbstractPlugin";
        $strOut = "\$plugin = " . $abstractPluginClass .
            "::getInstance( \$application, \"" .
            $this->phase . "\", \"" . $this->name . "\", \"" . 
            addslashes( $this->path ) . 
            "\", \"" . $this->file . "\" );\n";

        foreach($this->getParameters() as $parameter) {
            $strOut .= "\$plugin->addParameter( \"" . $parameter[ 'name' ] . 
                "\", \"" . addslashes($parameter[ 'value' ]) . "\" );\n";
        }
        return $strOut;
    }
}
