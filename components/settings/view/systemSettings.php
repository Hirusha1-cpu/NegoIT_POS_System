<?php
                include_once  'template/header.php';
?>

<table align="center" bgcolor="#E5E5E5" style="font-family:Calibri">
<tr><td colspan="4"><?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='red';
print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br />'; 
}
?></td></tr>
</table>

<table align="center" style="font-family:Calibri">
<tr bgcolor="#DDDDDD"><th>Settinng</th><th>Data</th><th>Action</th></tr>
<tr bgcolor="#EFEFEF"><td style="padding-left:20px; padding-right:20px">Fragmented Rows in <br /> Inventory temp table</td><td align="center"><input type="text" readonly="readonly" value="<?php print $inventory_temp_fagmented; ?>" style="width:50px; text-align:center" /></td><td><input type="button" value="Clear Data" onclick="window.location = 'index.php?components=settings&action=clear_invtemp'" /> </td></tr>
<tr bgcolor="#EFEFEF"><td style="padding-left:20px; padding-right:20px">SMS Balance</td><td align="center"><input type="text" readonly="readonly" value="<?php print $smsbalance; ?>" style="width:50px; text-align:center" /></td><td></td></tr>
<form method="post" action="index.php?components=settings&action=update_time">
<tr bgcolor="#EFEFEF"><td style="padding-left:20px; padding-right:20px">Working Time</td><td>From <input type="text" name="time_from" value="<?php print $time_from; ?>" style="width:40px;" />&nbsp;&nbsp;&nbsp;To <input type="text" name="time_to" value="<?php print $time_to; ?>" style="width:40px;" /></td><td><input type="Submit" value="Update" /> </td></tr>
</form>
<tr bgcolor="#EFEFEF"><td style="padding-left:20px; padding-right:20px">Bill PreCal Total Errors</td><td>
	<?php 
	for($i=0;$i<sizeof($precal_err_inv);$i++){
		print 'Bill No: <span style="color:red">'.str_pad($precal_err_inv[$i], 7, "0", STR_PAD_LEFT).'</span><br />';
	}
	?>
</td><td><?php if(sizeof($precal_err_inv)>0){ ?><input type="button" onclick="window.location = 'index.php?components=settings&action=pre_cal_bill'" value="Pre Calculate" /> <?php } ?></td></tr>
<tr bgcolor="#EFEFEF"><td style="padding-left:20px; padding-right:20px">System Time</td><td align="center"><?php print $time_now; ?></td><td></td></tr>
</table>

<?php
                include_once  'template/footer.php';
?>