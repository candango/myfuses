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
    
    /**
     * Enter description here...
     *
     * @return FuseAction
     */
    public function getAction() {
        $action = null;
        try {
            $action = $this->application->getCircuit( 
                $this->circuitName )->getAction( $this->actionName );    
        }
        catch ( MyFusesCircuitException $mfe ) {
            $mfe->breakProcess();
        }
        
        
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
    
}