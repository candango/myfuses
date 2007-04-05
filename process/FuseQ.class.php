<?php
class FuseQ {

    private $processQueue;
    
    private $request;
    
    public function __construct( FuseRequest &$request ) {
        $this->request = &$request;
        
        $this->processQueue = &$request->getAction()->getVerbs();
        
        
    }
    
}