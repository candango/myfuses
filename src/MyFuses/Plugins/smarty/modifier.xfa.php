<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

use Candango\MyFuses\Controller;


/**
 * MyFuses xfa modifier
 *
 * Type:     modifier<br>
 * Name:     xfa<br>
 * Purpose:  Obtains XFA object from the application based on the string
 * @author   Rafael Dohms <rafael at rafaeldohms dot com dot br>
 * @param string $xfa
 * @param boolean $initQuery
 * @param boolean $showFuseactionVariable
 * @return string
 *
 * @throws \Candango\MyFuses\Exceptions\ApplicationException
 */
function smarty_modifier_xfa(
    $xfa,
    $initQuery=false,
    $showFuseactionVariable=true
) {
    if(Controller::getApplication()->ignoreFuseactionVariable())
    {
        $showFuseactionVariable = false;
    }
    return Controller::getMySelfXfa($xfa, $initQuery, $showFuseactionVariable);
}
