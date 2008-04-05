<?php
require_once "myfuses/process/FuseQueue.class.php";

class FuseRequest {
    
    /**
     * Enter description here...
     *
     * @var Application
     */
    private $application;
    
    private $circuitName;
    
    private $actionName;
    
    private $validFuseactionName;
    
    private $fuseQueue;
    
    public function __construct( $applicationName = null ) {
        
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
            
            if( substr( $path, -1 ) == "/" ) {
                $path = substr( $path, 0, strlen( $path ) - 1 );
            }
            
            $path = substr( $path, 1, strlen( $path ) );
            
            $pathX = explode( "/", $path );
            
            if( $pathX[ 0 ] == $fuseactionVariable ) {
                if( count( $pathX ) != 1 ) {
                    $this->validFuseactionName = implode( ".", array_slice( 
                        $pathX, 1, count( $pathX ) ) );    
                }                
            }
            else {
                if( count( $pathX ) != 1 ) {
                    $this->validFuseactionName = implode( ".", array_slice( 
                        $pathX, 0, count( $pathX ) ) );    
                }
            }
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
     * Enter description here...
     *
     * @return Application
     */
    function getApplication() {
        return $this->application;
    }
    
    /**
     * Enter description here...
     *
     * @return FuseAction
     */
    public function getAction() {
        $action = null;
        
        $action = $this->application->getCircuit( $this->circuitName )
            ->getAction( $this->actionName );    
        
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
    
}