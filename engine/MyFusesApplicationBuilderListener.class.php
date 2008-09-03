<?php
interface MyFusesApplicationBuilderListener {
    
    public function applicationBuildPerformed( Application $application, 
        &$data );
    
}