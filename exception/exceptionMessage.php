<h2>An Error of type <font color="red">"<?php echo MyFusesException::
getCurrentInstance()->getType();?>"</font> has occured</h2>
<h3><?php echo( MyFusesException::getCurrentInstance()->getMessage() ); ?></h3>
<p>
<b>Detail:</b><br>
<?php echo( MyFusesException::getCurrentInstance()->getDetail() ); ?>
</p>
<table width="100%">
    <tr bgcolor="#005B9E">
        <th colspan="3" align="center">
            <font color="#FFFFFF">Call Stack</font>
        </th>
    </tr>
    <tr>
        <th bgcolor="#3E88C1"><font color="#FFFFFF">#</font></th>
        <th bgcolor="#3E88C1"><font color="#FFFFFF">Function</font></th>
        <th bgcolor="#3E88C1"><font color="#FFFFFF">Location</font></th>
    </tr>
    
<?php $stackTrace = MyFusesException::getCurrentInstance()->getTrace();?>
<?php for( $i = 1; $i  < count( $stackTrace ); $i ++ ){?>
    
    <tr bgcolor="#<?php echo ( ( $i % 2 ) == 0  ? "bee2fb" : "d7edfd" );?>">
        <td><b><?php echo count( $stackTrace ) - ( $i + 1 );?></b></td>
        <td>
            <?php echo MyFusesException::getTraceFunctionString( $stackTrace[ $i ] ) ; ?>
        </td>
        <td>
            <?php echo MyFusesException::getTraceLocationString( $stackTrace[ $i ] );?>
        </td>
    </tr>
<?php }?>

</table>