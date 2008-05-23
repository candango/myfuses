<?php
/**
 * MyFuses Code Handler class - MyFusesCodeHandler.class.php
 *
 * Utility to handle usual code operations.
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
 * @category   code
 * @package    myfuses.util.code
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: MyFusesFileHandler.class.php 183 2007-11-21 20:11:59Z piraz $
 */
 
/**
 * MyFuses Code Handler class - MyFusesCodeHandler.class.php
 *
 * Utility to handle usual code operations.
 *
 * @category   code
 * @package    myfuses.util.code
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 183 $
 * @since      Revision 125
 */
class MyFusesCodeHandler {
    
    public static $context = array();
    
    public static function setVariable( $name, $value ) {
        global $$name;
        
        $$name = $value;
        if( !in_array( $name, self::$context ) ) {
            self::$context[] = $name;    
        }
    }
    
    public static function getVariable( $name ) {
        global $$name;
        
        return in_array( $name, self::$context ) ? $$name : null;
    }
    
    public static function unsetVariable( $name ) {
        global $$name;
        
        self::$context = array_diff( self::$context, array( $name ) );
        
        unset( $$name );
    }
    
    public static function includeFile( $__MFCH_FILE_MONSTER_OF_LAKE ) {
        
        foreach( self::$context as $variable ) {
            global $$variable;
        }
        
        if( file_exists( $__MFCH_FILE_MONSTER_OF_LAKE ) ) {
             include $__MFCH_FILE_MONSTER_OF_LAKE;
        }
        // getting defined variables in this context
        foreach( get_defined_vars() as $key => $value ) {
            if( !in_array( $key, self::$context ) ) {
                if( $key != "__MFCH_FILE_MONSTER_OF_LAKE" ) {
                    self::setVariable( $key, $value );    
                }
            }
        }
        // trow some exception when file doesnt exists!!!
    }
    
    public static function setParameter( $name, $value ) {
        
        if( !in_array( $name, self::$context ) ) {
            self::setVariable( $name, $value );    
        }
        
    }
    
    public static function restoreParameter( $name ) {
        self::unsetVariable( $name );
    }
    
    public static function getContext(){
        return self::$context;
    }
    
    /**
     * Clean all hashed strings ex:#<string>#
     *
     * @param string $hstring
     * @return string
     */
    public static function sanitizeHashedString( $hstring ) {
        // resolving #valriable#'s 
        $hstring =  preg_replace( 
            "@([#])([\$|\d|\w|\-\>|\:|\(|\)|\'|\\\"|\[|\]|\s]*)([#])@", 
            "\" .$2. \"" , $hstring );
        
        $hstring = str_replace( "\"\" .", " ",$hstring );
        $hstring = str_replace( ". \"\"", "",$hstring );
        $hstring = str_replace( " \"#", " ",$hstring );
        $hstring = str_replace( "#\" ", " ",$hstring );
        return  $hstring;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */