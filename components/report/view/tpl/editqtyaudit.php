<td width="100px"></td>
<td>
	<script>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($itm_description);$x++){ print '"'.$itm_description[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
	</script>

	Item : <input type="text" id="tags1" name="item" value="<?php print $item; ?>" />
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
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >Item</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $item; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >From Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >To Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<hr>
</div>


<hr><br>

<div id="print">
	<table id="data_table1" align="center" style="font-size:12pt">
	<tr bgcolor="#CCCCCC"><th class="shipmentTB3">Date</th><th class="shipmentTB3">Store</th><th class="shipmentTB3">Item</th><th class="shipmentTB3">Cost</th><th class="shipmentTB3">Old<br />Qty</th><th class="shipmentTB3">Action<br />Qty</th><th class="shipmentTB3">New<br />Qty</th><th class="shipmentTB3">Total Price</th><th class="shipmentTB3">Edit By</th><th class="shipmentTB3" width="200px">Comment</th></tr>
	<?php
	$total_acqty=$total_price=0;
	for($i=0;$i<sizeof($ie_date);$i++){
		$total_acqty+=$ie_action_qty[$i];
		$total_price+=$ie_item_cost[$i]*$ie_action_qty[$i];
		print '<tr bgcolor="#EEEEEE"><td align="center" class="shipmentTB3">'.$ie_date[$i].'</td><td class="shipmentTB3">'.$ie_store[$i].'</td><td class="shipmentTB4" style="color:blue">'.$ie_item[$i].'</td><td class="shipmentTB3" align="right">'.number_format($ie_item_cost[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($ie_old_qty[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($ie_action_qty[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($ie_old_qty[$i]+$ie_action_qty[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($ie_item_cost[$i]*$ie_action_qty[$i]).'</td><td class="shipmentTB3">'.ucfirst($ie_user[$i]).'</td><td class="shipmentTB3">'.$ie_comment[$i].'</td></tr>';
	}
	print '<tr bgcolor="#CCCCCC"><td class="shipmentTB4" colspan="3">Sub Total</td><td></td><td></td><td class="shipmentTB3" align="right">'.number_format($total_acqty).'</td><td></td><td class="shipmentTB3" align="right">'.number_format($total_price).'</td><td></td><td></td></tr>';
	?>
	</table>
</div>

<div style="display:none">
	<table id="data_table2"></table>
	<table id="data_table3"></table>
	<table id="data_table4"></table>
	<table id="data_table5"></table>
</div>

<table><tr><td></td>