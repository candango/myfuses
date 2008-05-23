<?php
/**
 * MyFusesXmlUtil  - MyFusesXmlUtil.class.php
 * 
 * This is utility class has some methdos that handles xml to php trasnforming 
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
 * @category   util
 * @package    util.xml
 * @author     Flávio Garcia <piraz at users.sf.net>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:MyFusesXmlUtil.class.php 446 2008-05-15 14:45:48Z piraz $
 */

/**
 * MyFusesXmlUtil  - MyFusesXmlUtil.class.php
 * 
 * This is utility class has some methdos that handles xml to php trasnforming 
 * and encoding.
 * 
 * PHP version 5
 *
 * @category   util
 * @package    util.xml
 * @author     Flávio Garcia <piraz at users.sf.net>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:446 $
 * @since      Revision 17
 */
class MyFusesXmlUtil {
    
    public static function toXml( $data, $root = "myfuses_xml" ) {
        $strXml = "<" . $root . ">\n";
        $strXml .= self::doXmlTransformation( self::xmlPrepare( $data ) );
        $strXml .= "</" . $root . ">";
        return $strXml;
    }
    
    private static function doXmlTransformation( $data, $level=1, 
        $tagName = "" ) {
        $strXml = "";
        if( is_array( $data ) ) {
            foreach( $data as $_key => $_value ) {
                if( substr( $_key, -4 ) == "_<s>" ) {
                    $tagName = str_replace( substr( $_key, -4 ), "", $_key);
                    if( count( $_value ) ) {
                        foreach( $_value as $_vkey => $_vvalue ) {
                            $strXml .= str_repeat( "\t", $level ) . 
                                "<" . $tagName . ">\n";
                            $strXml .= self::doXmlTransformation( 
                                $_vvalue, $level + 1 );
                            $strXml .= str_repeat( "\t", $level ) . 
                                "</" . $tagName . ">\n";
                        }    
                    }
                    else {
                        $strXml .= str_repeat( "\t", $level ) . "<" . 
                            $tagName . "/>\n";
                    }
                }
                else {
                    if( count( $_value ) > 1 ) {
                        $strXml .= str_repeat( "\t", $level ) . "<" . $_key . ">\n";    
                    }
                    $strXml .= self::doXmlTransformation( $_value, 
                        $level+1, $_key );
                    if( count( $_value ) > 1 ) {
                        $strXml .= str_repeat( "\t", $level ) . "</" . $_key . ">\n";    
                    }
                }
            }
        }
        else {
            if( is_bool( $data ) ) {
                $strXml .= str_repeat( "\t", $level ) . "<" . $tagName . ">";
                $strXml .= $data ? "true" : "false";
                $strXml .= "</" . $tagName . ">\n";
            }
            else {
                if( is_null( $data ) ) {
                    $strXml .= str_repeat( "\t", $level ) . "<" . 
                        $tagName . "\>\n";    
                }
                else {
                    $strXml .= str_repeat( "\t", $level ) . "<" . 
                        $tagName . ">";
                    $strXml .= $data;
                    $strXml .= "</" . $tagName . ">\n";
                }
            }
            
        }
        return $strXml;
    }
    
    
    /**
     * Tra
     *
     * @param mixed $data
     * @return array
     */
    private static function xmlPrepare( $data ) {
        
        $monster = $data;
        
        if ( is_object( $data ) ) {
            $monster = 
                array( get_class( $data ) => 
                    MyFusesDataUtil::objectToArray( $data, true ) );
        }
        
        if ( is_array( $data ) ) { // objects will also fall here
            foreach ( $data as $key => $item ) {
                if( is_object( $item ) ) {
                    $monster[ get_class( $item ) . "_<s>" ][] = 
                        self::xmlPrepare( $item );
                    unset( $monster[$key] );    
                }
                else {
                    $monster[ $key ] = self::xmlPrepare( $item );    
                }
            }
            
            return $monster;
        }
        
        if ( is_string( $data ) ) {
            return $data;
        }
        
        return $monster;
    }
    
    
    /**
     * Return php structures from xml string
     *
     * @param string $xml
     * @return mixed
     */
    public static function fromXml( $xml ) {
        
        $document = new SimpleXMLElement( $xml );
        
        return self::fromXmlElement( $document );
        
    }
    
    private static function fromXmlElement( SimpleXMLElement $element, $struct = null ) {
        if( count( $element ) ) {
            foreach( $element as $key => $value ) {
                return self::getStruct( $key, $value );
            }
        }
        else {
            return "" . $element;
        }
        
        return $struct;
    }
    
    
    private static function getStruct( $key, $value ) {
        $struct = null;
        
        
        if( count( $value ) ) {
            if( class_exists( $key, true ) ) {
                $struct = new $key();
            } else {
                $struct = new stdClass(); 
            }
            foreach( $value as $key1 => $item ) {
                        
                if( !( $struct instanceof stdClass ) ) {
                    $refClass = new ReflectionClass( $struct );
                    if( $refClass->hasProperty( $key1 ) ) {
                        if( $refClass->getProperty( $key1 )->isPublic() ) {
                            $struct->$key1 = self::fromXmlElement( $item );    
                        }
                    }
                    
                    $method = "set" . strtoupper( substr( $key1, 0, 1 ) ) . 
                        substr( $key1, 1, count( $key1 ) + 1 );
                        
                    if( $refClass->hasMethod( $method ) ) {
                        if( $refClass->getMethod( $method )->isPublic() ) {
                            $struct->$method( self::fromXmlElement( $item ) );    
                        }
                    }
                }
                else {
                    $struct->$key1 = self::fromXmlElement( $item, $struct );
                }
            }
        }
        else {
            $struct = "" . $value;
        }
        
        return $struct;
        
    }
}