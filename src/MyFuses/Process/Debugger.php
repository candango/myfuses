<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Process;

/**
 * Debugger  - Debugger.php
 * 
 * This is the MyFuses Debugger class. All myfuses events will be registered in
 * Debugger and printed at the request end.
 *
 * @category   controller
 * @package    myfuses.process
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      5addb5347d0470a105f5865fc57d7df3dc981f7a
 */
class Debugger
{
    const MYFUSES_CATEGORY = "MyFuses";

    const RUNTIME_CATEGORY = "Runtime";

    private $initTime = 0;

    /**
     * Events registered in Debugger
     *
     * @var array
     */
    private $events;

    public function __construct()
    {
        $this->initTime = $this->getMicrotime();
    }

    /**
     * Register one event in debugger
     *
     * @param MyFusesDebugEvent $event
     */
    public function registerEvent(DebugEvent $event)
    {
        $event->setTime($this->getMicrotime() - $this->initTime);
        $event->setCount(count( $this->events) + 1);
        $this->events[] = $event;
    }

    /**
     * Return the Events registered
     *
     * @return arrat
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set the Events
     *
     * @param array $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    public function __toString()
    {
        $strOut = "<br />";
		$strOut .= "<div style=\"clear:both;padding-top:10px;border-bottom:1px Solid #CCC;font-family:verdana;font-size:16px;font-weight:bold\">MyFuses debugging:</div>";
        $strOut .= "<br />";
        $strOut .= "<table cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" style=\"border:1px Solid #CCC;font-family:verdana;font-size:11pt;\">";
        $strOut .= "<tr style=\"background:#EAEAEA\">";
        $strOut .= "<td style=\"border-bottom:1px Solid #CCC;font-family:verdana;font-size:11pt;\"><strong>Time</strong></td>";
        $strOut .= "<td style=\"border-bottom:1px Solid #CCC;font-family:verdana;font-size:11pt;\"><strong>Category</strong></td>";
        $strOut .= "<td style=\"border-bottom:1px Solid #CCC;font-family:verdana;font-size:11pt;\"><strong>Message</strong></td>";
        $strOut .= "<td style=\"border-bottom:1px Solid #CCC;font-family:verdana;font-size:11pt;\"><strong>Count</strong></td>";
        $strOut .= "</tr>";
        foreach ($this->events as $event) {
            $strOut .= $event;
        }
        $strOut .= "</table>";
        return $strOut;
    }
    
	public function getMicrotime()
    {
	    list($usec, $sec) = explode(" ", microtime());
	    
	    return (((float) $usec + (float) $sec)) * 1000;
	}

}

/**
 * DebugEvent
 * 
 * This class represents one event that will be registered into FuseDebuger.
 *
 * @category   controller
 * @package    myfuses.process
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      5addb5347d0470a105f5865fc57d7df3dc981f7a
 */
class DebugEvent
{
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

    public function __construct($category, $message)
    {
        $this->category = $category;
        $this->message = $message;
    }

    /**
     * Return the Debug Event time
     *
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the Debug Event time
     *
     * @param integer $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Return the Debug Event category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the Debug Event category
     *
     * @param String $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Return the Debug Event message
     *
     * @return String
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the Debug Event message
     *
     * @param String $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Return the Debug Event count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set the Debug Event count
     *
     * @return integer
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    public function __toString()
    {
        return "<tr style=\"background:#F9F9F9\"><td valign=\"top\" style=\"font-size:10pt;border-bottom:1px Solid #CCC;font-family:verdana;\">" . round($this->getTime())  .
            "ms</td><td valign=\"top\" style=\"font-size:10pt;border-bottom:1px Solid #CCC;font-family:verdana;\">" . $this->getCategory() . 
            "</td><td valign=\"top\" style=\"font-size:10pt;border-bottom:1px Solid #CCC;font-family:verdana;\">" . $this->getMessage() . 
            "</td><td valign=\"top\" align=\"center\" style=\"font-size:10pt;border-bottom:1px Solid #CCC;font-family:verdana;\">" . $this->getCount() . "</td></tr>";
    }
}
