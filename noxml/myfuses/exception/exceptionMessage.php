<h1><font color="red">"<?php echo $this->getType();?>"</font></h1>
<h2><?php echo( $this->getMessage() ); ?></h2>
<p>
<b>Description:</b>
<?php echo( $this->getDescription() ); ?>
<?php echo( $this->getDetail() ); ?>
</p>
<table width="100%">
    <tr bgcolor="#005B9E">
        <th colspan="3" align="center">
            <font size="3" color="#FFFFFF">Call Stack</font>
        </th>
    </tr>
    <tr>
        <th bgcolor="#3E88C1"><font size="2" color="#FFFFFF">#</font></th>
        <th bgcolor="#3E88C1"><font size="2" color="#FFFFFF">Function</font></th>
        <th bgcolor="#3E88C1"><font size="2" color="#FFFFFF">Location</font></th>
    </tr>
    
<?php $stackTrace = $this->getTrace();?>
<?php for( $i = 0; $i  < count( $stackTrace ); $i ++ ){?>
    <tr bgcolor="#<?php echo ( ( $i % 2 ) == 0  ? "bee2fb" : "d7edfd" );?>">
        <td><b><font size="2"><?php echo count( $stackTrace ) - ( $i + 1 );?></font></b></td>
        <td><font size="2"><?php echo MyFusesException::getTraceFunctionString( $stackTrace[ $i ] ) ; ?></font></td>
        <td><font size="2"><?php echo MyFusesException::getTraceLocationString( $stackTrace[ $i ] );?></font></td>
    </tr>
<?php }?>

</table>