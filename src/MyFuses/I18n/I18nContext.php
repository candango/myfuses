<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\I18n;

use Candango\MyFuses\Controller;

/**
 * MyFuses I18n Context class - MyFusesI18nContext.php
 *
 * Utility that controls I18n state.
 *
 * @category   I18n
 * @package    myfuses.util.I18n
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      c36c8ff941c440d0c01ea0341e03345dd9727b10
 */
class I18NContext
{
    private static $context = array();

    private static $time;

    private static $store = false;

    public static function getExpression($name, $params=null)
    {
    	if (is_null(Controller::getInstance()->getRequest())) {
    		$encoding = "UTF-8";
    	} else {
    		$encoding = Controller::getInstance()->getRequest()->
    		  getApplication()->getCharacterEncoding();	
    	}

    	$locale = "";

    	$replace = null;

    	if (is_null($params)) {
    	    $locale = Controller::getApplication()->getLocale();
    	} else {
    	    if (isset($params['locale'])) {
    	        $locale = $params['locale'];
    	    } else {
    	        $locale = Controller::getApplication()->getLocale();
    	    }

    	    if (isset($params['replace'])) {
    	        $replace = $params['replace'];
    	    }
    	}

    	if (!isset(self::$context[$locale][$name])) {
    		return "Expression " .  $name . " not found.";
    	}

    	$expression = html_entity_decode(self::$context[$locale][$name],
            ENT_NOQUOTES, $encoding );

    	if (!is_null($replace)) {
    	    $expression = vsprintf($expression, $replace);
    	}

    	return $expression; 
    }

    public static function setExpression($locale, $name, $value)
    {
        self::$context[$locale][$name] = $value;
    }

    public static function getContext()
    {
        return self::$context;
    }

    public static function setContext($context)
    {
        self::$context = $context;
    }

    public static function getTime()
    {
        return self::$time;
    }

    public static function setTime( $time )
    {
        self::$time = $time;
    }

    public static function mustStore()
    {
        return self::$store;
    }

    public static function setStore($store)
    {
        self::$store = $store;
    }
}

function myfuses_expresion($name, $params=null)
{
	return I18NContext::getExpression($name, $params);
}

function myexp($name, $params=null)
{
	return I18NContext::getExpression($name, $params);
}
