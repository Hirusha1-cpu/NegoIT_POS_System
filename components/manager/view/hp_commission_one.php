<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
function deleteCommissionReport($id){
	var check= confirm("Do you want Delete this Commission Report?");
	if (check== true)
		window.location = 'index.php?components=<?php print $components; ?>&action=hp_commission_delete&id='+$id;
}
</script>

<!-- ------------------Item List----------------------- -->
<?php
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<table align="center" style="font-family:Calibri">
<tr><td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt">History Data | Commission Paid</td><td width="100px"></td><td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt">Summary</td></tr>
<tr><td valign="top">
	<table>
	<tr><td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt" colspan="5">Salesman | Commission Paid</td></tr>
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" >Invoice No</th><th class="shipmentTB3">Customer</th><th class="shipmentTB3">Salesman</th><th class="shipmentTB3" >Commission</th><th class="shipmentTB3" >Invoice Status</th></tr>
	<?php
	for($i=0;$i<sizeof($his_sm_id);$i++){
		if(strlen($his_sm_cust[$i])>25) $cust_name=substr($his_sm_cust[$i],0,25).'...'; else $cust_name=$his_sm_cust[$i];
		if($his_sm_status[$i]==0){
			$sm_commission=$sm_rate; 
			$st='Completed Invoice';
		}else{
			$sm_commission=0;
			$st='Uncompleted Invoice';
		}
		$sm_commission_arr[$his_sm_sm[$i]]+=$sm_commission;
		
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td class="shipmentTB3" align="center"><a href="index.php?components='.$components.'&action=hp_active_list&invoice_no='.$his_sm_invoice[$i].'" style="text-decoration:none" >'.str_pad($his_sm_invoice[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3"><a title="'.$his_sm_cust[$i].'">'.$cust_name.'</a></td>';
		print '<td class="shipmentTB3">'.ucfirst($his_sm_sm[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($sm_commission).'</td><td class="shipmentTB3" align="right">'.$st.'</td>';
		print '</tr>';
	}
	print '</table>';
	
	
	
	
	print '<br />';
	print '<table>';
	print '	<tr><td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt" colspan="5">Recovery Agent | Commission Paid</td></tr>';
	print '<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" >Invoice No</th><th class="shipmentTB3" >Customer</th><th class="shipmentTB3">Recovery Agent</th><th class="shipmentTB3" >Commission</th><th class="shipmentTB3" >Invoice Status</th></tr>';
	for($i=0;$i<sizeof($his_rg_id);$i++){
		if(strlen($his_rg_cust[$i])>25) $cust_name=substr($his_rg_cust[$i],0,25).'...'; else $cust_name=$his_rg_cust[$i];
		if($his_rg_status[$i]==0){
			$rg_commission=$rg_rate; 
			$st='Completed Invoice';
		}else{
			$rg_commission=0;
			$st='Uncompleted Invoice';
		}
		$rg_commission_arr[$his_rg_rg[$i]]+=$rg_commission;
		
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td class="shipmentTB3" align="center"><a href="index.php?components='.$components.'&action=hp_active_list&invoice_no='.$his_rg_invoice[$i].'" style="text-decoration:none" >'.str_pad($his_rg_invoice[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3"><a title="'.$his_rg_cust[$i].'">'.$cust_name.'</a></td>';
		print '<td class="shipmentTB3">'.ucfirst($his_rg_rg[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($rg_commission).'</td><td class="shipmentTB3" align="right">'.$st.'</td>';
		print '</tr>';
	}

	print '<tr><td style="background-color:maroon; color:white;" class="shipmentTB3" colspan="8">Late Payment Collection</td></tr>';
	
	for($i=0;$i<sizeof($hra_hra_id);$i++){
		if(strlen($hra_rg_cust[$i])>25) $cust_name=substr($hra_rg_cust[$i],0,25).'...'; else $cust_name=$hra_rg_cust[$i];
		if($hra_his_status[$i]==0){
			$st='Completed Invoice';
		}else{
			$rg_commission=0;
			$st='Uncompleted Invoice';
		}
		$rg_commission_arr[$hra_rg_rg[$i]]-=$hra_rg_amo[$i];
		
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td class="shipmentTB3" align="center"><a href="index.php?components='.$components.'&action=hp_active_list&invoice_no='.$hra_invoice[$i].'" style="text-decoration:none" >'.str_pad($hra_invoice[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3"><a title="'.$hra_rg_cust[$i].'">'.$cust_name.'</a></td>';
		print '<td class="shipmentTB3">'.ucfirst($hra_rg_rg[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($hra_rg_amo[$i]).'</td><td class="shipmentTB3" align="right">'.$st.'</td>';
		print '</tr>';
	}
	?>
	</table>
</td><td></td><td valign="top">
	<table width="100%">
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3">Salesman</th><th class="shipmentTB3">Total Commission</th></tr>
	<?php
	$proceed_status1=false;
	if(sizeof($com_sm_did)==0) $proceed_status1=true;
	for($i=0;$i<sizeof($com_sm_did);$i++){
		if($sm_commission_arr[$com_sm_name[$i]]==$com_sm_amo[$i]) $proceed_status1=true;
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td class="shipmentTB3"><a href="index.php?components=report&action=hp_commission_one_user&id=29&user='.$com_sm_id[$i].'" style="text-decoration:none;">'.ucfirst($com_sm_name[$i]).'</a></td><td class="shipmentTB3" align="right">'.number_format($com_sm_amo[$i]).'</td></tr>';
	}
	?>
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3">Recovery Agent</th><th class="shipmentTB3">Total Commission</th></tr>
	<?php
	$proceed_status2=false;
	if(sizeof($com_rg_did)==0) $proceed_status2=true;
	for($i=0;$i<sizeof($com_rg_did);$i++){
		if($rg_commission_arr[$com_rg_name[$i]]==$com_rg_amo[$i]) $proceed_status2=true;
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td class="shipmentTB3"><a href="index.php?components=report&action=hp_commission_one_user&id=29&user='.$com_rg_id[$i].'" style="text-decoration:none;">'.ucfirst($com_rg_name[$i]).'</a></td><td class="shipmentTB3" align="right">'.number_format($com_rg_amo[$i]).'</td></tr>';
	}
	?>
	</table>
	
	<hr />
	
		<table width="100%">
		<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" align="left">Report No</th><th class="shipmentTB3"><?php print str_pad($hc_id, 7, "0", STR_PAD_LEFT); ?></th></tr>
		<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" align="left">Total<br />Commission</th><th class="shipmentTB3"><input type="text" id="total_com" value="<?php print number_format(array_sum($com_sm_amo) + array_sum($com_rg_amo)); ?>" disabled="disabled" style="text-align:right" /></th></tr>
		<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" align="left">Month</th><th class="shipmentTB3"><input type="month" id="month" name="month" value="<?php print $hc_month; ?>" disabled="disabled" /></th></tr>
		<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" align="left">Status</th><td class="shipmentTB3"><div id="div_status" ><?php if($proceed_status1 && $proceed_status2) print 'Calculation Okay'; else print '<span class="blink">Calculation Error</span>'; ?></div></td></tr>
		<?php
			if($proceed_status1 && $proceed_status2 && $delete_permission){ ?>
				<tr style="background-color:#EEEEEE;"><th class="shipmentTB3" colspan="2"><div id="div_gen_btn"><input type="button" value="Delete Commission Report" style="width:200px; height:50px; background-color:maroon; color:#FFFFFF" onclick="deleteCommissionReport(<?php print $hc_id; ?>)" /></div></th></tr>
		<?php 
			}
		?>
		</table>
</td></tr>
</table>

<?php
                include_once  'template/footer.php';
?>