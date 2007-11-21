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
 * @package    myfuses.util.file
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */
 
/**
 * MyFuses File Handler class - MyFusesFileHandler.class.php
 *
 * Utility to handle usual file operations.
 *
 * @category   file
 * @package    myfuses.util.file
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 125
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
    
    /**
     * Sanitize any path to avoid process breaks
     * 
     * @param string $path
     * @return string
     */
    public static function sanitizePath( $path ) {
        if( substr( $path, -1 ) != DIRECTORY_SEPARATOR ) {
            $path .= DIRECTORY_SEPARATOR;
        }
        return $path;
    }
    
    
    
    /**
     * Write a string in a given file
     * 
     * @param string $file The file name
     * @param string $string The string to be writed
     */
    public static function writeFile( $file, $string ) {
    	$fp = fopen( $file,"w" );
		        
        if ( !flock($fp,LOCK_EX) ) {
            throw new MyFusesFileOperationException( $file, 
                MyFusesFileOperationException::LOCK_EX_FILE );
        }
        
        if ( !fwrite($fp, $string) ) {
            throw new MyFusesFileOperationException( $file, 
                MyFusesFileOperationException::WRITE_FILE );
        }
        flock($fp,LOCK_UN);
        fclose($fp);
        chmod( $file, 0777 );
    }
    
    /**
     * Reads the content of given file
     * 
     * @param string $file The file name
     * @return string The file content
     */
    public static function readFile( $file ) {
        if ( @!$fp = fopen( $file ,"r" ) ) {
            throw new MyFusesFileOperationException( $file, 
                MyFusesFileOperationException::OPEN_FILE );
        }
        
        if ( !flock( $fp, LOCK_SH ) ) {
            throw new MyFusesFileOperationException( $file, 
                MyFusesFileOperationException::LOCK_FILE );
        }
        
        $fileCode = fread( $fp, filesize( $file ) );
        
        flock($fp,LOCK_UN);
        fclose($fp);
        
        return $fileCode;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */