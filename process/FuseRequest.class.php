<?php
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
    
    public function __construct( $applicationName = null ) {
        
        if( is_null( $applicationName ) ) {
            $this->application = & MyFuses::getInstance()->getApplication();    
        }
        else{
            $this->application = & MyFuses::getInstance()->getApplication( $applicationName );
        }
        
        $defaultFuseaction = $this->application->getDefaultFuseAction();
        
        $fuseactionVariable = $this->application->getFuseactionVariable();
        
        
        if ( isset( $_GET[ $fuseactionVariable ] ) ) {
            $this->validFuseactionName = $_GET[ $fuseactionVariable ];
        }
        
        if ( isset( $_POST[ $fuseactionVariable ] ) ) {
            $this->validFuseactionName = $_POST[ $fuseactionVariable ];
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
    
    function getCircuitName() {
        return $this->circuitName;
    }
    
    function getActionName() {
        return $this->actionName;
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
    
}