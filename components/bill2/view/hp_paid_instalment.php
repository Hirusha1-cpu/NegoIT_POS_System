<?php
                include_once  'template/header.php';
?>

<!-- -------------------------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<!-- -------------------------------------------------------------------------------------------------------------------------------------- -->
	<form action="index.php" method="get">
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="hp_paid_instalment" />
	<table align="center" height="100%" cellspacing="0" border="0" style="font-family:Calibri; font-size:12pt; border-radius: 15px; padding-left:10px; padding-right:10px" bgcolor="#F0F0F0" border="0">
	<tr><td width="50px"></td>
	<td width="80px">Invoice No</td><td><input type="text" id="invoice_no" name="invoice_no" value="<?php print $invoice_no; ?>" style="width:80px; text-align:center" /></td><td width="50px"></td>
	<td width="120px">Instalment Date</td><td>
		<select id="inst_date" name="inst_date" onchange="this.form.submit()">
		<?php 
			for($i=1;$i<=sizeof($hp_schedule);$i++){
				if($hp_schedule[$i]==$inst_date) $select='selected="selected"'; else $select='';
				print '<option value="'.$hp_schedule[$i].'" '.$select.'>'.$hp_schedule[$i].'</option>';
			}
		?>
		</select>
	</td><td width="50px"></td>
	<td><input type="submit" value="Submit" style="height:35px; width:80px" /></td>
	<td width="50px"></td></tr>
	</table>
	</form>
<br />
	
<table align="center" style="font-family:Calibri">
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3">Instalment Date</th><th class="shipmentTB3" >Instalment Amount</th><th class="shipmentTB3">Payment No</th><th class="shipmentTB3">Payment Date</th><th class="shipmentTB3">Payment Amount</th><th class="shipmentTB3">Pay Total</th></tr>
	<?php
	for($i=0;$i<sizeof($py_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#DDDDDD';
		print '<tr style="background-color:'.$color.';">';
		if($i==0)	print '<td class="shipmentTB3" rowspan="'.sizeof($py_id).'" align="center" style="background-color:#CCCCFF; font-weight:bold;">'.$inst_date.'</td><td class="shipmentTB3" align="center" rowspan="'.sizeof($py_id).'" style="background-color:#CCCCFF; font-weight:bold;">'.number_format($hp_amount).'</td>';
		print '<td class="shipmentTB3" align="center"><a href="index.php?components=bill2&action=finish_payment&id='.$py_id[$i].'" style="text-decoration:none">'.str_pad($py_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3">'.$py_date[$i].'</td><td class="shipmentTB3"  align="right">'.number_format($py_amount[$i]).'</td><td class="shipmentTB3" align="right">'.number_format(array_sum($py_amount)).'</td></tr>';
	}
	?>
</table>
<?php
                include_once  'template/footer.php';
?>