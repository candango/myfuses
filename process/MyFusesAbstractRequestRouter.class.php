<?php
abstract class MyFusesAbstractRequestRouter implements MyFusesRequestRouter {
    
    /**
     * (non-PHPdoc)
     * @see process/MyFusesRequestRouter#grab()
     */
    public function grab( MyFusesRequest $request ) {
        
        $fuseactionVariable = $request->getFuseactionVariable();
        
        if ( isset( $_GET[ $fuseactionVariable ] ) && 
            $_GET[ $fuseactionVariable ] != '' ) {
            $request->setCurrentFuseaction( $_GET[ $fuseactionVariable ] );
        }
            
        if ( isset( $_POST[ $fuseactionVariable ] ) && 
            $_POST[ $fuseactionVariable ] != '' ) {
            $request->setCurrentFuseaction( $_POST[ $fuseactionVariable ] );
        }
        
        if( $request->getCurrentFuseaction() === null ) {
            $request->setCurrentFuseaction( $request->getDefaultFuseaction() );
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see process/MyFusesRequestRouter#resolve()
     */
    public function resolve( MyFusesRequest $request ) {
        
    }

    /**
     * (non-PHPdoc)
     * @see process/MyFusesRequestRouter#release()
     */
    public function release( MyFusesRequest $request ) {
        
    }
}