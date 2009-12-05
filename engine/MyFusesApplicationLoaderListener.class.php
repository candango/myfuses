<?php
interface MyFusesApplicationLoaderListener {
    
    public function loadInitialized( BasicApplication $application );
    
    public function loadPerformed( MyfusesLoader $loader, &$data );
    
}