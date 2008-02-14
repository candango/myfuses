<?php
/**
 * DoVerb  - DoVerb.class.php
 * 
 * This verb delegates execution to another verb. In fact DoVerb will resolve
 * the verb called until find some "real" verb. 
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
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2007.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

/**
 * DoVerb  - DoVerb.class.php
 * 
 * This verb delegates execution to another verb. In fact DoVerb will resolve
 * the verb called until find some "real" verb. 
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 125
 */
require_once "myfuses/core/verbs/ParameterizedVerb.class.php";

class DoVerb extends ParameterizedVerb {
    
    /**
     * Circuit name to be executed
     *
     * @var string
     */
    private $circuitToBeExecutedName;
    
    private $appName;
    
    /**
     * Action name to be executed
     *
     * @var string
     */
    private $actionToBeExecutedName;
    
    public function setActionToBeExecuted( $actionName ) {
        
        
        $actionNameX = explode( '.', $actionName );
        
        $app = $this->getAction()->getCircuit()->getApplication()->getName();
        
        if( count( $actionNameX ) > 2 ) {
            list( $app, $circuit, $action ) = $actionNameX;
            $actionNameX = array( $circuit, $action );
        }
        
        $this->appName = $app;
        
        if ( count( $actionNameX ) < 2 ) {
            try {
                $this->circuitToBeExecutedName = 
                    $this->getAction()->getCircuit()->getName();    
            }
	        catch ( MyFusesCircuitException $mfe ) {
	            $mfe->breakProcess();
	        }
            $this->actionToBeExecutedName = $actionName;
        }
        else {
			$this->circuitToBeExecutedName = $actionNameX[ 0 ];
			$this->actionToBeExecutedName = $actionNameX[ 1 ];
        }
        
    }
    
    public function getData() {
        $data = parent::getData();
        $app = $this->getAction()->getCircuit()->getApplication()->getName();
        $data[ "attributes" ][ "action" ] = ( $this->appName != $app ? 
            $this->appName . "." : "" ) .  $this->circuitToBeExecutedName . 
            "." . $this->actionToBeExecutedName;
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        $this->setActionToBeExecuted( $data[ "attributes" ][ "action" ] );
    }
    
	/**
     * Return the parsed code
     *
     * @return string
     */
    public function getRealParsedCode( $commented, $identLevel ) {
        try {
            $action =  MyFuses::getInstance()->getApplication( 
                $this->appName )->getCircuit( 
	            $this->circuitToBeExecutedName )->
	            getAction( $this->actionToBeExecutedName );
        }
        catch ( MyFusesCircuitException $mfce ) {
            $mfce->breakProcess();
        }
        catch ( MyFusesFuseActionException $mffae ) {
            $mffae->breakProcess();
        }
        
        $strOut .= str_repeat( "\t", $identLevel );
        
        $action->setCalledByDo( true );
        
        $strOut .= $action->getParsedCode( $commented, $identLevel + 1 );
        
        $action->setCalledByDo( false );
        
        return $strOut;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */