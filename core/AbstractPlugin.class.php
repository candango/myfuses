<?php
/**
 * AbstractPlugin  - AbstractPlugin.class.php
 * 
 * This is a functional abstract MyFuses plugin implementation. One concrete
 * Plugin must extends this class.
 * 
 * PHP version 5
 * 
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * This product includes software developed by the Fusebox Corporation 
 * (http://www.fusebox.org/).
 * 
 * The Original Code is Fuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2007.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "core/Plugin.class.php" );  

/**
 * AbstractPlugin  - AbstractPlugin.class.php
 * 
 * This is a functional abstract MyFuses plugin implementation. One concrete
 * Plugin must extends this class.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
abstract class AbstractPlugin implements Plugin{
    
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
     * @TODO Maybe this attribute will be a class like MyFusesFase
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
     * Return the plugin name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set the plugin name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Return the plugin file
     *
     * @return string
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Set the plugin file
     *
     * @param string $file
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
     /**
     * Return the plugin template
     *
     * @return string
     */
    public function getTemplate() {
        return $this->getFile();
    }
    
    /**
     * Set the plugin template
     *
     * @param string $file
     */
    public function setTemplate( $file ) {
        $this->setFile( $file );
    }
    
    /**
     * Return the plugin path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Set the plugin path
     *
     * @param string $path
     */
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * Returns the plugin phase
     *
     * @return string
     */
    public function getPhase() {
        return $this->phase;
    }
    
    /**
     * Set the application fase
     *
     * @param string $phase
     */
    public function setPhase( $phase ) {
        $this->phase = $phase;
    }
    
    /**
     * Returns the plugin index
     *
     * @return integer
     */
    public function getIndex() {
        return $this->index;
    }
    
    /**
     * Set the plugin index
     *
     * @param integer
     */
    public function setIndex( $index ) {
        $this->index = $index;
    }
    
    /**
     * Return plugin application
     *
     * @return Application
     */
    public function getApplication() {
        return $this->application;
    }
    
    /**
     * Set plugin application
     *
     * @param Application $application
     */
    public function setApplication( Application $application ) {
        $this->application = $application;
    }
    
    /**
     * Clear application plugin
     */
    public function clearApplication() {
        $this->application = null;
    }
    
    /**
     * Return a new plugin instance
     * 
     * @param Application $application
     * @param string $phase
     * @param string $name
     * @param string $path
     * @param string $file
     * 
     * @return Plugin
     */
    public static function getInstance( Application $application, 
        $phase, $name, $path, $file ) {
        
        $class = $name;
            
        if( substr( $name, -6 ) != "Plugin" ) {
            $class .= "Plugin";   
        }
            
        if( $file == "" ) {
            if( substr( $name, -6 ) == "Plugin" ) {
                $file = $name . ".class.php";   
            }
            else {
                $file = $name . "Plugin.class.php";    
            }
        }
        
        // FIXME handle missing file include exception
        if( $path == "" ) {
            foreach( $application->getController()->getPluginPaths() as 
                $path ) {
                
                $tmpPath = "";
                
                if( MyFusesFileHandler::isAbsolutePath( $path ) ) {
                    $tmpPath = $path;
                }
                else {
                    $tmpPath = $application->getPath() . $path;
                }
                
                if( is_file( $tmpPath . $file ) ) {
                    $path = $tmpPath; break;
                }
            }
        }
        
        require_once $path . $file;
        
        $plugin = new $class();
        
        $plugin->setName( $name );
        $plugin->setPath( $path );
        $plugin->setFile( $file );
        $plugin->setPhase( $phase );
        
        $application->addPlugin( $plugin );
        
        return $plugin;
    }
    
    public function getCachedCode() {
        $strOut = "AbstractPlugin::getInstance( \$application, \"" . 
			$this->phase . "\", \"" . $this->name . "\", \"" . 
			addslashes( $this->path ) . 
			"\", \"" . $this->file . "\" );\n";
		return $strOut;
    }
    
    public function getParsedCode( $comented, $identLevel ) {
        $controllerClass = $this->getApplication()->getControllerClass();
        $strOut = "\$plugin = " . $controllerClass . "::getApplication( \"" . 
            $this->application->getName() . "\" )->getPlugin(" .
                " \"" . $this->phase . "\", " . $this->index . "  );\n";
        $strOut .= "\$plugin->run();\n\n";
        return $strOut;
    }
    
    public function getComments( $identLevel ) {
            
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */