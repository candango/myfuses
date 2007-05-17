<?php
class MyFusesFileOperationException extends MyFusesException {
    
    
    const OPEN_FILE = 1;
    
    const LOCK_FILE = 2;
    
    const WRITE_FILE = 3;
    
    public function __construct( $file, $operation ) {
    	
        $operationMessageMap = array(
            self::OPEN_FILE => array(
                'msg' => 'Could not open the file __FILE__.',
                'detail' => '"Could not open the file <b>"__FILE__"</b> ' .
                    'founded in directory <b>__DIR__</b>. Check permission.'
            ),
            self::LOCK_FILE => array(
                'msg' => 'Could not lock the file __FILE__.',
                'detail' => '"Could not lock the file <b>"__FILE__"</b> ' .
                    'founded in directory <b>__DIR__</b>. Check permission.'
            ),
            self::WRITE_FILE => array(
                'msg' => 'Could not write in file __FILE__.',
                'detail' => '"Could not wirite in file <b>"__FILE__"</b> ' .
                    'founded in directory <b>__DIR__</b>. Check permission.'
            )
        );
        
        $fileX = explode( DIRECTORY_SEPARATOR, $file );
        
        $dir = str_replace( $fileX[ count( $fileX ) - 1 ], '', $file );
        
        $search = array( '__FILE__', '__DIR__' );
        
        $replace = array( $fileX[ count( $fileX ) - 1 ], $dir );
        
        $msg =  str_replace( $search, $replace, 
            $operationMessageMap[ $operation ][ 'msg' ] ) ; 
        
        $detail = str_replace( $search, $replace, 
            $operationMessageMap[ $operation ][ 'detail' ] ) ;
        
        parent::__construct( $msg, $detail, 
            MyFusesException::MISSING_CORE_FILE );
        
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */