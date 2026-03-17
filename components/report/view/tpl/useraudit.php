<td width="100px"></td>
<td>
<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($user_name);$x++){ print '"'.$user_name[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
</script>

	User : <input type="text" id="tags1" name="username" value="<?php print $username; ?>" />
</td>
<td width="30px"></td>
<?php  if(!isMobile()){ ?>
<td rowspan="2"><input type="submit" value="Generate" style="height:60px" /></td>
<?php } ?>
</tr>
<tr><td>From Date: <input type="date" name="from_date" value="<?php print $from_date; ?>" /></td><td></td><td>To Date: <input type="date" name="to_date" value="<?php print $to_date; ?>" /></td><td></td></tr>
<?php  if(isMobile()){ ?>
<tr><td align="center" colspan="4"><input type="submit" value="Generate" style="height:60px" /></td></tr>
<?php } ?>
</table>
</form>
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Item Sale By Salesman</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >User</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $username; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >From Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >To Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<hr>
</div>

<div id="print">

<hr><br>
	<table id="data_table1" align="center" style="font-size:12pt" width="600px">
	<tr><td colspan="4" bgcolor="#467898" style="color:white" align="center">Sales Billing Deletions</td></tr>
	<tr bgcolor="#CCCCCC"><th width="200px">Date Time</th><th width="100px">Invoice No</th><th>Lock Status</th><th>Deleted By</th></tr>
	<?php
	for($i=0;$i<sizeof($bill_no);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center">'.$bill_del_date[$i].'</td><td align="center"><a href="index.php?components=billing&action=finish_bill&id='.$bill_no[$i].'">'.str_pad($bill_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td>&nbsp;&nbsp;'.$bill_lock[$i].'</td><td>&nbsp;&nbsp;'.$bill_deleted_by[$i].'&nbsp;&nbsp;</td></tr>';
	}
	?>
	</table>
	<br />
	<table id="data_table2" align="center" style="font-size:12pt" width="600px">
	<tr><td colspan="4" bgcolor="#467898" style="color:white" align="center">Transfer | Rejected & Cancelled</td></tr>
	<tr bgcolor="#CCCCCC"><th width="200px">Date Time</th><th width="100px">Invoice No</th><th>User</th></tr>
	<tr bgcolor="#CCCCCC"><td colspan="3" style="color:navy; font-weight:bold">&nbsp;&nbsp;Rejected</td></tr>
	<?php
	for($i=0;$i<sizeof($tr1_no);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center">'.$tr1_del_date[$i].'</td><td align="center"><a href="index.php?components=trans&action=print_gtn&id='.$tr1_no[$i].'&approve_permission=0">'.str_pad($tr1_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td>&nbsp;&nbsp;'.$tr1_deleted_by[$i].'&nbsp;&nbsp;</td></tr>';
	}
	?>
	<tr bgcolor="#CCCCCC"><td colspan="3" style="color:navy; font-weight:bold">&nbsp;&nbsp;Canceled</td></tr>
	<?php
	for($i=0;$i<sizeof($tr2_no);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center">'.$tr2_del_date[$i].'</td><td align="center"><a href="index.php?components=trans&action=print_gtn&id='.$tr2_no[$i].'&approve_permission=0">'.str_pad($tr2_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td>&nbsp;&nbsp;'.$tr2_deleted_by[$i].'&nbsp;&nbsp;</td></tr>';
	}
	?>
	</table>
	<br />
	<table id="data_table3" align="center" style="font-size:12pt" width="600px">
	<tr><td colspan="4" bgcolor="#467898" style="color:white" align="center">Shipment Deletions</td></tr>
	<tr bgcolor="#CCCCCC"><th width="200px">Deleted Date</th><th width="100px">Shipment No</th><th>Approved By</th><th>Deleted By</th></tr>
	<?php
	for($i=0;$i<sizeof($sm_no);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center">'.$sm_del_date[$i].'</td><td align="center"><a href="index.php?components=inventory&action=one_shipment&shipment_no='.$sm_no[$i].'">'.str_pad($sm_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td>&nbsp;&nbsp;<a href="#" style="text-decoration:none" title="Approved for Deletion on : '.$sm_del_approve_date[$i].'">'.$sm_del_approve_by[$i].'</a></td><td>&nbsp;&nbsp;'.$sm_del_by[$i].'&nbsp;&nbsp;</td></tr>';
	}
	?>
	</table>
	<br />
	<table id="data_table4" align="center" style="font-size:12pt" width="600px">
	<tr><td colspan="4" bgcolor="#467898" style="color:white" align="center">Shipment Unique Item Deletions</td></tr>
	<tr bgcolor="#CCCCCC"><th width="200px">Deleted Date</th><th width="100px">Shipment No</th><th>Unique ID</th><th>Deleted By</th></tr>
	<?php
	for($i=0;$i<sizeof($itu_sm_no);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center">'.$itu_del_date[$i].'</td><td align="center"><a href="index.php?components=inventory&action=one_shipment&shipment_no='.$itu_sm_no[$i].'">'.str_pad($itu_sm_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td>&nbsp;&nbsp;<a href="index.php?components=manager&action=unic_items&search_unic='.$itu_sn[$i].'" >'.$itu_sn[$i].'</a>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$itu_del_by[$i].'&nbsp;&nbsp;</td></tr>';
	}
	?>
	</table>
</div>

<div style="display:none">
	<table id="data_table5"></table>
</div>
<table><tr><td></td>