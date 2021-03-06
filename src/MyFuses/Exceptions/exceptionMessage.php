<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

use Candango\MyFuses\Exceptions\MyFusesException;
?>

<h2>An Error of type <font color="red">"<?php echo MyFusesException::
getCurrentInstance()->getType();?>"</font> has occured</h2>
<h3><?php echo(MyFusesException::getCurrentInstance()->getMessage()); ?></h3>
<p>
<b>Detail:</b><br>
<?php echo(MyFusesException::getCurrentInstance()->getDetail()); ?>
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
    
<?php $stackTrace = MyFusesException::getCurrentInstance()->getTrace();?>
<?php for( $i = 1; $i  < count( $stackTrace ); $i ++ ){?>
    <tr bgcolor="#<?php echo ( ( $i % 2 ) == 0  ? "bee2fb" : "d7edfd" );?>">
        <td><b><font size="2"><?php echo count( $stackTrace ) - ( $i + 1 );?></font></b></td>
        <td><font size="2"><?php echo MyFusesException::getTraceFunctionString( $stackTrace[ $i ] ) ; ?></font></td>
        <td><font size="2"><?php echo MyFusesException::getTraceLocationString( $stackTrace[ $i ] );?></font></td>
    </tr>
<?php }?>

</table>
