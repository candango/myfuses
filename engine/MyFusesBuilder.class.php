<?php
/**
 * Interface that defines one My Fuses Loader
 * 
 * 
 */
interface MyFusesBuilder {
    
    public function buildApplication();
    
    /**
     * Add one application build listener
     *
     * @param MyFusesApplicationBuilderListener $listener
     */
    public function addApplicationBuilderListener( 
        MyFusesApplicationBuilderListener $listener );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */