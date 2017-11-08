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

namespace Candango\MyFuses\Exceptions;

/**
 * MyFuses Missing Core File Exception - MyFusesMissingCoreFileException.php
 *
 * Exception thrown when a missing core file error happens.
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f06b361b3bc6909ebf21f108d42b79a17cfb3924
 */
class MissingCoreFileException extends Exception
{
    public function __construct( $file )
    {
        $fileX = explode(DIRECTORY_SEPARATOR, $file);

        $msg = "Missing core file \"" . $fileX[count($fileX) - 1] . "\" .";

        $detail = "The core file was not found in \"" . $file . "\".<br> You " .
                "cannot move this file to another place or rename unless " .
                "you kown what you are doing.<br>";

        parent::__construct($msg, $detail, self::MISSING_CORE_FILE);
    }
}
