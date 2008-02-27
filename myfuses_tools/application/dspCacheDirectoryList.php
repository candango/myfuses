<h2>MyFuses Cache Listing</h2>
<?php
$cachedPath = $application->getController()->getParsedPath() . 
    $application->getName();
?>
<h3>Current Path: <?php echo $cachedPath?></h3>

<?php
$it = new RecursiveDirectoryIterator( $cachedPath );

// RecursiveIteratorIterator accepts the following modes:
//     LEAVES_ONLY = 0  (default)
//     SELF_FIRST  = 1
//     CHILD_FIRST = 2
foreach (new RecursiveIteratorIterator($it, 1) as $path) {

    if ($path->isDir()) {

        echo "Path: $path<br>";

    }
    else {
        echo "File: $path<br>";
    }

}
