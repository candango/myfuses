<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Exception;


/**
 * MyFuses Exception - MyFusesException.php
 *
 * Base exception class used by the framework.
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Garcia <piraz at candango.org>
 * @since f06b361b3bc6909ebf21f108d42b79a17cfb3924
 */
abstract class MyFusesException extends Exception
{
    const CIRCUIT_XML_ERROR = 1;
    const ERROR_WRITING_PARSED_FILE = 2;
    const FAILED_ASSERTION = 3;
    const MYFUSES_XML_ERROR = 4;
    const INVALID_ACCESS_MODIFIER = 5;
    const MALFORMED_FUSEACTION = 6;
    const MISSING_APP_PATH = 7;
    const MISSING_CIRCUIT_XML = 8;
    const MISSING_CIRCUIT_PATH = 9;

    const MISSING_MYFUSES_XML = 11;
    const MISSING_FUSE = 12;
    const MISSING_PARSED_FILE = 13;
    const OVERLOADED_FUSEACTION = 14;
    const UNDEFINED_CIRCUIT = 15;
    const UNDEFINED_FUSEACTION = 16;

    const FILE_OPERATION = 101;
    const MISSING_CORE_FILE = 102;
    const NON_EXISTENT_APPLICATION = 103;
    const NON_EXISTENT_CIRCUIT = 104;
    const NON_EXISTENT_FUSEACTION = 105;
    const USER_TRYING_ACCESS_INTERNAL_CIRCUIT = 107;

    private static $currentInstance;

    private $detail;

    private $type;

    /**
     * 
     */
    function __construct($message, $detail, $code)
    {
        parent::__construct($message, $code);

        $this->type = get_class($this);

        $this->detail = $detail;

        self::setCurrentInstance($this);

        $stackTrace = $this->getTrace();

        $function = "<b>Function</b>: " .
                    self::getTraceFunctionString($stackTrace[0]);
        $location = "<b>Location</b>: " .
                    self::getTraceLocationString($stackTrace[0]);

        $this->detail .= "<br>$function<br>$location";
    }

    /**
     * 
     */
    function getType()
    {
        return $this->type;
    }

    /**
     * 
     */
    function getDetail()
    {
        return $this->detail;
    }

    /**
     * 
     */
    function breakProcess()
    {
        ob_clean();
        include dirname(__FILE__) . DIRECTORY_SEPARATOR .
            'exceptionMessage.php';
        $str = ob_get_contents();
        ob_clean();
        print $str;
        die();
    }

    /**
     * Sets the current instance
     *
     * @param MyFusesException instance
     */
    static function setCurrentInstance(MyFusesException $instance)
    {
        self::$currentInstance = $instance;
    }

    /**
     * Returns the current instance
     *
     * @return MyFusesException
     */
    static function getCurrentInstance()
    {
        return self::$currentInstance;
    }

    /**
     * Returnt the location where the exception was found
     *
     * @param array trace
     * @return string
     */
    static function getTraceLocationString($trace)
    {
        return "<b>File:</b> " . @$trace['file'] . " <b>Line:</b> " .
            @$trace['line'];
    }

    /**
     * Returns the trace function string
     *
     * @param array trace
     * @return string
     */
    static function getTraceFunctionString($trace)
    {
        $out = "";

        if (isset( $trace['class'])) {
            $out .= $trace['class'];
        }

        if (isset($trace['type'])) {
            $out .= $trace['type'];
        }

        $out .= $trace['function'] . "(" ;

        if (count($trace['args'])) {
            $out .= " ";
        }

        $traceX = array();
        if (count($trace['args'])) {
            foreach ($trace['args'] as $key => $value) {
                if (is_object($value)) {
                    @$traceX[$key] = "<b>" . get_class($value) .
                        "</b> => '" . get_class($value)  . "'";
                } else {
                    if (is_string($value)) {
                        $value = (strlen($value) < 40 ? $value :
                            substr($value, 0, 40) . "...");
                    }
                    $traceX[$key] = "<b>" . gettype($value) .
                        "</b> => '" . $value . "'"  ;
                }
            }
        }
        $out .= implode(", ", $traceX);

        if (count($trace['args'])) {
            $out .= " ";
        }

        $out .= ")" ;
        return $out;
    }
}

class MyFusesRuntimeExeption extends Exception
{

}

class MyFusesProcessException extends MyFusesRuntimeExeption
{

}

class MyFusesFuseactionException extends MyFusesRuntimeExeption
{

}
