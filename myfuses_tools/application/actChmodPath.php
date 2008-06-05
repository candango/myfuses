<?php

    if( file_exists( $_GET[ 'file' ] ) ) {
        if ( chmod( $_GET[ 'file' ] , 0770) ){
        $_SESSION[ 'file_message' ] = "File " . $_GET[ 'file' ] . 
            " chmoded sucessfully." ;
        }else{
        	$_SESSION[ 'file_message' ] = "Error on chmod operation" ;
        }
    }
    else {
        $_SESSION[ 'file_message' ] = "The file " . $_GET[ 'file' ] . 
            " doesn't exists." ;    
    }
    

