<?php
require_once "myfuses/process/FuseRequest.class.php";

class FuseQueue {

    private $preFuseActionQueue = array();
    
    private $processQueue = array();
    
    private $postFuseactionQueue = array();
    
    private $preProcessQueue = array();
    
    private $postProcessQueue = array();
    
    /**
     * Queue request
     * 
     * @var FuseRequest
     */
    private $request;
    
    public function __construct( FuseRequest &$request ) {
        $this->request = &$request;

        $this->buildPreProcessQueue();
        
        $this->buildProcessQueue();
        
        $this->buildPostProcessQueue();
        
    }
    
    private function buildProcessQueue() {
        $action = &$this->request->getAction();
        
	    if( $action->getCircuit()->getAccess() == 
            Circuit::INTERNAL_ACCESS ) {
            $params = array( "circuitName" => $action->getCircuit()
                ->getName(), "application" => $action->getCircuit()
                ->getApplication() );
            throw new MyFusesCircuitException( $params, 
                MyFusesCircuitException::
                USER_TRYING_ACCESS_INTERNAL_CIRCUIT );
	    }    
        
        $this->processQueue[] = &$this->request->getAction();
    }
    
    private function buildPreProcessQueue() {
        // FIXME Plugin::PRE_PROCESS_PHASE deve ser mudado para MyFusesLifecycle::PRE_PROCESS_PHASE
        $plugins = $this->request->getApplication()->getPlugins( 
            Plugin::PRE_PROCESS_PHASE );
        
        $action = $this->request->getApplication()->
            getCircuit( "MYFUSES_GLOBAL_CIRCUIT" )->
            getAction( "PreProcessFuseAction" );
        
        $actions = $plugins;
        
        $actions[] = $action;
        
        $this->preProcessQueue = $actions;
    }
    
    private function buildPostProcessQueue() {
        // FIXME Plugin::POST_PROCESS_PHASE deve ser mudado para MyFusesLifecycle::POST_PROCESS_PHASE
        $plugins = $this->request->getApplication()->getPlugins( 
            Plugin::POST_PROCESS_PHASE );
        
        $action = $this->request->getApplication()->
            getCircuit( "MYFUSES_GLOBAL_CIRCUIT" )->
            getAction( "PostProcessFuseAction" );
                    
        $actions[] = $action;
        
        $actions = array_merge( $actions, $plugins );

        $this->postProcessQueue = $actions;
    }
    
    public function getProcessQueue() {
        return $this->processQueue;
    }
    
    public function getPreProcessQueue() {
        return $this->preProcessQueue;
    }
    
    public function getPostProcessQueue() {
        return $this->postProcessQueue;
    }
    
    public function getPreFuseActionQueue() {
        return $this->preFuseActionQueue;
    }
    
    public function getPostFuseActionQueue() {
        return $this->postFuseactionQueue;
    }

}