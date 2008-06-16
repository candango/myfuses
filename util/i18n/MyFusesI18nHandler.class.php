<?php
/**
 * MyFuses i18n Handler class - MyFusesI18nHandler.class.php
 *
 * Utility to handle usual i18n operations.
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
 * @version    SVN: $Id$
 */

/**
 * MyFuses i18n Handler class - MyFusesI18nHandler.class.php
 *
 * Utility to handle usual i18n operations.
 *
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 125
 */
class MyFusesI18nHandler {
    
    public static function setLocale() {
        
        $locale = MyFuses::getApplication()->getLocale();
        
        putenv( "LANG=" . $locale );
        
        setlocale(LC_ALL, $locale);
    }
    
    public static function checkFiles() {
        
    }
    
    public static function loadFiles() {
        
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */