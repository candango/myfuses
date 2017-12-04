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

namespace Candango\MyFuses\Process;

/**
 * MyFuses Context class - Context.php
 *
 * Controls the context of global variables and parameters that are set inside
 * circuits and lost the global reference. Here we make sure the variable will
 * be set to the global context.
 *
 * Context controls if the variable value, that commonly is a string, must be
 * transformed to php during the build process when the value is enclosed by #.
 *
 * @category   controller
 * @package    myfuses.util.context
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      7b802fcc293a23bc8ccfb6eedad643d3fbccf933
 */
class Context
{
    public static $context = array();

    /**
     * Set a variable by it's given value.
     *
     * If the append parameter is set to true, the variable value will appended
     * to the variable if it was previously defined.
     *
     * @param $name The variable name
     * @param $value The variable value
     * @param bool $append If the variable value should be appended or not
     */
    public static function setVariable($name, $value, $append = false)
    {
        global $$name;

        if ($append) {
            if (isset($$name)) {
                $$name .= $value;
            } else {
                $$name = $value;
            }
        } else {
            $$name = $value;
        }
        if (!in_array( $name, self::$context)) {
            self::$context[] = $name;
        }
    }

    public static function getVariable($name)
    {
        global $$name;

        return in_array($name, self::$context) ? $$name : null;
    }

    public static function unsetVariable($name)
    {
        global $$name;

        self::$context = array_diff(self::$context, array($name));

        unset( $$name );
    }

    public static function includeFile($__MFCH_FILE_MONSTER_OF_LAKE)
    {
        foreach (self::$context as $variable)
        {
            global $$variable;
        }

        if (file_exists($__MFCH_FILE_MONSTER_OF_LAKE))
        {
             include $__MFCH_FILE_MONSTER_OF_LAKE;
        }
        // getting defined variables in this context
        foreach (get_defined_vars() as $key => $value) {
            if (!in_array($key, self::$context)) {
                if ($key != "__MFCH_FILE_MONSTER_OF_LAKE") {
                    self::setVariable($key, $value);
                }
            }
        }
        // TODO: trow some exception when file doesnt exists!!!
    }

    public static function setParameter( $name, $value )
    {
        if (!in_array( $name, self::$context)) {
            self::setVariable($name, $value);
        }
    }

    public static function restoreParameter($name)
    {
        self::unsetVariable($name);
    }

    public static function getContext()
    {
        return self::$context;
    }

    /**
     * Clean all hashed strings ex:#<string>#
     *
     * @param string $hstring
     * @return string
     */
    public static function sanitizeHashedString($hstring)
    {
        // resolving #valriable#'s 
        $hstring =  preg_replace(
            "@([#])([\$|\d|\w|\-\>|\:|\(|\)|\'|\\\"|\[|\]|\s]*)([#])@", 
            "\" .$2. \"" , $hstring
        );

        $hstring = str_replace("\"\" .", " ",$hstring);
        $hstring = str_replace(". \"\"", "",$hstring);
        $hstring = str_replace(" \"#", " ",$hstring);
        $hstring = str_replace("#\" ", " ",$hstring);
        $hstring = str_replace("\"#", "",$hstring);
        $hstring = str_replace("#\"", "",$hstring);
        return  $hstring;
    }
}
