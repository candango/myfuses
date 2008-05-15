<?php
/**
 * MyFusesDataUtil  - MyFusesDataUtil.class.php
 * 
 * This is utility class has some methdos that handles basic php transforming 
 * and encoding.
 * 
 * PHP version 5
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
 * This product includes software developed by the Fusebox Corporation 
 * (http://www.fusebox.org/).
 * 
 * The Original Code is Fuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   data
 * @package    util.data
 * @author     Flávio Garcia <piraz at users.sf.net>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

/**
 * MyFusesDataUtil  - MyFusesDataUtil.class.php
 * 
 * This is utility class has some methdos that handles basic php transforming 
 * and encoding.
 * 
 * PHP version 5
 *
 * @category   data
 * @package    util.data
 * @author     Flávio Garcia <piraz at users.sf.net>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class MyFusesDataUtil {
    
    public static function objectToArray( $item, $assoc=false ) {
        $itemArray = array();
        
        $refClass = new ReflectionClass( $item );
        
        foreach( $refClass->getProperties() as $property ) {
            
            if( $property->isPublic() ) {
                $itemArray[ $property->name ] = $item->{$property->name};
                
                if( is_object( $itemArray[ $property->name ] ) ) {
                    $itemArray[ $property->name ] = self::objectToArray( 
                        $itemArray[ $property->name ], true );        
                }   
            }
        }
        
        foreach( $refClass->getMethods() as $method ) {
            if( $method->isPublic() ) {
                if( substr( $method->getName(), 0, 3 ) == "get" || 
                    substr( $method->getName(), 0, 2 ) == "is" ) {

                    $subInit = substr($method->getName(),0,4);
                    $subFinal = substr($method->getName(),4);
                    $subInit = str_replace( array( "get", "is" ), "", $subInit );
                    
                    $property = $subInit.$subFinal;
                    
                    $property = strtolower( substr( $property, 0, 1 ) ) . 
                        substr( $property, 1, strlen( $property ) );
                    
                    $itemArray[ $property ] = $item->{$method->getName()}();
                    
                    if( is_object( $itemArray[ $property ] ) ) {
                        $itemArray[ $property ] = self::objectToArray( 
                            $itemArray[ $property ], true );        
                    }
                }
            }

        }
        
        return $itemArray;
    }
    
}