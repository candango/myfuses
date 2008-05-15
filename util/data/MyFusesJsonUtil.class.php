<?php
/**
 * MyFusesJsonUtil  - MyFusesJsonUtil.class.php
 * 
 * This is utility class has some methdos that handles json to php trasnforming 
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
 * @package    util.json
 * @author     Flávio Garcia <piraz at users.sf.net>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

/**
 * MyFusesJsonUtil  - MyFusesJsonUtil.class.php
 * 
 * This is utility class has some methdos that handles json to php trasnforming 
 * and encoding.
 * 
 * PHP version 5
 *
 * @category   util
 * @package    util.json
 * @author     Flávio Garcia <piraz at users.sf.net>
 * @author     Daniel Luz <mernen at users.sf.net>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class MyFusesJsonUtil {
    
    /**
     * This mehtod calls jsonPrepare and encondes the data to json
     *
     * @param mixed $data
     * @return string
     */
    public static function toJson( $data ) {
        return json_encode( self::jsonPrepare( $data ) );
    }
    
    /**
     * Recursively converts objects into arrays and strings into UTF-8
     * representations, as required by PHP's json_encode
     * 
     * @param mixed $var An array, an object, a string, a number, a boolean,
     *                   or null, to be converted
     * @return mixed     A converted value in the same format as the given
     */
    private static function jsonPrepare( $data ) {
        if ( is_object( $data ) ) {
            if (!$data instanceof stdClass ){
                $data = MyFusesDataUtil::objectToArray( $data );
            }
        }
        
        if ( is_array( $data ) ) { // objects will also fall here
            foreach ( $data as &$item ) {
                $item = self::jsonPrepare( $item );
            }
            return $data;
        }

        if ( is_string( $data ) ) {
            return utf8_encode( $data );
        }

        // for all other cases (number, boolean, null), no change

        return $data;
    }
    
}