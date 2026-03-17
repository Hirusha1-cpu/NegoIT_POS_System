	<script type="text/javascript">
		function expand($id1,$id2){
			document.getElementById($id1).style.display = "block";
			document.getElementById($id2).style.display = "none";
		}
	</script>


<div id="printheader" style="display:none;" >
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Balance Sheet</h2>
	<table style="font-family:Calibri">
	<tr><td width="150px"><strong>Generated Date</strong></td><td><?php print dateNow();?></td></tr>
	<tr><td width="150px"><strong>As Of</strong></td><td><?php print $_GET['to_date'];?></td></tr>
	</table>
	<hr />
	<hr />
	<br />
</div>

<div id="print">
<table align="center" width="600px" style="font-family:Calibri">
<tr><td colspan="2" style="color:#467898; font-weight:bold; font-size:14pt">Assets</td></tr>
<tr bgcolor="#8898A1" style="color:white"><td class="shipmentTB3">Account</td><td width="150px" class="shipmentTB3" align="right">Balance</td></tr>
<?php 
$l2=$l3='';
$total_asset=0;
for($i=0;$i<sizeof($ass_ac_id);$i++){
	if($ass_ac_name[$i]!='Undeposited Cheques'){
		if($l2!=$ass_ac_catL2[$i])	print '<tr bgcolor="#F5F5F5"><td class="shipmentTB3" colspan="2">&nbsp;+ '.$ass_ac_catL2[$i].'</td></tr>';
		if($l3!=$ass_ac_catL3[$i])	print '<tr bgcolor="#F5F5F5"><td class="shipmentTB3" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$ass_ac_catL3[$i].'</td></tr>';
		print '<tr><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" href="index.php?components=fin&action=acount_history&id='.$ass_ac_id[$i].'">'.$ass_ac_name[$i].'</a></td><td class="shipmentTB3" align="right">'.number_format($ass_ac_amount[$i]).'</td></tr>';
		$l2=$ass_ac_catL2[$i];
		$l3=$ass_ac_catL3[$i];
		$total_asset+=$ass_ac_amount[$i];
	}
} ?>
<tr><td class="shipmentTB3"><strong>Total Assets</strong></td><td width="150px" class="shipmentTB3" align="right"><strong><?php print number_format($total_asset); ?></strong></td></tr>

<tr><td colspan="2" ><br /></td></tr>

<tr><td colspan="2" style="color:#467898; font-weight:bold; font-size:14pt">Liabilities & Equity</td></tr>
<tr><td colspan="2" style="color:grey; font-weight:bold; font-size:12pt">Liabilities</td></tr>
<tr bgcolor="#8898A1" style="color:white"><td class="shipmentTB3">Account</td><td width="150px" class="shipmentTB3" align="right">Balance</td></tr>
<?php 
$l2=$l3='';
$accout_payble_total=0;
for($i=0;$i<sizeof($lia_ac_id);$i++){
	if($l2!=$lia_ac_catL2[$i])	print '<tr bgcolor="#F5F5F5"><td class="shipmentTB3" colspan="2">&nbsp;+ '.$lia_ac_catL2[$i].'</td></tr>';
	if($lia_ac_catL3[$i]=='Account Payable'){
		$accout_payble_total+=$lia_ac_amount[$i];
	}//else{
		if(($l3=='Account Payable')&&($lia_ac_catL3[$i]!='Account Payable')){
			print '</table></div><div id="ac_payble2" ><table align="center" width="600px" style="font-family:Calibri">';
			print '<tr bgcolor="#F5F5F5"><td class="shipmentTB3">&nbsp;&nbsp;<a style="text-decoration:none; cursor:pointer;" onclick="expand('."'ac_payble1'".','."'ac_payble2'".')">+</a>&nbsp; Account Payable</td><td class="shipmentTB3" align="right">'.number_format($accout_payble_total).'</td></tr></table></div>';
			print '<table align="center" width="600px" style="font-family:Calibri">';
		}
		if(($l3!='Account Payable')&&($lia_ac_catL3[$i]=='Account Payable')) print '</table><div id="ac_payble1" style="display:none;"><table align="center" width="600px" style="font-family:Calibri">'; 
		if($l3!=$lia_ac_catL3[$i]){
			if($lia_ac_catL3[$i]=='Account Payable')
				print '<tr bgcolor="#F5F5F5"><td class="shipmentTB3" colspan="2">&nbsp;&nbsp;<a style="text-decoration:none; cursor:pointer;" onclick="expand('."'ac_payble2'".','."'ac_payble1'".')">-</a>&nbsp;'.$lia_ac_catL3[$i].'</td></tr>';
			else
				print '<tr bgcolor="#F5F5F5"><td class="shipmentTB3" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$lia_ac_catL3[$i].'</td></tr>';
		}
		print '<tr><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" href="index.php?components=fin&action=acount_history&id='.$lia_ac_id[$i].'">'.$lia_ac_name[$i].'</a></td><td class="shipmentTB3" align="right">'.number_format($lia_ac_amount[$i]).'</td></tr>';
	$l2=$lia_ac_catL2[$i];
	$l3=$lia_ac_catL3[$i];
} 
		print '<tr><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Initial Shipments</td><td class="shipmentTB3" align="right">'.number_format($initial_ship).'</td></tr>';
$total_liabilities=array_sum($lia_ac_amount)+$initial_ship;
?>
<tr><td class="shipmentTB3"><strong>Total Liabilities</strong></td><td width="150px" class="shipmentTB3" align="right"><strong><?php print number_format($total_liabilities); ?></strong></td></tr>
<tr><td colspan="2" ><br /></td></tr>

<tr><td colspan="2" style="color:grey; font-weight:bold; font-size:12pt"><a style="cursor:pointer" >Equity</a></td></tr>
<tr bgcolor="#8898A1" style="color:white"><td class="shipmentTB3">Account</td><td width="150px" class="shipmentTB3" align="right">Balance</td></tr>
<?php 
$l2=$l3='';
for($i=0;$i<sizeof($equ_ac_id);$i++){
	if($l2!=$equ_ac_catL2[$i])	print '<tr bgcolor="#F5F5F5"><td class="shipmentTB3" colspan="2">&nbsp;+ '.$equ_ac_catL2[$i].'</td></tr>';
	if($l3!=$equ_ac_catL3[$i])	print '<tr bgcolor="#F5F5F5"><td class="shipmentTB3" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$equ_ac_catL3[$i].'</td></tr>';
	print '<tr><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" href="index.php?components=fin&action=acount_history&id='.$equ_ac_id[$i].'">'.$equ_ac_name[$i].'</a></td><td class="shipmentTB3" align="right">'.number_format($equ_ac_amount[$i]).'</td></tr>';
	$l2=$equ_ac_catL2[$i];
	$l3=$equ_ac_catL3[$i];
} 
	print '<tr><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" >Retained Earnings</a></td><td class="shipmentTB3" align="right">'.number_format(array_sum($exp_ac_amount)+$cogs+array_sum($inc_ac_amount)).'</td></tr>';
$total_equities=array_sum($equ_ac_amount)-(array_sum($exp_ac_amount)+$cogs+array_sum($inc_ac_amount));
?>
<tr><td class="shipmentTB3"><strong>Total Equity</strong></td><td width="150px" class="shipmentTB3" align="right"><strong><?php print number_format($total_equities); ?></strong></td></tr>
<tr><td colspan="2" ><br /></td></tr>
<tr bgcolor="#DDDDDD"><td class="shipmentTB3"><strong>Total Liabilities and Equity</strong></td><td class="shipmentTB3" align="right"><strong><?php print number_format($total_liabilities+$total_equities); ?></strong></td></tr>
</table>
</div>