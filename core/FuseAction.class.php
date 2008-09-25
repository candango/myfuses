<?php
/**
 * FuseAction  - FuseAction.class.php
 * 
 * FuseAction is the real action executed by one Circuit. When you acess some
 * Circuit.Action MyFuses will resolve some FuseAction in fact. 
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

require_once "myfuses/core/AbstractAction.class.php";
require_once "myfuses/core/CircuitAction.class.php";

/**
 * FuseAction  - FuseAction.class.php
 * 
 * FuseAction is the real action executed by one Circuit. When you acess some
 * Circuit.Action MyFuses will resolve some FuseAction in fact. 
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
 * @since      Revision 25
 */
class FuseAction extends AbstractAction implements CircuitAction {
    
    /**
     * Enter description here...
     *
     * @var Circuit
     */
    private $circtuit;
    
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
     * Call prefuseaction flag
     *
     * @var boolean
     */
    private $callPreFuseaction = true;
    
    public function __construct( Circuit $circuit ) {
        $this->setCircuit( $circuit );
    }
    
    
    /**
     * Return Circuit Action complete name.<br>
     * Complete name is circuit name plus dot plus action name.
     *
     * return string
     */
    public function getCompleteName() {
        return $this->getCircuit()->getName() . "." . $this->getName();
    }
    
	/**
     * Enter description here...
     *
     * @return Circuit
     */
    public function &getCircuit() {
        $circuit = $this->circtuit;
        
        MyFusesLifecycle::seekCircuit( $circuit );
        
        return $this->circtuit;
    }
    
    /**
     * Enter description here...
     *
     * @param Circuit $circuit
     */
    public function setCircuit( Circuit &$circuit ) {
        $this->circtuit = &$circuit;
    }
    
    /**
     * Enter description here...
     *
     * @param Verb $verb
     */
    public function addVerb( Verb $verb ) {
        $this->verbs[] = $verb;
        $verb->setAction( $this );
    }
    
    /**
     * Enter description here...
     *
     * @param string $name
     * @return Verb
     */
    public function getVerb( $name ) {
        return $this->verbs[ $name ];
    }
    
    /**
     * Enter description here...
     *
     * @return array
     */
    public function &getVerbs() {
        return $this->verbs;
    }
    
    public function getXFAs() {
        return $this->xfas;
    }
    
    public function addXFA( $name, $value ) {
        $this->xfas[ $name ] = $value;
    }
    
    public function getXfa( $name ) {
        return $this->xfas[ $name ];
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * Return if the fuseaction must call prefusection
     *
     * @return boolean
     */
    public function mustCallPreFuseaction() {
        return $this->callPreFuseaction;
    }
    
    /**
     * Set if the fuseaction must call prefuseaction
     *
     * @param boolean $callPreFuseaction
     */
    public function setCallPreFuseaction( $callPreFuseaction ) {
        $this->callPreFuseaction = $callPreFuseaction;
    }
    
    public function getParsedCode( $comented, $identLevel ) {
        $strOut = "";
        
        $application = $this->getCircuit()->getApplication();
        
        $controllerClass = $this->getCircuit()->
            getApplication()->getControllerClass();
        
        $myFusesString = $controllerClass . "::getInstance()";
        
        $actionString = "\"" . $this->circtuit->getApplication()->getName() .
            "." . $this->circtuit->getName() . 
            "." . $this->getName() . "\"";
        
        if( $this->getCircuit()->getName() != "MYFUSES_GLOBAL_CIRCUIT" ) {
            if( $this->getName() != "prefuseaction" && 
                $this->getName() != "postfuseaction" ) {
                
                $strOut .= $myFusesString . "->setCurrentProperties( \"" . 
                        MyFusesLifecycle::PRE_FUSEACTION_PHASE . "\", "  . 
                        $actionString . " );\n\n";    
                
                // parsing pre fuseaction plugins
                if( count( $this->getCircuit()->getApplication()->getPlugins( 
                    Plugin::PRE_FUSEACTION_PHASE ) ) ) {
                    $pluginsStr = $controllerClass . 
                        "::getInstance()::getApplication( \"" . 
                        $application->getName() . "\" )->getPlugins(" .
                        " \"" . Plugin::PRE_FUSEACTION_PHASE . "\" )";
                    $strOut .= "foreach( " . $pluginsStr . " as \$plugin ) {\n";
                    $strOut .= "\t\$plugin->run();\n}\n\n";
                }
                //end parsing pre fuseaction plugins
                 
            }
        }
        
        $action = null;
        
        if( !is_null( $action ) ) {
            $strOut .= $action->getParsedCode( $comented, $identLevel );    
        }
        
        $request = MyFuses::getInstance()->getRequest();
        
        if( ( $this->getCircuit()->getName() == $request->getCircuitName() ) &&  
            ( $this->getName() == $request->getActionName() )  ) {
           $strOut .= $myFusesString . "->setCurrentProperties( \"" . 
                MyFusesLifecycle::PROCESS_PHASE . "\", "  . 
                $actionString . " );\n\n";
        }
        
        if( get_class( $this ) != "FuseAction" ) {
            $strOut .= $actionString . "->doAction();\n";    
        }
        
        foreach( $this->verbs as $verb ) {
            $strOut .= $verb->getParsedCode( $comented, $identLevel );
        }
        
        if( $this->getCircuit()->getName() != "MYFUSES_GLOBAL_CIRCUIT" ) {
            if( $this->getName() != "prefuseaction" && 
                $this->getName() != "postfuseaction" ) {
                $strOut .= $myFusesString . "->setCurrentPhase( \"" . 
                    MyFusesLifecycle::POST_FUSEACTION_PHASE . "\" );\n\n";
                
                if( !is_null( $action ) ) {
                    $strOut .= $action->getParsedCode( $comented, $identLevel );
                }
                
                $strOut .= $myFusesString . "->setCurrentAction( "  . 
                    $actionString . " );\n\n";
                
                // parsing post fuseaction plugins
                if( count( $this->getCircuit()->getApplication()->getPlugins( 
                    Plugin::POST_FUSEACTION_PHASE ) ) ) {
                    $pluginsStr = $controllerClass . 
                        "::getInstance->getApplication( \"" . 
                        $application->getName() . "\" )->getPlugins(" .
                        " \"" . Plugin::POST_FUSEACTION_PHASE . "\" )";
                    $strOut .= "foreach( " . $pluginsStr . " as \$plugin ) {\n";
                    $strOut .= "\t\$plugin->run();\n}\n\n";
                }
                //end parsing post fuseaction plugins
            }
        }
        
        return $strOut;
    }
    
    public function getComments( $identLevel ) {
        return "";
    }
    
    /**
     * 
     */
    public function wasCalledByDo() {
        return $this->calledByDo;
    }
    
    /**
     * 
     */
    public function setCalledByDo( $calledByDo ) {
        $this->calledByDo = $calledByDo;
    }
    
    public function getErrorParams() {
        $params = $this->getCircuit()->getErrorParams();
        // FIXME CircuitAction must have a name
        $params[ 'actionName' ] = $this->getName();
        return $params;
    }
    
	public function doAction() {
	    
	}
	
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getCachedCode() {
        $strOut = "";
        if( !is_null( $this->getPath() ) ) {
            $strOut .= "require_once \"" . $this->getPath() . "\";\n";
        }
        $strOut .= "\$action = new " . get_class( $this ) . "( \$circuit );\n";
        if( !is_null( $this->getPath() ) ) {
            $strOut .= "\$action->setPath( \"" . $this->getPath() . "\" );\n";    
        }
        $strOut .= "\$action->setName( \"" . $this->getName() . "\" );\n";
        foreach( $this->customAttributes as $namespace => $attributes ) {
            foreach( $attributes as $name => $value ) {
                $strOut .= "\$action->setCustomAttribute( \"" . $namespace . 
                    "\", \"" . $name . "\", \"" . $value . "\" );\n";
            }
        }
        
        $strOut .= $this->getVerbsCachedCode();
        return $strOut;
    }
    
    /**
     * Returns all Action Verbs cache code
     * 
     * @return string
     */
    private function getVerbsCachedCode() {
        
        $strOut = "\n";
        
        foreach( $this->verbs as $verb ) {
            $strOut .= $verb->getCachedCode() . "\n";
            $strOut .= "\$action->addVerb( \$verb );\n\n";
        }
        
        return $strOut;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */