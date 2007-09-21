<?php
/**
 * MyFuses File Handler class - MyFusesFileHandler.class.php
 *
 * Utility to handle usual file operations.
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * The Original Code is Candango Fusebox Implementation part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2005 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   file
 * @package    myfuses.util
 * @author     Flávio Gonçalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2005 - 2007 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    CVS: $Id: FuseboxException.class.php,v 1.1 2006/02/03 11:17:37 piraz Exp $
 * @link       *
 * @see        *
 * @since      File available since Release 0.0.1
 * @deprecated *
 */
 
/**
 * MyFuses File Handler class - MyFusesFileHandler.class.php
 *
 * Utility to handle usual file operations.
 *
 * @category   file
 * @package    myfuses.util
 * @author     Flávio Gonçalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2005 - 2007 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    CVS: $Id: FuseboxException.class.php,v 1.1 2006/02/03 11:17:37 piraz Exp $
 * @link       *
 * @see        *
 * @since      File available since Release 0.0.1
 * @deprecated *
 */
class MyFusesFileHandler {
    
    /**
     * Returns a array of finded files in a given file list or single file 
     * string.
     * 
     * @param mixed $fileList List of files or file string
     * @return array Finded files list
     */
    public static function findFile( $fileList ) {
    	
        $findedFileList = array();
        
        if( is_array( $fileList ) ) {
        	foreach( $fileList as $file ) {
        		if( is_file( $file ) ) {
        			$findedFileList[] = $file;
        		}
        	}
        }
        else{
        	if( is_file( $fileList ) ) {
                $findedFileList[] = $fileList;
            }
        }
        
        return $findedFileList;
        
    }
    
    /**
     * Returns if the path informed is absolute
     * 
     * @param string path
     * @return boolean
     */
    public static function isAbsolutePath( $path ) {
    	// pattern that search any [DIRECTORY_SEPARATOR] or  
        // [any letter]:[\ or /]
        $pattern = "[^\\" . DIRECTORY_SEPARATOR . 
            "|^\w\\:[\\\\|\\/]]";
        if( preg_match( $pattern , $path  ) ) {
    		return true;
    	}
        return false;
    }
    
    // TODO finish writeFile
    public static function writeFile( $fileName, $string ) {
    	$fp = fopen( $fileName,"w" );
		        
        if ( !flock($fp,LOCK_EX) ) {
            die("Could not get exclusive lock to Parsed File file");
        }
        
        if ( !fwrite($fp, $string) ) {
            var_dump( "deu pau 2!!!" );
        }
        flock($fp,LOCK_UN);
        fclose($fp);
        chmod( $fileName, 0777 );
    }
    
    // TODO finish readFile
    public static function readFile( $fileName ) {
        
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */