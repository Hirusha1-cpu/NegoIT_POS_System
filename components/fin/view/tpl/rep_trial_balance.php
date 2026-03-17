<div id="printheader" style="display:none;" >
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Balance Sheet</h2>
	<table style="font-family:Calibri">
	<tr><td width="150px"><strong>Generated Date</strong></td><td><?php print dateNow();?></td></tr>
	</table>
	<hr />
	<hr />
	<br />
</div>

<div id="print">
<table align="center" width="700px" style="font-family:Calibri">
<!--<tr><td colspan="2" style="color:#467898; font-weight:bold; font-size:14pt">Assets</td></tr>-->
<tr bgcolor="#8898A1" style="color:white"><td class="shipmentTB3">Account</td><td width="150px" class="shipmentTB3" align="right">DR</td><td width="150px" class="shipmentTB3" align="right">CR</td></tr>
<?php 
//---------------------------------------------------Assets---------------------------------------------------------------//
$total_dr=$total_cr=0;
for($i=0;$i<sizeof($ass_ac_id);$i++){
	if($ass_ac_name[$i]!='Undeposited Cheques'){
		$dr=$cr='';
		if($ass_ac_amount[$i]>=0){
			$dr=number_format($ass_ac_amount[$i],2); 
			$total_dr+=$ass_ac_amount[$i];
		}else{
			$cr=number_format(-$ass_ac_amount[$i],2);
			$total_cr-=$ass_ac_amount[$i];
		}
		print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" href="index.php?components=fin&action=acount_history&id='.$ass_ac_id[$i].'">'.$ass_ac_name[$i].'</a></td><td class="shipmentTB3" align="right">'.$dr.'</td><td class="shipmentTB3" align="right">'.$cr.'</td></tr>';
	}
} 
//------------------------------------------------Liabilities-------------------------------------------------------------//
for($i=0;$i<sizeof($lia_ac_id);$i++){
	$dr=$cr='';
	if($lia_ac_amount[$i]>=0){
		$cr=number_format($lia_ac_amount[$i],2); 
		$total_cr+=$lia_ac_amount[$i];
	}else{
		$dr=number_format(-$ass_ac_amount[$i],2);
		$total_dr-=$lia_ac_amount[$i];
	}
	print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" href="index.php?components=fin&action=acount_history&id='.$lia_ac_id[$i].'">'.$lia_ac_name[$i].'</a></td><td class="shipmentTB3" align="right">'.$dr.'</td><td class="shipmentTB3" align="right">'.$cr.'</td></tr>';
}
	print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Initial Shipments</td><td class="shipmentTB3" align="right"></td><td class="shipmentTB3" align="right">'.number_format($initial_ship).'</td></tr>';
	$total_cr+=$initial_ship;
//------------------------------------------------Equity-------------------------------------------------------------//
for($i=0;$i<sizeof($equ_ac_id);$i++){
	$dr=$cr='';
	if($equ_ac_amount[$i]>=0){
		$cr=number_format($equ_ac_amount[$i],2); 
		$total_cr+=$equ_ac_amount[$i];
	}else{
		$dr=number_format(-$equ_ac_amount[$i],2);
		$total_dr-=$equ_ac_amount[$i];
	}
	print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" href="index.php?components=fin&action=acount_history&id='.$equ_ac_id[$i].'">'.$equ_ac_name[$i].'</a></td><td class="shipmentTB3" align="right">'.$dr.'</td><td class="shipmentTB3" align="right">'.$cr.'</td></tr>';
}
//------------------------------------------------Income-------------------------------------------------------------//
for($i=0;$i<sizeof($inc_ac_id);$i++){
	$dr=$cr='';
	if($inc_ac_amount[$i]>=0){
		$dr=number_format($inc_ac_amount[$i],2); 
		$total_dr+=$inc_ac_amount[$i];
	}else{
		$cr=number_format(-$inc_ac_amount[$i],2);
		$total_cr-=$inc_ac_amount[$i];
	}
	print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" href="index.php?components=fin&action=acount_history&id='.$inc_ac_id[$i].'">'.$inc_ac_name[$i].'</a></td><td class="shipmentTB3" align="right">'.$dr.'</td><td class="shipmentTB3" align="right">'.$cr.'</td></tr>';
} 
for($i=0;$i<sizeof($exp_ac_id);$i++){
	$dr=$cr='';
	if($exp_ac_amount[$i]>=0){
		$dr=number_format($exp_ac_amount[$i],2); 
		$total_dr+=$exp_ac_amount[$i];
	}else{
		$cr=number_format(-$exp_ac_amount[$i],2);
		$total_cr-=$exp_ac_amount[$i];
	}
	print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none; color:inherit" href="index.php?components=fin&action=acount_history&id='.$exp_ac_id[$i].'">'.$exp_ac_name[$i].'</a></td><td class="shipmentTB3" align="right">'.$dr.'</td><td class="shipmentTB3" align="right">'.$cr.'</td></tr>';
}
	print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cost of Goods Sold (COGS)</td><td class="shipmentTB3" align="right">'.number_format($cogs).'</td><td class="shipmentTB3" align="right"></td></tr>';
	$total_dr+=$cogs;
	print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($total_dr,2).'</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($total_cr,2).'</strong></td></tr>';
 ?>
 </table>
</div>