<?php
function deltree( $f ) {
  if ( is_dir( $f ) ) {
    foreach( glob($f.'/*') as $sf) {
      if (is_dir( $sf ) && !is_link($sf)) {
        deltree( $sf );
      } else {
        unlink( $sf );
      } 
    } 
  }
  rmdir( $f );
}

$cachedPath = $application->getController()->getParsedPath() . 
    $application->getName();

// checkin if the file delete was in cached path
if( strpos( $_GET[ 'file' ], $cachedPath ) !== false ) {
    if( file_exists( $_GET[ 'file' ] ) ) {
        if( is_dir( $_GET[ 'file' ] ) ) {
            deltree( MyFusesFileHandler::sanitizePath( $_GET[ 'file' ] ) );
            //var_dump( MyFusesFileHandler::sanitizePath( $_GET[ 'file' ] ) );die();
        }
        else {
            unlink( $_GET[ 'file' ] );    
        }
        $_SESSION[ 'file_message' ] = "File " . $_GET[ 'file' ] . 
            " deleted sussefully." ;
    }
    else {
        $_SESSION[ 'file_message' ] = "The file " . $_GET[ 'file' ] . 
            " doesn't exists." ;    
    }
    
}
else {
    $_SESSION[ 'file_message' ] = "You cannot delete the file " . 
        $_GET[ 'file' ] . 
        " because this file is not in application cache dir." ;
}