<?php
/**
 * MyFuses i18n Context class - MyFusesI18nContext.class.php
 *
 * Utility that controls i18n state.
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
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:MyFusesI18nContext.class.php 521 2008-06-25 12:43:32Z piraz $
 */

/**
 * MyFuses i18n Context class - MyFusesI18nContext.class.php
 *
 * Utility that controls i18n state.
 *
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:521 $
 * @since      Revision 514
 */
class MyFusesI18nContext {
    
    private static $context = array();
    
    private static $time;
    
    private static $store = false;
    
    public static function getExpression( $name, $params=null ) {
    	
    	if( is_null( MyFuses::getInstance()->getRequest() ) ) {
    		$encoding = 'UTF-8';
    	}
    	else {
    		$encoding = MyFuses::getInstance()->getRequest()->
    		  getApplication()->getCharacterEncoding();	
    	}
    	
    	$locale = "";
    	
    	$replace = null;
    	
    	if( is_null( $params ) ) {
    	    $locale = MyFuses::getApplication()->getLocale();
    	}
    	else {
    	    if( isset( $params[ 'locale' ] ) ) { 
    	        $locale = $params[ 'locale' ];
    	    }
    	    else {
    	        $locale = MyFuses::getApplication()->getLocale();
    	    }
    	    
    	    if( isset( $params[ 'replace' ] ) ) {
    	        $replace = $params[ 'replace' ];
    	    }
    	}    	
    	
    	if( !isset( self::$context[ $locale ][ $name ] ) ) {
    		return "Expression " .  $name . " not found.";
    	}
    	
    	$expression = html_entity_decode( self::$context[ $locale ][ $name ], 
    	   ENT_NOQUOTES, $encoding ); 
        
    	if( !is_null( $replace ) ) {
    	    $expression = vsprintf( $expression, $replace );
    	}
    	
    	return $expression; 
    }
    
    public static function setExpression( $locale, $name, $value ) {
        self::$context[ $locale ][ $name ] = $value;
    }
    
    public static function getContext(){
        return self::$context;
    }
    
    public static function setContext( $context ) {
        self::$context = $context;
    }
    
    public static function getTime() {
        return self::$time;
    }
    
    public static function setTime( $time ) {
        self::$time = $time;
    }
    
    public static function mustStore() {
        return self::$store;
    }
    
    public static function setStore( $store ) {
        self::$store = $store;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

function get_myfuses_expresion( $name, $params=null ) {
	return MyFusesI18nContext::getExpression( $name, $params );
}

function myexp( $name, $params=null ) {
	return MyFusesI18nContext::getExpression( $name, $params );
}