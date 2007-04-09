<?php
class FuseQueue {

    private $processQueue;
    
    private $request;
    
    public function __construct( FuseRequest &$request ) {
        $this->request = &$request;
        
        $this->buildProcessQueue();
        
    }
    
    private function buildProcessQueue() {
        $this->processQueue = &$this->request->getAction()->getVerbs();
    }
    
    public function getProcessQueue() {
        return $this->processQueue;
    }
    
}