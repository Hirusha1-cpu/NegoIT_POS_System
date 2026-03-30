<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
function setOrder($type){
	$order='';
	if($type=='sm'){
		 if(document.getElementById("sm_order").checked==true){
		 	document.getElementById("rg_order").checked==false;
		 	$order='&sm_order=1';
		 }
	}
	if($type=='rg'){
		 if(document.getElementById("rg_order").checked==true){
		 	document.getElementById("sm_order").checked==false;
		 	$order='&rg_order=1';
		 }
	}
	window.location = 'index.php?components=<?php print $components; ?>&action=hp_commission_new'+$order;
}

function setSMCommission($sm_name){
	var $sm_selected=document.getElementById('sm_selected').value;
	var $total_com=parseFloat(document.getElementById('total_com').value);
	var $one_com=parseFloat(document.getElementById('sm_amo_'+$sm_name).value);
	if(document.getElementById('sm_tik_'+$sm_name).checked){ 
		$sm_selected+=$sm_name +','+$one_com+'|'; 
		$total_com+=$one_com;
	}else{
		if($sm_selected!=''){
			$sm_select_arr=($sm_selected.slice(0, -1)).split('|');
			$sm_selected='';
			for($i=0;$i<$sm_select_arr.length;$i++){
				$sm_arr=$sm_select_arr[$i].split(',');
				if($sm_arr[0] != $sm_name)	$sm_selected+=$sm_select_arr[$i]+'|';
			}	
		}
		$total_com-=$one_com;
	}
	document.getElementById('sm_selected').value=$sm_selected;
	document.getElementById('total_com').value=$total_com;
}

function setRGCommission($rg_name){
	var $rg_selected=document.getElementById('rg_selected').value;
	var $total_com=parseFloat(document.getElementById('total_com').value);
	var $one_com=parseFloat(document.getElementById('rg_amo_'+$rg_name).value);
	if(document.getElementById('rg_tik_'+$rg_name).checked){ 
		$rg_selected+=$rg_name +','+$one_com +'|'; 
		$total_com+=$one_com;
	}else{
		if($rg_selected!=''){
			$rg_select_arr=($rg_selected.slice(0, -1)).split('|');
			$rg_selected='';
			for($i=0;$i<$rg_select_arr.length;$i++){
				$rg_arr=$rg_select_arr[$i].split(',');
				if($rg_arr[0] != $rg_name)	$rg_selected+=$rg_select_arr[$i]+'|';
			}	
		}
		$total_com-=$one_com;
	}
	document.getElementById('rg_selected').value=$rg_selected;
	document.getElementById('total_com').value=$total_com;
}

function validateCommissionReport(){
	$month=document.getElementById('month').value;
	$sm_selected=document.getElementById('sm_selected').value;
	$out=true;
	
	if((document.getElementById('sm_selected').value=='') && (document.getElementById('rg_selected').value=='')){ 
		document.getElementById("div_status").innerHTML='<div class="blink">Please Select Salesmans<br />Or Recovery Agents</div>';
		$out=false;
	}
	
	if($month==''){ 
		document.getElementById("div_status").innerHTML='<div class="blink">Please Select the month</div>';
		$out=false;
	}
	
	if($out){
		document.getElementById("div_status").innerHTML='Okay to Procced';
		document.getElementById("div_gen_btn").innerHTML=document.getElementById("loading").innerHTML;
		return true;
	}else{
		return false;
	}
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
<tr><td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt">Completed Bills | Commission Not Paid</td><td width="100px"></td><td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt">Summary</td></tr>
<tr><td valign="top">
	<table>
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" rowspan="2">Invoice No</th><th class="shipmentTB3" rowspan="2">Customer</th><th class="shipmentTB3" colspan="2">Salesman</th><th class="shipmentTB3" colspan="2">Recovery Agent</th><th class="shipmentTB3" rowspan="2">Recovery Total</th><th class="shipmentTB3" rowspan="2">Invoice Total</th></tr>
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" >Name<br /><span style="font-size:8pt"><input type="checkbox" id="sm_order" onclick="setOrder('sm')" <?php if($sm_order) print 'checked="checked"'; ?> /> Order</span></th><th class="shipmentTB3">Commission<br /><span style="font-size:8pt"><?php print $sm_rate; ?></span></th><th class="shipmentTB3">Name<br /><span style="font-size:8pt"><input type="checkbox" id="rg_order" onclick="setOrder('rg')" <?php if($rg_order) print 'checked="checked"'; ?> /> Order</span></th><th class="shipmentTB3">Commission<br /><span style="font-size:8pt"><?php print $rg_rate; ?></span></th></tr>
	<?php
	// for($i=0;$i<sizeof($his_id);$i++){
	// 	if(strlen($his_cust[$i])>25) $cust_name=substr($his_cust[$i],0,25).'...'; else $cust_name=$his_cust[$i];
	// 	if($his_sm_pay[$i]==0) $sm_commission=$sm_rate; else $sm_commission=0;
	// 	if($his_rg_pay[$i]==0) $rg_commission=$rg_rate; else $rg_commission=0;
	// 	$sm_commission_arr[$his_sm[$i]]+=$sm_commission;
	// 	$rg_commission_arr[$his_rg[$i]]+=$rg_commission;
		
	// 	if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
	// 	print '<tr bgcolor="'.$color.'"><td class="shipmentTB3" align="center"><a href="index.php?components='.$components.'&action=hp_active_list&invoice_no='.$his_inv[$i].'" style="text-decoration:none" >'.str_pad($his_inv[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3"><a title="'.$his_cust[$i].'">'.$cust_name.'</a></td>';
	// 	print '<td class="shipmentTB3">'.ucfirst($his_sm[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($sm_commission).'</td>';
	// 	print '<td class="shipmentTB3">'.ucfirst($his_rg[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($rg_commission).'</td>';
	// 	print '<td class="shipmentTB3" align="right">'.number_format($his_hp_total[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($his_bill_total[$i]).'</td>';
	// 	print '</tr>';
	// }
	if(isset($his_id) && is_array($his_id) && count($his_id) > 0) {
    for($i=0; $i < count($his_id); $i++) {
        if(strlen($his_cust[$i])>25) $cust_name=substr($his_cust[$i],0,25).'...'; else $cust_name=$his_cust[$i];
        if($his_sm_pay[$i]==0) $sm_commission=$sm_rate; else $sm_commission=0;
        if($his_rg_pay[$i]==0) $rg_commission=$rg_rate; else $rg_commission=0;
        $sm_commission_arr[$his_sm[$i]]+=$sm_commission;
        $rg_commission_arr[$his_rg[$i]]+=$rg_commission;
        
        if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
        print '<tr bgcolor="'.$color.'"><td class="shipmentTB3" align="center"><a href="index.php?components='.$components.'&action=hp_active_list&invoice_no='.$his_inv[$i].'" style="text-decoration:none" >'.str_pad($his_inv[$i], 7, "0", STR_PAD_LEFT).'</a> Nos<td class="shipmentTB3"><a title="'.$his_cust[$i].'">'.$cust_name.'</a> Nos';
        print '<td class="shipmentTB3">'.ucfirst($his_sm[$i]).' Nos<td class="shipmentTB3" align="right">'.number_format($sm_commission).' Nos';
        print '<td class="shipmentTB3">'.ucfirst($his_rg[$i]).' Nos<td class="shipmentTB3" align="right">'.number_format($rg_commission).' Nos';
        print '<td class="shipmentTB3" align="right">'.number_format($his_hp_total[$i]).' Nos<td class="shipmentTB3" align="right">'.number_format($his_bill_total[$i]).' Nos';
        print '</tr>';
    }
	} else {
		// Display a message when no data exists
		print '<tr><td colspan="8" align="center">No records found</td></tr>';
	}
	print '</table>';
	
	print '<br />';
	print '<table>';
	print '<tr><td style="background-color:maroon; color:white;" class="shipmentTB3" colspan="8">Late Payment Collection</td></tr>';
	print '<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" >Invoice No</th><th class="shipmentTB3" >Customer</th><th class="shipmentTB3" >Salesman</th><th class="shipmentTB3">Recovery Agent</th><th class="shipmentTB3" >Instalment Date</th><th class="shipmentTB3" >Instalment Amount</th><th class="shipmentTB3" >Deduction Amount</th></tr>';
	for($i=0;$i<sizeof($deduct_his_id);$i++){
		if(strlen($deduct_his_cust[$i])>25) $cust_name=substr($deduct_his_cust[$i],0,25).'...'; else $cust_name=$deduct_his_cust[$i];
		//$sm_commission_arr[$deduct_his_sm[$i]]+=$sm_late_deduct;
		$rg_commission_arr[$deduct_his_rg[$i]]-=$deduct_py_amount[$i];
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td class="shipmentTB3" align="center"><a href="index.php?components='.$components.'&action=hp_active_list&invoice_no='.$deduct_his_inv[$i].'" style="text-decoration:none" >'.str_pad($deduct_his_inv[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3"><a title="'.$deduct_his_cust[$i].'">'.$cust_name.'</a></td>';
		print '<td class="shipmentTB3">'.ucfirst($deduct_his_sm[$i]).'</td><td class="shipmentTB3">'.ucfirst($deduct_his_rg[$i]).'</td>';
		print '<td class="shipmentTB3" align="center">'.$deduct_inst_date[$i].'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format($deduct_py_amount[$i]).'</td><td class="shipmentTB3" align="right">-'.number_format($deduct_py_amount[$i]).'</td>';
		print '</tr>';
	}
	?>
	</table>
</td><td></td><td valign="top">
	<table width="100%">
	<tr style="background-color:#467898; color:white;"><td></td><th class="shipmentTB3">Salesman</th><th class="shipmentTB3">Total Commission</th></tr>
	<?php
	$proceed_status=true;
	for($i=0;$i<sizeof($sm_list);$i++){
		if($sm_commission_arr[$sm_list[$i]]<0) $proceed_status=false;
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td><input type="checkbox" id="sm_tik_'.$sm_list[$i].'" onclick="setSMCommission(\''.$sm_list[$i].'\')" /></td><td class="shipmentTB3">'.ucfirst($sm_list[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($sm_commission_arr[$sm_list[$i]]).'<input type="hidden" id="sm_amo_'.$sm_list[$i].'" value="'.$sm_commission_arr[$sm_list[$i]].'" /></td></tr>';
	}
	?>
	<tr style="background-color:#467898; color:white;"><td></td><th class="shipmentTB3">Recovery Agent</th><th class="shipmentTB3">Total Commission</th></tr>
	<?php
	for($i=0;$i<sizeof($rg_list);$i++){
		if($rg_commission_arr[$rg_list[$i]]<0) $proceed_status=false;
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td><input type="checkbox" id="rg_tik_'.$rg_list[$i].'" onclick="setRGCommission(\''.$rg_list[$i].'\')" /></td><td class="shipmentTB3">'.ucfirst($rg_list[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($rg_commission_arr[$rg_list[$i]]).'<input type="hidden" id="rg_amo_'.$rg_list[$i].'" value="'.$rg_commission_arr[$rg_list[$i]].'" /></td></tr>';
	}
	?>
	</table>
	
	<hr />
	
	<form action="index.php?components=<?php print $components; ?>&action=hp_generate_commission" method="post" onsubmit="return validateCommissionReport()">
		<input type="hidden" id="sm_selected" name="sm_selected" value="" />
		<input type="hidden" id="rg_selected" name="rg_selected" value="" />
		<table width="100%">
		<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" align="left">Total<br />Commission</th><th class="shipmentTB3"><input type="text" id="total_com" value="0" readonly="readonly" style="text-align:right" /></th></tr>
		<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" align="left">Month</th><th class="shipmentTB3"><input type="month" id="month" name="month" value="<?php print date("Y-m",time()); ?>" /></th></tr>
		<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" align="left">Status</th><td class="shipmentTB3"><div id="div_status" ><?php if($proceed_status) print 'Okay to Procced'; else print 'Error: Commission<br />should be larger than 0'; ?></div></td></tr>
		<?php
			if($proceed_status){ ?>
				<tr style="background-color:#EEEEEE;"><th class="shipmentTB3" colspan="2"><div id="div_gen_btn"><input type="submit" value="Generate Commission Report" style="width:250px; height:50px;" /></div></th></tr>
			<?php }
		?>
		</table>
	</form>
</td></tr>
</table>

<?php
                include_once  'template/footer.php';
?>