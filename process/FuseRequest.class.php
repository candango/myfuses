<?php
/**
 * FuseRequest - FuseRequest.class.php
 *
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox
 * Corporation Fusebox framework.
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
 * The Original Code is myFuses "a Candango implementation of Fusebox
 * Corporation Fusebox" part .
 *
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2009.
 * All Rights Reserved.
 *
 * Contributor(s): Flavio Garcia.
 *
 * @category   controller
 * @package    myfuses
 * @author     Flavio Garcia <piraz at candango.org>
 * @copyright  Copyright (c) 2006 - 2017 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 */
require_once "myfuses/process/FuseQueue.class.php";

/**
 * FuseRequest - FuseRequest.class.php
 *
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox
 * Corporation Fusebox framework.
 *
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2009 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 */
class FuseRequest {
    
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
     * Queue that process must resolve alter request resolution
     *
     * @var FuseQueue
     */
    private $fuseQueue;
    
    /**
     * Url path extra parameters
     *
     * @var array
     */
    private $extraParams = array();
    
    public function __construct( $applicationName = null ) {
        MyFuses::setCurrentPhase( MyFusesLifecycle::BUILD_PHASE );
        
        if( is_null( $applicationName ) ) {
            $this->application = MyFuses::getInstance()->getApplication();
        }
        else{
            $this->application = MyFuses::getInstance()->getApplication(
                $applicationName );
        }
        
        $defaultFuseaction = $this->application->getDefaultFuseAction();
        
        $fuseactionVariable = $this->application->getFuseactionVariable();

        // FIXME Fixing an error occoured with CGI GATWAYS. 
        // FIXME Sppressing redirect with CGI!!!
        if(MyFuses::isRewriting()) {
                
            $root = dirname( $_SERVER[ 'SCRIPT_NAME' ] );

            if (isset($_SERVER[ 'REDIRECT_URL' ])){
                $pathX = explode("?", $_SERVER[ 'REQUEST_URI' ]);
                $path = $pathX[0];
            }


            if( $root != "/" ) {
            	$path = str_replace( $root, "", $path );	
            }
            
            // FIXME Very very strange. Must research more about this.
            $path = str_replace( 'myfuses.xml', 'myfuses', $path );
            
            if( substr( $path, -1 ) == "/" ) {
                $path = substr( $path, 0, strlen( $path ) - 1 );
            }
            
            $path = substr( $path, 1, strlen( $path ) );
            
            $pathX = explode( "/", $path );

            $this->validFuseactionName = $this->resolvePath( $pathX );
        }
        else {
            if ( isset( $_GET[ $fuseactionVariable ] ) 
                && $_GET[ $fuseactionVariable ] != '' ) {
	            $this->validFuseactionName = $_GET[ $fuseactionVariable ];
	        }
	        
	        if ( isset( $_POST[ $fuseactionVariable ] ) 
	           && $_POST[ $fuseactionVariable ] != '' ) {
	            $this->validFuseactionName = $_POST[ $fuseactionVariable ];
	        }
        }
        
        if( count( explode( ".", $this->validFuseactionName ) ) > 2 ) {
            list( $appName, $circuitName, $actionName ) = 
        	    explode( '.', $this->validFuseactionName );
            
            $this->application = MyFuses::getInstance()->getApplication( 
                $appName );
                
            if( is_null( $this->application ) ) {
                $params = array( "applicationName" => $appName );
                throw new MyFusesApplicationException( $params, 
                    MyFusesApplicationException::NON_EXISTENT_APPLICATION );    
            }
            
            $this->validFuseactionName = $circuitName . "." . $actionName;
        }
        
        if ( is_null( $this->validFuseactionName ) ) {
            $this->validFuseactionName = $defaultFuseaction;
        }
        
        list( $this->circuitName, $this->actionName ) = 
        	explode( '.', $this->validFuseactionName );
    }
    
    /**
     * Return application handled by request
     *
     * @return Application
     */
    function getApplication() {
        return $this->application;
    }
    
    /**
     * Return fuseaction handle by request
     *
     * @return FuseAction
     */
    public function getAction() {
        $action = null;
        
        $circuit = $this->application->getCircuit( $this->circuitName );
        
        $action = $circuit->getAction( $this->actionName );    
        
        return $action;
    }
    
    function getCircuitName() {
        return $this->circuitName;
    }
    
    function getActionName() {
        return $this->actionName;
    }
    
    function getFuseActionName() {
        return $this->getCircuitName() . "." . $this->getActionName();
    }
    
    function getValidFuseactionName(){
        return $this->validFuseactionName;
    }
    
    public function getXFAs() {
        return $this->application->getCircuit( 
            $this->circuitName )->getAction( $this->actionName )->getXFAs();
    }
    
    public function &retrieveGetVars() {
        return $_GET;
    }
    
    public function &retrievePostVars() {
        return $_POST;
    }
    
    public function &retrieveRequestVars() {
        return $_REQUEST;
    }
    
    public function &retrieveSessionVars() {
        return $_SESSION;
    }
    
    /**
     * Return the Request Fuse Queue
     * 
     * @return FuseQueue
     */
    public function &getFuseQueue(){
        if( is_null( $this->fuseQueue ) ) {
            $this->fuseQueue = new FuseQueue( $this );
        }
        return $this->fuseQueue;
    }
    
    public function __toString(){
        return get_class( $this ) . "( '" . $this->getFuseActionName() . "' )";
    }
    
    /**
     * Resolve an array of paths returning the valid fuseaction and storing
     * extra parameters.
     *
     * @param unknown_type $path
     * @return unknown
     */
    public function resolvePath($path) {
        $resolvedPath = "";
        
        $fuseactionVariable = $this->getApplication()->getFuseactionVariable();
        
        if( $path[ 0 ] == $fuseactionVariable || $path[ 0 ] == "" ) {
            $path = array_slice( $path, 1, count( $path ) );
        }
        
        if( count( $path ) == 0  ) {
            return $this->getApplication()->getName() . "." . 
                $this->getApplication()->getDefaultFuseaction();
        }
        
        if( count( $path ) == 1 ) {
            try {
                $circuit = $this->getApplication()->getCircuit( $path[ 0 ] );
            
                $resolvedPath = $circuit->getName();
                
                foreach( $circuit->getActions() as $action ) {
                    if( $action->isDefault() ) {
                        return $circuit->getApplication()->getName() . "." . 
                            $resolvedPath . "." . $action->getName();
                    }
                    $params = array( "actionName" => "default",
                        "circuit" => $circuit ,
                        "application" => $this->getApplication() );
                    throw new MyFusesActionException( $params,
                        MyFusesActionException::NON_EXISTENT_FUSEACTION);
                }
            }
            catch( MyFusesCircuitException $mfce ) {
                try{
                    $application = MyFuses::getApplication( $path[ 0 ] );
                    
                    return $application->getName() . "." . 
                        $application->getDefaultFuseaction();    
                }
                catch ( MyFusesApplicationException $mfae ){
                    $this->setExtraParams( $path );
                    return $this->getApplication()->getName() . "." . 
                        $this->getApplication()->getDefaultFuseaction();  
                }
                
            }
        }
        elseif( count( $path ) > 1 ) {
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
                catch ( MyFusesActionException $mffae ) {
                    foreach( $circuit->getActions() as $action ) {
                        if( $action->isDefault() ) {
                            $this->setExtraParams( array_slice( 
                                $path, 1, count( $path ) ) );
                            return $circuit->getApplication()->getName() . "." . 
                                $resolvedPath . "." . $action->getName();
                        }
                    }
                }
                
            }
            catch( MyFusesCircuitException $mfce ) {
                try {
                    $application = MyFuses::getApplication( $path[ 0 ] );
                    
                    $resolvedPath = $application->getName();
                    
                    try {
                        $circuit = $application->getCircuit( $path[ 1 ] );
                        
                        $resolvedPath = $resolvedPath . "." . 
                                    $circuit->getName();
                        
                        if( count( $path ) > 2 ) {
                            try {
                                $action = $circuit->getAction( $path[ 2 ] );
                                
                                if( count( $path > 3 ) ) {
                                    $this->setExtraParams( array_slice( 
                                            $path, 3, count( $path ) ) );
                                }
                                
                                return $resolvedPath . "." . $action->getName();
                            }
                            catch ( MyFusesActionException $mffae ) {
                                foreach( $circuit->getActions() as $action ) {
                                    if( $action->isDefault() ) {
                                        $this->setExtraParams( array_slice( 
                                            $path, 2, count( $path ) ) );
                                        return $resolvedPath . "." . 
                                            $action->getName();
                                    }
                                }
                            }    
                        } else {
                            foreach( $circuit->getActions() as $action ) {
                                if( $action->isDefault() ) {
                                    return $resolvedPath . "." . 
                                        $action->getName();
                                }
                            }
                        }
                    }
                    catch( MyFusesCircuitException $mfce ) {
                        $this->setExtraParams( array_slice( 
                                            $path, 1, count( $path ))  );
                        return $application->getName() . "." . 
                            $application->getDefaultFuseaction();
                    }
                }
                catch( MyFusesApplicationException $mfae ){
                    $this->setExtraParams( $path );
                    return $this->getApplication()->getName() . "." . 
                        $this->getApplication()->getDefaultFuseaction();
                }
                
            }
        }
    }
    
    /**
     * Return all url directory extra parameters informed
     *
     * @return array
     */
    public function getExtraParams() {
        return $this->extraParams;
    }
    
    /**
     * Set all url directory extra parameters
     *
     * @param array $extraParams
     */
    private function setExtraParams( $extraParams ) {
        $this->extraParams = $extraParams;
    }
    
}