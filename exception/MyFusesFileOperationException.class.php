<?php
/**
 * MyFuses File Operation Exception class - MyFusesFileOperationException.class.php
 *
 * This class handles all file operation exceptions.
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
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */
 
/**
 * MyFuses File Operation Exception class - MyFusesFileOperationException.class.php
 *
 * This class handles all file operation exceptions.
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class MyFusesFileOperationException extends MyFusesException {
    
    
    const OPEN_FILE = 1;
    
    const LOCK_FILE = 2;
    
    const LOCK_EX_FILE = 3;
    
    const WRITE_FILE = 4;
    
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
            self::LOCK_EX_FILE => array(
                'msg' => 'Could not get exclusive lock to __FILE__ file.',
                'detail' => '"Could not get exclusive lock to' . 
                ' <b>"__FILE__"</b> file' . ' founded in directory ' . 
                '<b>__DIR__</b>. Check permission.'
            ),
            self::WRITE_FILE => array(
                'msg' => 'Could not write in file __FILE__.',
                'detail' => '"Could not wirite in file <b>"__FILE__"</b> ' .
                    ' founded in directory <b>__DIR__</b>. Check permission.'
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