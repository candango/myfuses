<?php
/**
 * Interface that defines one My Fuses Loader
 * 
 * 
 */
interface MyFusesLoader {
    
    const XML_LOADER = 0;
    
    /**
     * Do fisical load
     *
     * @param Application $application
     */
    public function doLoad( Application $application );
    
    /**
     * Load the aplication
     *
     * @param Application $application
     */
    public function loadApplication( Application $application );
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */