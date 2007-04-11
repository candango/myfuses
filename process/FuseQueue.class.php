<?php
class FuseQueue {

    private $preFuseActionQueue = array();
    
    private $processQueue = array();
    
    private $postFuseactionQueue = array();
    
    private $request;
    
    public function __construct( FuseRequest &$request ) {
        $this->request = &$request;
        
        $action = $this->request->getAction()->getCircuit()->getPreFuseAction();
        
        if( !is_null( $action ) ) {
            $this->preFuseActionQueue = $action->getVerbs();
        }
        
        $this->buildProcessQueue();
        
        $action = $this->request->getAction()->getCircuit()->getPostFuseAction();
        
        if( !is_null( $action ) ) {
            $this->postFuseactionQueue = $action->getVerbs();
        }

    }
    
    private function buildProcessQueue() {
        $this->processQueue = &$this->request->getAction()->getVerbs();
    }
    
    public function getProcessQueue() {
        return $this->processQueue;
    }
    
    public function getPreFuseActionQueue() {
        return $this->preFuseActionQueue;
    }
    
    public function getPostFuseActionQueue() {
        return $this->postFuseactionQueue;
    }

}