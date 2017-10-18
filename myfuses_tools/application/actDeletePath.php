<?php
function delpath($path)
{
    if (is_dir($path)) {
        $it = new RecursiveDirectoryIterator($path);
        foreach (new RecursiveIteratorIterator($it, 1) as $child) {
            $pName = "" . $child;
            if ($child->isDir() && !$child->isLink()) {
                delpath($pName);
            } else {
                unlink($pName);
            }
        }
        rmdir($path);
    } else {
        unlink($path);
    }  
}

$cachedPath = $application->getController()->getParsedPath() .
    $application->getName();

// checking if the file delete was in the cached path
if (strpos($_GET['file'], $cachedPath) !== false) {
    if (file_exists($_GET['file'])) {
        delpath($_GET[ 'file' ]);
        $_SESSION['file_message'] = "File " . $_GET[ 'file' ] .
            " deleted sussefully.";
    } else {
        $_SESSION['file_message'] = "The file " . $_GET['file'] .
            " doesn't exists.";
    }
} else {
    $_SESSION['file_message'] = "You cannot delete the file " . $_GET['file'] .
        " because this file is not in application cache dir.";
}
