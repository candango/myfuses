<?php
require_once "myfuses/process/FuseQueue.class.php";

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
            $this->application = & MyFuses::getInstance()->getApplication();    
        }
        else{
            $this->application = & MyFuses::getInstance()->getApplication( 
                $applicationName );
        }
        
        $defaultFuseaction = $this->application->getDefaultFuseAction();
        
        $fuseactionVariable = $this->application->getFuseactionVariable();
        
        if( isset( $_SERVER[ 'REDIRECT_STATUS' ] ) && 
            $this->getApplication()->allowRewrite() ) {
            
            $root = dirname( $_SERVER[ 'SCRIPT_NAME' ] );
            
            $path = str_replace( $root, "", $_SERVER[ 'REDIRECT_URL' ] );
            
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
    public function resolvePath( $path ) {
        $resolvedPath;
        
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