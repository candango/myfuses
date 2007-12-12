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
        $queue = array();
        
        // gettin all circuit prefuseactions possible
        $circuit = $this->request->getAction()->getCircuit();
        
        while( !is_null( $circuit ) ) {
            if( !is_null( $circuit->getPreFuseAction() ) ) {
                array_unshift( $queue, $circuit->getPreFuseAction() );
            }
            $circuit = $circuit->getParent();
        }
        
        // getting THE pre fuseaction
        array_unshift( $queue, $this->request->getApplication()->
            getCircuit( "MYFUSES_GLOBAL_CIRCUIT" )->
            getAction( "PreProcessFuseAction" ) );
        
        // getting the pre process plugins
        $plugins = $this->request->getApplication()->getPlugins( 
            Plugin::PRE_PROCESS_PHASE );
        
        $queue = array_merge( $plugins, $queue );    
        
        $this->preProcessQueue = $queue;
    }
    
    private function buildPostProcessQueue() {
        $queue = array();
        
        // getting THE post fuseaction
        $queue[] =$this->request->getApplication()->
            getCircuit( "MYFUSES_GLOBAL_CIRCUIT" )->
            getAction( "PostProcessFuseAction" );
        
        // gettin all circuit prefuseactions possible
        $circuit = $this->request->getAction()->getCircuit();
            
        while( !is_null( $circuit ) ) {
            if( !is_null( $circuit->getPostFuseAction() ) ) {
                $queue[] = $circuit->getPostFuseAction();
            }
            $circuit = $circuit->getParent();
        }    
            
        // FIXME Plugin::POST_PROCESS_PHASE deve ser mudado para MyFusesLifecycle::POST_PROCESS_PHASE
        $plugins = $this->request->getApplication()->getPlugins( 
            Plugin::POST_PROCESS_PHASE );
        
        $circuit = $this->request->getAction()->getCircuit();
        
        $queue = array_merge( $queue, $plugins );

        $this->postProcessQueue = $queue;
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