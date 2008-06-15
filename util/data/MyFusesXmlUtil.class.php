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
    
    /**
     * Transforms one php structure to xml.
     * Encloses the data xml representation by a given root tag.
     *
     * @param mixed $data
     * @param string $root
     * @return string
     */
    public static function toXml( $data, $root = "myfuses_xml" ) {
        $strXml = "<" . $root . ">\n";
        $strXml .= self::doXmlTransformation( $data );
        $strXml .= "</" . $root . ">";
        return $strXml;
    }
    
    /**
     * Transforms any php structure to xml string
     *
     * @param mixed $data
     * @param integer $level
     * @param string $tagName
     * @return string
     */
    private static function doXmlTransformation( $data, $level=1, 
        $tagName = "" ) {
        $strXml = "";
        
        if( is_object( $data ) ) {
            $strXml .= self::getObjectXml( $data, $level );
        }
        elseif( is_array( $data ) ) {
            if( $tagName === "" ) {
                $tagName = "array";
            }
            $strXml .= str_repeat( "\t", $level ) . "<" . $tagName . ">\n";
            foreach( $data as $items ) {
                $strXml .= self::doXmlTransformation( $items, $level+1 );    
            }
            $strXml .= str_repeat( "\t", $level ) . "</" . $tagName . ">\n";
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
                        $tagName . "/>\n";    
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
     * Return xml representation form
     *
     * @param Object $object
     * @param integer $level
     * @return string
     */
    private static function getObjectXml( $object, $level ) {
        
        $tagName = get_class( $object );
            
        $refClass = new ReflectionClass( $object );
        
        $strXml = str_repeat( "\t", $level ) . 
                            "<" . $tagName . ">\n";
        
        foreach( $refClass->getMethods() as $method )  {
            if( $method->isPublic() ) {
                if( substr( $method->getName(), 0, 3 ) == "get" || 
                    substr( $method->getName(), 0, 2 ) == "is" ) {
                    $methodName =& $method->getName();
                    $subInit = substr( $methodName, 0 , 4 );
                    $subFinal = substr( $methodName, 4 );
                    $subInit = str_replace( array( "get", "is" ), "", $subInit );
                    
                    $property = strtolower( $subInit ) . $subFinal;
                    
                    $value = $object->$methodName();
                    
                    $strXml .= 
                        self::doXmlTransformation( $value, $level + 1, $property );
                }
            }
        }
        
        $strXml .= str_repeat( "\t", $level ) . 
                            "</" . $tagName . ">\n";
        
        //var_dump( $strXml );die();
        
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
        
        return self::fromXmlElement( $document->children() );
        
    }
    
    public static function fromXmlUrl( $url ) {
        return self::fromXml( file_get_contents( $url ), true );
    }
    
    private static function fromXmlElement( SimpleXMLElement $element ) {
        
        $struct = null;
        
        if( count( $element->children() ) ) {
            
            $structName = $element->getName();
            if( class_exists( $structName, true ) ) {
                $struct = new $structName();
                
                $refClass = new ReflectionClass( $struct );
                
                foreach( $element->children() as $key => $item ) {
                    
                    $phpValue = self::fromXmlElement( $item );
                    
                    try {
                        if( $property = $refClass->getProperty( $key ) ) {
                            if( $property->isPublic() ) {
                                $struct->$key = $phpValue;    
                            }
                        }
                        
                        $methodName = "set" . strtoupper( substr( $key, 0, 1 ) ) . 
                            substr( $key, 1, strlen( $key ) );
                            
                        if( $method = $refClass->getMethod( $methodName ) ) {
                            if( $method->isPublic() ) {
                                $struct->$methodName( $phpValue );    
                            }
                        }
                    }
                    catch( ReflectionException $re ) {
                        switch( $re->getCode() ) {
                            // ignoring non existent properties and methods
                            case 0;
                            case 1;
                                break;
                            default:
                                throw $re;
                        }
                    }
                }    
                
            } else {
                $struct = array();
                foreach( $element as $item ) {
                    $struct[] = self::fromXmlElement( $item );    
                }
            }
        }
        else {
            $struct = "" . $element;    
        }
        return $struct;
    }
    
}