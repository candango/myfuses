<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */


/**
 * toString modifier
 *
 * Type:     modifier<br>
 * Name:     tostring<br>
 * Purpose:  Converts to string, useful for simpleXML object
 * @author   Rafael Dohms <rafael at rafaeldohms dot com dot br>
 * @param mixed Original value 
 * @return string Converted value
 */
function smarty_modifier_tostring($mixed)
{
	return (string) $mixed;
}
