<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

	<div id="print">
	<form action="index.php?components=report&action=cost" method="post" onsubmit="return validateDateRange()" >
	<table align="center" style="font-family:Calibri; font-size:12pt">
	<tr><td>From Date: <input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" style="width:120px" /></td><td width="80px"></td>
	<td>To Date: <input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" style="width:120px" /></td>
	<td><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
	</table>
	</form>

	<table align="center" width="300px"><tr>
	<td><h1 align="center" style="color:#0158C2">Cost Report</h1></td>
	</tr></table>
	<div id="print">
	<table align="center"><tr><td style="vertical-align:top">
		<table align="center" style="font-size:11pt; font-family:Calibri">
		<tr><td colspan="4" style="color:gray">&nbsp;&nbsp;<strong>Inventory Items</strong></td></tr>
		<tr style="background-color:#467898;color :white;"><th>&nbsp;&nbsp;Store Name&nbsp;&nbsp;</th><th>Total Cost of Items</th><th>Total Wholesale<br />Value of Items</th><th>Total Wholesale<br />Value of Items<br />With Minimum Discount</th></tr>
		<?php
		$total_c=$total_w=$total_wmin=0;
		for($i=0;$i<sizeof($st_name);$i++){
		print '<tr bgcolor="#EEEEEE"><td style="color:navy"><strong>&nbsp;&nbsp;'.$st_name[$i].'</strong></td><td align="right">'.number_format($store_c_total[$st_name[$i]]).'&nbsp;&nbsp;</td><td align="right">'.number_format($store_w_total[$st_name[$i]]).'&nbsp;&nbsp;</td><td align="right">'.number_format($store_wmin_total[$st_name[$i]]).'&nbsp;&nbsp;</td></tr>';
		}
		print '<tr bgcolor="#CCCCCC"><td><strong>&nbsp;&nbsp;Total</strong></td><td align="right"><strong>'.number_format(array_sum($store_c_total)).'&nbsp;&nbsp;</strong></td><td align="right"><strong>'.number_format(array_sum($store_w_total)).'&nbsp;&nbsp;</strong></td><td align="right"><strong>'.number_format(array_sum($store_wmin_total)).'&nbsp;&nbsp;</strong></td></tr>';
		print '<tr bgcolor="#CCCCCC"><td><strong>&nbsp;&nbsp;Total Including Pending</strong>&nbsp;&nbsp;</td><td align="right" style="padding-right:10px"><strong>'.number_format(array_sum($store_c_total)+array_sum($pending_bm_amount)+array_sum($pending_tr_amount)).'</strong></td><td align="right" style="padding-right:10px" colspan="2"><i>Including Pending Bills and Transfers</i></td></tr>';
		?>
		</table>
		<br>
		<br>
		
		<table align="center" style="font-size:11pt; font-family:Calibri">
		<tr><td colspan="2" style="color:gray">&nbsp;&nbsp;<strong>Disposal Items</strong></td></tr>
		<tr style="background-color:#467898;color :white;"><th>&nbsp;&nbsp;Store Name&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Total Cost of Items&nbsp;&nbsp;</th></tr>
		<?php
		$total=0;
		for($i=0;$i<sizeof($st_name);$i++){
		print '<tr bgcolor="#EEEEEE"><td style="color:navy"><strong>&nbsp;&nbsp;'.$st_name[$i].'</strong></td><td align="right">'.number_format($disposal_total[$st_name[$i]]).'&nbsp;&nbsp;</td></tr>';
		$total+=$disposal_total[$st_name[$i]];
		}
		print '<tr bgcolor="#CCCCCC"><td><strong>&nbsp;&nbsp;Total</strong></td><td align="right"><strong>'.number_format($total).'&nbsp;&nbsp;</strong></td></tr>';
		?>
		</table>
	</td><td width="30px"></td><td style="vertical-align:top"><div id="landscape" style="vertical-align:top" ></div></td></tr>
	<tr><td style="vertical-align:top"><div id="portrait" >
		<table align="center" style="font-size:11pt; font-family:Calibri">
		<tr><td colspan="4" style="color:gray">&nbsp;&nbsp;<strong>Inventory Items | By Category</strong></td></tr>
		<tr style="background-color:#467898;color :white;"><th>&nbsp;&nbsp;Category Name&nbsp;&nbsp;</th><th>Total Cost of Items</th></tr>
		<?php
		for($i=0;$i<sizeof($itc_name);$i++){
		print '<tr bgcolor="#EEEEEE"><td style="color:navy"><strong>&nbsp;&nbsp;'.$itc_name[$i].'</strong></td><td align="right">'.number_format($itc_total[$i]).'&nbsp;&nbsp;</td></tr>';
		}
		print '<tr bgcolor="#CCCCCC"><td><strong>&nbsp;&nbsp;Total</strong></td><td align="right"><strong>'.number_format(array_sum($itc_total)).'&nbsp;&nbsp;</strong></td></tr>';
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
	</table>
	</div></td><td></td><td></td></tr>
	
	</div>	

  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>