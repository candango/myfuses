<?php
interface MyFusesApplicationLoaderListener {
    
    public function applicationLoadPerformed( MyfusesLoader $loader, &$data );
    
}