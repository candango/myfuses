<?php
/**
 * Interface that defines one My Fuses Loader
 * 
 * 
 */
interface MyFusesBuilder {
    
    /**
     * Builds the application structure
     *
     * @param Application $application
     */
    public static function buildApplication( Application $application );
    
    /**
     * Add one application build listener
     *
     * @param MyFusesApplicationBuilderListener $listener
     */
    public function addApplicationBuilderListener( 
        MyFusesApplicationBuilderListener $listener );
    
    /**
     * Return builder application
     *
     * @return Application
     */    
    public function getApplication();    
    
    /**
     * Set builder application
     *
     * @param Application $application
     */
    public function setApplication( Application $application );
        
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */