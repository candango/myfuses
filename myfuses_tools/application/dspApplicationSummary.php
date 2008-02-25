<h3>Application Summary</h3>

<h4>Circuits</h4>

<table border="1">
  <tr>
    <th>Name</th>
    <th>Path</th>
    <th>File</th>
    <th>Access</th>
  </tr>
<?foreach( $application->getCircits() as $circuit ){?>
    <?if( $circuit->getName() != 'MYFUSES_GLOBAL_CIRCUIT' ) {?>
    <tr>
        <td><?=$circuit->getName()?></td>
        <td><?=$circuit->getPath()?></td>
        <td><?=$circuit->getFile()?></td>
        <td><?=$circuit->getAccess()?></td>
    </tr>
    <?}?>
<?}?>
</table>