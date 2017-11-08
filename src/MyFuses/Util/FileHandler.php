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

/**
 * MyFuses File Handler class - MyFusesFileHandler.php
 *
 * Utility to handle usual file operations.
 *
 * @category   file
 * @package    myfuses.util.file
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      63b5db316ba7c748afa1a8e60b2c6bb319492abb
 */
class MyFusesFileHandler
{
    /**
     * Returns a array of finded files in a given file list or single file 
     * string.
     * 
     * @param mixed $fileList List of files or file string
     * @return array Finded files list
     */
    public static function findFile($fileList)
    {
        $foundFileList = array();

        if (is_array($fileList)) {
        	foreach ($fileList as $file) {
        		if(is_file($file)) {
        			$foundFileList[] = $file;
        		}
        	}
        } else {
        	if(is_file($fileList)) {
                $foundFileList[] = $fileList;
            }
        }

        return $foundFileList;
    }

    /**
     * Returns if the path informed is absolute
     * 
     * @param string path
     * @return boolean
     */
    public static function isAbsolutePath($path)
    {
    	// pattern that search any [DIRECTORY_SEPARATOR] or  
        // [any letter]:[\ or /]
        $pattern = "[^\\" . DIRECTORY_SEPARATOR . 
            "|^\w\\:[\\\\|\\/]]";
        if (preg_match( $pattern , $path )) {
    		return true;
    	}
        return false;
    }

    /**
     * Sanitize any path to avoid process breaks
     * 
     * @param string $path
     * @return string
     */
    public static function sanitizePath($path)
    {
    	if (substr($path, -1) != DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }
        return $path;
    }

    /**
     * Unquerify a uri, removing all parameters
     *
     * @param string $uri
     * @return string
     */
    public static function unquerifyUri($uri)
    {
    	return current(explode('&',$uri));
    }

    public static function createPath($path, $permission = 0755)
    {
        if (substr($path, -1) == DIRECTORY_SEPARATOR) {
            $path = substr($path, 0, strlen($path) - 1);
        }

        $children = array();

        while (!file_exists($path)) {
            $pathX = explode(DIRECTORY_SEPARATOR, $path);
            array_unshift($children, array_pop($pathX));
            $path = implode(DIRECTORY_SEPARATOR, $pathX);
        }

        foreach ($children as $child) {
            $path .= DIRECTORY_SEPARATOR . $child; 
            mkdir($path);
            chmod($path, $permission);
        }
    }

    /**
     * Write a string in a given file
     * 
     * @param string $file The file name
     * @param string $string The string to be writed
     * @throws FileOperationException
     */
    public static function writeFile($file, $string)
    {
    	$fp = fopen($file,"w");

        if (!flock($fp,LOCK_EX)) {
            throw new FileOperationException($file,
                FileOperationException::LOCK_EX_FILE);
        }

        if (!fwrite($fp, $string)) {
            throw new FileOperationException($file,
                FileOperationException::WRITE_FILE);
        }

        flock($fp,LOCK_UN);
        fclose($fp);
        chmod( $file, 0755);
    }

    /**
     * Reads the content of given file
     * 
     * @param string $file The file name
     * @return string The file content
     * @throws FileOperationException
     */
    public static function readFile($file)
    {
        if (@!$fp = fopen($file ,"r")) {
            throw new FileOperationException($file,
                FileOperationException::OPEN_FILE);
        }

        if (!flock( $fp, LOCK_SH)) {
            throw new FileOperationException($file,
                FileOperationException::LOCK_FILE);
        }

        $fileCode = fread($fp, filesize($file));

        flock($fp,LOCK_UN);
        fclose($fp);

        return $fileCode;
    }

    /**
     * Validate one file and check if has the given extension
     * 
     * @param $file
     * @param $extension
     * @return boolean
     */
    public static function hasExtension($file, $extension)
    {
        // TODO check if the file name have the php extension 
        $fileX = explode(".", $file);

        if (count($fileX) < 1) {
            return false;
        }

        if($fileX[count($fileX) - 1] != $extension) {
            return false;
        }

        return true;
    }

}
