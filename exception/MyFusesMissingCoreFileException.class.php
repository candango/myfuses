<?php
class MyFusesMissingCoreFileException extends MyFusesException {
    
    public function __construct( $file ) {
    	
        $fileX = explode( DIRECTORY_SEPARATOR, $file );
        
        $msg = "Missing core file \"" . 
                $fileX[ count( $fileX ) - 1 ] . "\" .";
        
        $detail = "The core file was not found in \"" . $file . "\".<br> You " .
                "cannot move this file to another place or rename unless " .
                "you kown what you are doing.<br>";
        
        parent::__construct( $msg, $detail, 
            MyFusesException::MISSING_CORE_FILE );
        
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */