<h2>Application Summary</h2>

<h3>Circuits</h3>

<table border="1">
  <tr>
    <th>Name</th>
    <th>Path</th>
    <th>File</th>
    <th>Access</th>
  </tr>
<?if( count( $application->getCircits() ) ) {?>  
    <?foreach( $application->getCircits() as $circuit ) {?>
        <?if( $circuit->getName() != 'MYFUSES_GLOBAL_CIRCUIT' ) {?>
        <tr>
            <td><?=$circuit->getName()?></td>
            <td><?=$circuit->getPath()?></td>
            <td><?=$circuit->getFile()?></td>
            <td><?=$circuit->getAccessName()?></td>
        </tr>
        <?}?>
    <?}?>
<?}else {?>
<tr>
    <td colspan="4">No Circuit Founded</td>
</tr>
<?}?>
</table>

<h3>Classes</h3>

<table border="1">
  <tr>
    <th>Name</th>
    <th>Path</th>
  </tr>
<?if( count( $application->getClasses() ) ) {?>  
    <?foreach( $application->getClasses() as $class ) {?>
    <tr>
        <td><?=$class->getName()?></td>
        <td><?=$class->getPath()?></td>
    </tr>
    <?}?>
<?}else {?>
<tr>
    <td colspan="4">No Class Founded</td>
</tr>
<?}?>
</table>

<h3>Parameters</h3>

<b>Fuseaction Variable</b>: <?=$application->getFuseactionVariable()?><br/>
<b>Default Fusesaction</b>: <?=$application->getDefaultFuseaction()?><br/>
<b>Mode</b>: <?=$application->getMode()?><br/>
<b>Parse With Enabled</b>: <?=$application->isParsedWithComments() ? "yes" : "no"?><br/>
<b>Character Encoding</b>: <?=$application->getCharacterEncoding()?><br/>
<b>Debug Enabled</b>: <?=$application->isDebugAllowed() ? "yes" : "no"?><br/>
<b>MyFuses Tools Enabled</b>: <?=$application->isToolsAllowed() ? "yes" : "no"?><br/>