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
    
    public static function setVariable( $variable, $value ) {
        
        global $$variable;
        
        $$variable = $value;
        
    }
    
    public static function getVariable( $variable ) {
        
        global $$variable;
        
        return $$variable;
        
    }
    
    public static function includeFile( $file ) {
        var_dump( $file );die();
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */