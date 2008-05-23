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

require_once "myfuses/core/verbs/ParameterizedVerb.class.php";
require_once "myfuses/core/verbs/InvokeVerb.class.php";

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
    
    public static function doAction( CircuitAction $action ) {        
        $parsedPath = $action->getCircuit()->
            getApplication()->getParsedPath();

        $actionFile = $parsedPath . $action->getCircuit()->getName() . 
            DIRECTORY_SEPARATOR . $action->getName() . ".action.do.php";    

        if( !is_file( $actionFile ) || $action->getCircuit()->isModified() ) {
                
            $strOut = $action->getParsedCode( $action->getCircuit()->
                getApplication()->isParsedWithComments(), 0 );
    
            $path = $action->getCircuit()->getApplication()->getParsedPath() .
                $action->getCircuit()->getName() . DIRECTORY_SEPARATOR;
                
            if( !file_exists( $path ) ) {
                MyFusesFileHandler::createPath( $path );
                chmod( $path, 0777 );
            }   
            
            MyFusesFileHandler::writeFile( $actionFile, "<?php\n" . 
                        MyFusesContext::sanitizeHashedString( $strOut ) );
                        
            MyFuses::getInstance()->getDebugger()->registerEvent( 
                new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                    "Fuseaction " . $action->getCircuit()->
                    getApplication()->getName() . "." . 
                    $action->getCircuit()->getName() . "." .
                    $action->getName() . " Compiled" ) );
        }
        MyFusesContext::includeFile( $actionFile );           
    }
    
	/**
     * Return the parsed code
     *
     * @return string
     */
    public function getRealParsedCode( $commented, $identLevel ) {
        InvokeVerb::clearClassCall();        
        $completeActionName = $this->appName . "." . 
            $this->circuitToBeExecutedName . "." . 
            $this->actionToBeExecutedName; 
            
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
        
        //$this->generateActionFile( $action, $commented );
        
        $strOut = str_repeat( "\t", $identLevel );
        
        $action->setCalledByDo( true );
        
        $strOut .=  $this->getAction()->getCircuit()->getApplication()->
            getControllerClass() . "::doAction( \"" . 
            $completeActionName . "\" );";

        $strOut .= self::getContextRestoreString();
            
        $action->setCalledByDo( false );
        
        return $strOut;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */