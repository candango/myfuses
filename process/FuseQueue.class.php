<?php
require_once "myfuses/process/FuseRequest.class.php";

class FuseQueue {

    private $preFuseActionQueue = array();
    
    private $processQueue = array();
    
    private $postFuseactionQueue = array();
    
    private $preProcessQueue = array();
    
    private $postProcessQueue = array();
    
    private $request;
    
    public function __construct( FuseRequest &$request ) {
        $this->request = &$request;

        $this->buildPreProcessQueue();
        
        $action = $this->request->getAction()->getCircuit()->getPreFuseAction();
        
        if( !is_null( $action ) ) {
            $this->preFuseActionQueue = $action->getVerbs();
        }
        
        $this->buildProcessQueue();
        
        $action = $this->request->getAction()->getCircuit()->getPostFuseAction();
        
        if( !is_null( $action ) ) {
            $this->postFuseactionQueue = $action->getVerbs();
        }
        
        $this->buildPostProcessQueue();
        
    }
    
    private function buildProcessQueue() {
        $this->processQueue = &$this->request->getAction()->getVerbs();
    }
    
    private function buildPreProcessQueue() {
        $action = $this->request->getApplication()->
            getCircuit( "MYFUSES_GLOBAL_CIRCUIT" )->
            getAction( "PreProcessFuseAction" );
        $this->preProcessQueue = $action->getVerbs();
    }
    
    private function buildPostProcessQueue() {
        $action = $this->request->getApplication()->
            getCircuit( "MYFUSES_GLOBAL_CIRCUIT" )->
            getAction( "PostProcessFuseAction" );
        $this->postProcessQueue = $action->getVerbs();
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