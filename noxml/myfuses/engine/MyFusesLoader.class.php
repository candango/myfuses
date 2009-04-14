<?php
require_once MYFUSES_ROOT_PATH . "engine/MyFusesAbstractLoader.class.php";
require_once MYFUSES_ROOT_PATH . "engine/loaders/MyFusesXmlLoader.class.php";


interface MyFusesLoader {
	
	/**
	 * 
	 * @param $application
	 * @param $name
	 * @param $value
	 */
    public function setApplicationParameter( Application $application, 
        $name, $value );
        
    public function addApplicationReference( Application $application, 
       CircuitReference $reference );
}