<?php
/**
 * MyFuses Native i18n Handler class - MyFusesNativeI18nHandler.class.php
 *
 * Utility to handle usual i18n operations. This class is a native 
 * implementation dont't need any other lib to work.
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
 * MyFuses Native i18n Handler class - MyFusesNativeI18nHandler.class.php
 *
 * Utility to handle usual i18n operations. This class is a native 
 * implementation dont't need any other lib to work.
 *
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:521 $
 * @since      Revision 125
 */
class MyFusesNativeI18nHandler extends MyFusesI18nHandler {
    
    public function setLocale() {
            
    }
    
    public function storeFiles() {
        
        $path = MyFuses::getApplication()->getParsedPath();
        
        $path = MyFusesFileHandler::sanitizePath( $path . 'i18n' );
        
        if( !file_exists( $path ) ) {
            MyFusesFileHandler::createPath( $path );
        }
        
        $fileName = $path . "locale.data.php";
        
        $data = "<?php\nreturn unserialize( '";
                
        $data .= str_replace("'","\'", 
            serialize( MyFusesI18nContext::getContext() ) );
        
        $data .= "' );";
        
        MyFusesFileHandler::writeFile( $fileName, $data );
        
    }
        
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */