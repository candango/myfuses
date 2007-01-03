<?php
abstract class AbstractMyFusesLoader implements MyFusesLoader {
    
    public function loadApplication( Application $application ) {
    
        
        
        $this->doLoad( $application );
        
        //TODO complete application load
        
        $application->setLoaded( true );
    }
    
    public static function getLoader( $whichLoader ) {
        
        $loaderArray = array(
            MyFusesLoader::XML_LOADER => "XMLMyFusesLoader"
        );
        
        return new $loaderArray[ $whichLoader ]();
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */