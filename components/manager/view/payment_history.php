<?php
                include_once  'template/header.php';
?>
<!-- ----------------------------------------------------------------------------- -->
<div id="printheader" style="display:none" >
	<h2 style="color:navy"><?php print $store_company; ?></h2>
	<p style="color:#3333FF; text-decoration:underline; font-family:Calibri"><strong>Sub System: Payment Collections</strong></p>
	<table border="1" cellspacing="0" style="font-family:Calibri">
	<tr><td>&nbsp;&nbsp;&nbsp;<strong>From Date</strong>&nbsp;&nbsp;&nbsp;</td><td width="100px" align="center"><?php print $from_date; ?></td></tr>
	<tr><td>&nbsp;&nbsp;&nbsp;<strong>To Date</strong>&nbsp;&nbsp;&nbsp;</td><td width="100px" align="center"><?php print $to_date; ?></td></tr>
	</table>
	<hr />
</div>

<form id="search_form" action="index.php" method="get">
<input type="hidden" name="components" value="manager" />
<input type="hidden" name="action" value="payment_history" />
<table align="center" height="100%" cellspacing="0" style="font-family:Calibri; border-radius: 15px;" bgcolor="#F0F0F0" >
<tr><td width="100px"></td><td><strong>From</strong> <input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td>
<td width="50px"></td><td><strong>To</strong> <input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td>
<td><a onclick="document.getElementById('search_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td><td width="100px"></td></tr>
<tr><td colspan="8" align="center" style="font-size:10pt;">Returned Cheques will be Shown in <span style="color:red">Red</span> Color</td></tr>
</table>
</form>
<br />
<div id="print">
<table align="center" style="font-family:Calibri; font-size:11pt" border="1" cellspacing="0">
<tr><td class="shipmentTB4" style="background-color:#467898; color:white;" colspan="2">Summary Table</td></tr>
<tr><td class="shipmentTB4" bgcolor="silver">Balance Befor "From" Date</td><td class="shipmentTB4" bgcolor="#EEEEEE" align="right" width="100px"><?php print number_format($balance_before_from); ?></td></tr>
<tr><td class="shipmentTB4" bgcolor="silver">Balance within Selected Date</td><td class="shipmentTB4" bgcolor="#EEEEEE" align="right" width="100px"><?php print number_format($balance_within_period); ?></td></tr>
<tr><td class="shipmentTB4" bgcolor="silver">Balance After "To" Date</td><td class="shipmentTB4" bgcolor="#EEEEEE" align="right" width="100px"><?php print number_format($balance_after_to); ?></td></tr>
</table>
<br />
<table align="center" style="font-family:Calibri; font-size:11pt" border="1" cellspacing="0">
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB4">Submited By</th><th class="shipmentTB4">Submited Date</th><th class="shipmentTB4">Processed By</th><th class="shipmentTB4">Processed Date</th><th class="shipmentTB4">Cash</th><th class="shipmentTB4">Cheque</th><th class="shipmentTB4">Cheque Number</th></tr>
	<?php 
	$cash_total=$chq_total=0;
	for($i=0;$i<sizeof($ps_amount);$i++){
		if($ps_type[$i]==1){ $cash=number_format($ps_amount[$i]); $cheque=''; if($ps_status_code[$i]==2) $cash_total+=$ps_amount[$i]; }
		if($ps_type[$i]==2){ $cheque=number_format($ps_amount[$i]); $cash=''; if($ps_status_code[$i]==2) $chq_total+=$ps_amount[$i]; }
		if($ps_status_code[$i]==4){ $color1=$color2='red'; }else{ $color1='black'; $color2='blue'; }
		if($ps_cust_chq[$i]!=''){ $a1='<a href="index.php?components=billing&action=finish_payment&id='.$ps_cust_chq[$i].'">'; $a2='</a>'; }else{ $a1=$a2=''; }
			print '<tr style="color:'.$color1.'"><td class="shipmentTB3">'.ucfirst($ps_submited_by[$i]).'</td><td class="shipmentTB3"><a style="cursor:pointer; color:'.$color2.';" title="Time: '.substr($ps_submited_date[$i],11,5).'">'.substr($ps_submited_date[$i],0,10).'</a></td><td class="shipmentTB3">'.ucfirst($ps_processed_by[$i]).'</td><td class="shipmentTB3"><a style="cursor:pointer; color:'.$color2.';" title="Time: '.substr($ps_processed_date[$i],11,5).'">'.substr($ps_processed_date[$i],0,10).'</a></td><td class="shipmentTB3" align="right">'.$cash.'</td><td class="shipmentTB3" align="right">'.$cheque.'</td><td class="shipmentTB3">'.$a1.$ps_chque_no[$i].' : '.$ps_chque_bank[$i].' : '.$ps_chque_branch[$i].$a2.'</td></tr>';
	} 
			print '<tr style="background-color:#DDDDDD;"><td colspan="4" align="right" class="shipmentTB3"><strong>TOTAL</strong></td><td class="shipmentTB3" align="right">'.$cash_total.'</td><td class="shipmentTB3" align="right">'.$chq_total.'</td><td></td></tr>';

	?>
</table>
</div>
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px;">
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