<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<!-- ------------------Item List----------------------- -->

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Cost Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print date("Y-m-d",time()); ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<p>Note: Total cost value of Stores and total retured items</p><hr>
</div>
<form action="index.php?components=report&action=cost" method="post" onsubmit="return validateDateRange()" >
	<table align="center"><tr><td>
	<div style="border-radius:10px; background-color:#DDDDDD;padding-left :20px; padding-right:20px;">
		<table align="center" style="font-family:Calibri; font-size:12pt">
		<tr><td><strong>From Date:</strong> <input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" style="width:130px" /></td><td width="80px"></td>
		<td><strong>To Date:</strong> <input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" style="width:130px" /></td>
		<td><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
		</table>
	</div>
	</td></tr></table>
</form>
<table align="center" width="300px"><tr>
<td><h1 align="center" style="color:#0158C2">Cost Report</h1></td>
</tr></table>
<hr />
<div id="print">
<table align="center">
<tr><td style="vertical-align:top">
	<table align="center" style="font-size:11pt; font-family:Calibri">
	<tr><td colspan="4" style="color:gray">&nbsp;&nbsp;<strong>Inventory Items | By Category</strong></td></tr>
	<tr style="background-color:#467898;color :white;"><th>&nbsp;&nbsp;Category Name&nbsp;&nbsp;</th><th>Total Cost of Items</th></tr>
	<?php
	for($i=0;$i<sizeof($itc_name);$i++){
	print '<tr bgcolor="#EEEEEE"><td style="color:navy"><strong>&nbsp;&nbsp;'.$itc_name[$i].'</strong></td><td align="right" style="padding-right:10px">'.number_format($itc_total[$i]).'</td></tr>';
	}
	print '<tr bgcolor="#CCCCCC"><td><strong>&nbsp;&nbsp;Total</strong></td><td align="right" style="padding-right:10px"><strong>'.number_format(array_sum($itc_total)).'</strong></td></tr>';
	?>
	</table>
	<br>
	<br>
</td><td width="100px"></td><td style="vertical-align:top">
	<table align="center" style="font-size:11pt; font-family:Calibri">
	<tr><td colspan="4" style="color:gray">&nbsp;&nbsp;<strong>Inventory Items | By Store</strong></td></tr>
	<tr style="background-color:#467898;color :white;"><th>&nbsp;&nbsp;Store Name&nbsp;&nbsp;</th><th>Total Cost of Items</th><th>Total Wholesale<br />Value of Items</th><th>Total Wholesale<br />Value of Items<br />With Minimum Discount</th></tr>
	<?php
	$total_c=$total_w=$total_wmin=0;
	for($i=0;$i<sizeof($st_name);$i++){
	print '<tr bgcolor="#EEEEEE"><td style="color:navy"><strong>&nbsp;&nbsp;'.$st_name[$i].'</strong></td><td align="right" style="padding-right:10px">'.number_format($store_c_total[$st_name[$i]]).'</td><td align="right" style="padding-right:10px">'.number_format($store_w_total[$st_name[$i]]).'</td><td align="right" style="padding-right:10px">'.number_format($store_wmin_total[$st_name[$i]]).'</td></tr>';
	}
	print '<tr bgcolor="#CCCCCC"><td><strong>&nbsp;&nbsp;Total</strong></td><td align="right" style="padding-right:10px"><strong>'.number_format(array_sum($store_c_total)).'</strong></td><td align="right" style="padding-right:10px"><strong>'.number_format(array_sum($store_w_total)).'</strong></td><td align="right" style="padding-right:10px"><strong>'.number_format(array_sum($store_wmin_total)).'</strong></td></tr>';
	print '<tr bgcolor="#CCCCCC"><td><strong>&nbsp;&nbsp;Total Including Pending</strong>&nbsp;&nbsp;</td><td align="right" style="padding-right:10px"><strong>'.number_format(array_sum($store_c_total)+array_sum($pending_bm_amount)+array_sum($pending_tr_amount)).'</strong></td><td align="right" style="padding-right:10px" colspan="2"><i>Including Pending Bills and Transfers</i></td></tr>';
	?>
	</table>
	<br>
	<br>
	<table style="font-size:11pt; font-family:Calibri">
	<tr><td colspan="2" style="color:gray">&nbsp;&nbsp;<strong>Disposal Items</strong></td></tr>
	<tr style="background-color:#467898;color :white;"><th>&nbsp;&nbsp;Store Name&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Total Cost of Items&nbsp;&nbsp;</th></tr>
	<?php
	$total=0;
	for($i=0;$i<sizeof($st_name);$i++){
	print '<tr bgcolor="#EEEEEE"><td style="color:navy"><strong>&nbsp;&nbsp;'.$st_name[$i].'</strong></td><td align="right" style="padding-right:10px">'.number_format($disposal_total[$st_name[$i]]).'</td></tr>';
	$total+=$disposal_total[$st_name[$i]];
	}
	print '<tr bgcolor="#CCCCCC"><td><strong>&nbsp;&nbsp;Total</strong></td><td align="right" style="padding-right:10px"><strong>'.number_format($total).'</strong></td></tr>';
	?>
	</table>
	<hr />
	<table width="100%">
	<tr><td valign="top">
		<table style="font-size:11pt; font-family:Calibri">
		<tr><td colspan="2" style="color:gray">&nbsp;&nbsp;<strong>Pending Bills</strong></td></tr>
		<tr style="background-color:#467898;color :white;"><th align="center">Date</th><th align="center">Invoice Number</th><th>&nbsp;&nbsp;Cost of Items&nbsp;&nbsp;</th></tr>
		<?php
		for($i=0;$i<sizeof($pending_bm_no);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center" style="padding-left:10px; padding-right:10px;">'.$pending_bm_date[$i].'</td><td style="color:navy" align="center"><a style="text-decoration:none" onmouseover="style=\'text-decoration:underline\'" onmouseout="style=\'text-decoration:none\'" target="blank" href="index.php?components=billing&action=finish_bill&id='.$pending_bm_no[$i].'">'.str_pad($pending_bm_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="right" style="padding-right:10px">'.number_format($pending_bm_amount[$i]).'</td></tr>';
		}
		print '<tr bgcolor="#CCCCCC"><td colspan="2"><strong>&nbsp;&nbsp;Total</strong></td><td align="right" style="padding-right:10px"><strong>'.number_format(array_sum($pending_bm_amount)).'</strong></td></tr>';
		?>
		</table>
	</td><td width="20px;"></td><td valign="top" align="right">
		<table style="font-size:11pt; font-family:Calibri">
		<tr><td colspan="2" style="color:gray">&nbsp;&nbsp;<strong>Pending Transfers</strong></td></tr>
		<tr style="background-color:#467898;color :white;"><th align="center">Date</th><th align="center">Transfer Number</th><th>&nbsp;&nbsp;Cost of Items&nbsp;&nbsp;</th></tr>
		<?php
		for($i=0;$i<sizeof($pending_tr_no);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center" style="padding-left:10px; padding-right:10px;">'.$pending_tr_date[$i].'</td><td style="color:navy" align="center"><a style="text-decoration:none" onmouseover="style=\'text-decoration:underline\'" onmouseout="style=\'text-decoration:none\'" target="blank" href="index.php?components=trans&action=print_gtn&approve_permission=0&id='.$pending_tr_no[$i].'">'.str_pad($pending_tr_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="right" style="padding-right:10px">'.number_format($pending_tr_amount[$i]).'</td></tr>';
		}
		print '<tr bgcolor="#CCCCCC"><td colspan="2"><strong>&nbsp;&nbsp;Total</strong></td><td align="right" style="padding-right:10px"><strong>'.number_format(array_sum($pending_tr_amount)).'</strong></td></tr>';
		?>
		</table>
	</td></tr>
	</table></td></tr>
</table>
</div>	
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>

</td></tr></table>
<br />
<?php
                include_once  'template/footer.php';
?>