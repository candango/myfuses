<?php
/**
 * FuseDebugger  - FuseDebugger.class.php
 * 
 * This is the MyFuses Debugger class. All myfuses events will be registered in
 * Debugger and printed at the request end.
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
 * @category   debugger
 * @package    myfuses.process
 * @author     Flávio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: MyFuses.class.php 143 2007-08-03 21:35:41Z piraz $
 */

/**
 * FuseDebugger  - FuseDebugger.class.php
 * 
 * This is the MyFuses Debugger class. All myfuses events will be registered in
 * Debugger and printed at the request end.
 * 
 * PHP version 5
 *
 * @category   debugger
 * @package    myfuses.process
 * @author     Flávio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @since      Revision 17
 */
class FuseDebugger {
    
    /**
     * Events registered in Debugger
     *
     * @var array
     */
    private $events;
    
    /**
     * Register one event in debugger
     *
     * @param FuseDebugEvent $event
     */
    public function registerEvent( FuseDebugEvent $event ) {
        $this->events[] = $event;
    }
    
    /**
     * Return the Events registered
     *
     * @return arrat
     */
    public function getEvents() {
        return $this->events;
    }
    
    /**
     * Set the Events
     *
     * @param array $events
     */
    public function setEvents( $events ) {
        $this->events = $events;
    }
    
    public function __toString() {
        $strOut = "<br />";
        $strOut .= "<h1>MyFuses debugging:</h1>";
        $strOut .= "<br />";
        $strOut .= "<table><th>";
        $strOut .= "<td>Time</td>";
        $strOut .= "<td>Category</td>";
        foreach( $this->events as $event ) {
            $strOut .= $event;
        }
    }
    
}

/**
 * FuseDebugEvent
 * 
 * This class represents one event that will be registered into FuseDebuger.
 * 
 * PHP version 5
 *
 * @category   debugger
 * @package    myfuses.process
 * @author     Flávio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @since      Revision 17
 */
class FuseDebugEvent {

    /**
     * Time when this debug Debug Event occours
     *
     * @var integer
     */
    private $time = 0;

    /**
     * Debug Event category
     *
     * @var string
     */
    private $category;

    /**
     * Debug Event message
     *
     * @var string
     */
    private $message = "";

    /**
     * How much times this event runs 
     *
     * @var integer
     */
    private $count = 0;
    
    /**
     * Return the Debug Event time
     *
     * @return integer
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * Set the Debug Event time
     *
     * @param integer $time
     */
    public function setTime( $time ) {
        $this->time = $time;
    }

    /**
     * Return the Debug Event category
     *
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set the Debug Event category
     *
     * @param String $category
     */
    public function setCategory( $category ) {
        $this->category = $category;
    }
    
    /**
     * Return the Debug Event message
     *
     * @return String
     */
    public function getMessage(){
        return $this->message;
    }
    
    /**
     * Set the Debug Event message
     *
     * @param String $message
     */
    public function setMessage( $message ){
        $this->message = $message;
    }

    /**
     * Return the Debug Event count
     *
     * @return integer
     */
    public function getCount(){
        return $this->count;
    }

    /**
     * Set the Debug Event count
     *
     * @return integer
     */
    public function setCount( $count ){
        $this->count = $count;
    }
    
    public function __toString() {
        return "<tr><td>" . $this->getTime() . 
            "</td><td>" . $this->getCategory() . 
            "</td><td>" . $this->getCategory() . 
            "</td><td>" . $this->getCount() . "</td></tr>";
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */