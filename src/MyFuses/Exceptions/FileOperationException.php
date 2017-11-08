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
 * MyFuses File Operation Exception - MyFusesFileOperationException.php
 *
 * This class handles all file operation exceptions.
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f06b361b3bc6909ebf21f108d42b79a17cfb3924
 */
class FileOperationException extends Exception
{
    const OPEN_FILE = 1;

    const LOCK_FILE = 2;

    const LOCK_EX_FILE = 3;

    const WRITE_FILE = 4;

    const INCLUDE_FILE = 5;

    public function __construct($file, $operation)
    {
        $operationMessageMap = array(
            self::OPEN_FILE => array(
                'msg' => 'Could not open the file __FILE__.',
                'detail' => '"Could not open the file <b>"__FILE__"</b> ' .
                    'founded in directory <b>__DIR__</b>. Check permission.'
            ),
            self::LOCK_FILE => array(
                'msg' => 'Could not lock the file __FILE__.',
                'detail' => '"Could not lock the file <b>"__FILE__"</b> ' .
                    'founded in directory <b>__DIR__</b>. Check permission.'
            ),
            self::LOCK_EX_FILE => array(
                'msg' => 'Could not get exclusive lock to __FILE__ file.',
                'detail' => '"Could not get exclusive lock to' . 
                ' <b>"__FILE__"</b> file' . ' founded in directory ' . 
                '<b>__DIR__</b>. Check permission.'
            ),
            self::WRITE_FILE => array(
                'msg' => 'Could not write in file __FILE__.',
                'detail' => '"Could not wirite in file <b>"__FILE__"</b> ' .
                    ' founded in directory <b>__DIR__</b>. Check permission.'
            ),
            self::INCLUDE_FILE => array(
                'msg' => 'Could not include the file __FILE__.',
                'detail' => '"Could not include the file <b>"__FILE__"</b> ' .
                    ' founded in directory <b>__DIR__</b>. Check if file exists.'
            )
        );

        $fileX = explode(DIRECTORY_SEPARATOR, $file);

        $dir = str_replace($fileX[count($fileX) - 1], '', $file);

        $search = array('__FILE__', '__DIR__');

        $replace = array($fileX[count($fileX) - 1], $dir);

        $msg =  str_replace( $search, $replace, 
            $operationMessageMap[$operation]['msg']) ;

        $detail = str_replace($search, $replace,
            $operationMessageMap[$operation]['detail']) ;

        parent::__construct($msg, $detail,
            self::MISSING_CORE_FILE);
    }
}
