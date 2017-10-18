<h2>Application Summary</h2>

<h3>Circuits</h3>

<table border="1">
  <tr>
    <th>Name</th>
    <th>Path</th>
    <th>File</th>
    <th>Access</th>
  </tr>  
<?php if (count($application->getCircuits())) {?>
    <?php foreach ($application->getCircuits() as $circuit) {?>
        <?php if ($circuit->getName() != 'MYFUSES_GLOBAL_CIRCUIT') {?>
        <tr>
            <td><?=$circuit->getName()?></td>
            <td><?=$circuit->getPath()?></td>
            <td><?=$circuit->getFile()?></td>
            <td><?=$circuit->getAccessName()?></td>
        </tr>
        <?php }?>
    <?php }?>
<?php } else {?>
<tr>
    <td colspan="4">No Circuit Founded</td>
</tr>
<?php }?>
</table>

<h3>Classes</h3>

<table border="1">
  <tr>
    <th>Name</th>
    <th>Path</th>
  </tr>
<?php if (count($application->getClasses())) {?>
    <?php foreach ($application->getClasses() as $class) {?>
    <tr>
        <td><?=$class->getName()?></td>
        <td><?=$class->getPath()?></td>
    </tr>
    <?php }?>
<?php }else {?>
<tr>
    <td colspan="4">No Class Founded</td>
</tr>
<?php }?>
</table>

<h3>Parameters</h3>

<b>Fuseaction Variable</b>: <?=$application->getFuseactionVariable()?><br/>
<b>Default Fusesaction</b>: <?=$application->getDefaultFuseaction()?><br/>
<b>Mode</b>: <?=$application->getMode()?><br/>
<b>Parse With Enabled</b>: <?=$application->isParsedWithComments() ? "yes" : "no"?><br/>
<b>Character Encoding</b>: <?=$application->getCharacterEncoding()?><br/>
<b>Debug Enabled</b>: <?=$application->isDebugAllowed() ? "yes" : "no"?><br/>
<b>MyFuses Tools Enabled</b>: <?=$application->isToolsAllowed() ? "yes" : "no"?><br/>
