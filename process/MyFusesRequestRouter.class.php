<?php
require_once MYFUSES_ROOT_PATH . "process/MyFusesAbstractRequestRouter.class.php";
require_once MYFUSES_ROOT_PATH . "process/MyFusesBasicRequestRouter.class.php";

interface MyFusesRequestRouter {
    
    public function grab( MyFusesRequest $request );
    
    public function resolve( MyFusesRequest $request );
    
    public function release( MyFusesRequest $request );
}