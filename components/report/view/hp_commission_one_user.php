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

<div id="printheader" style="display:none" >
<!-- 	
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1> 
	<hr />
-->
</div>

<div id="print" >
<table align="center" style="font-family:Calibri" border="1" cellspacing="0">
<tr><td>
	<table width="100%">
		<tr><td class="shipmentTB3" width="50px"><strong>Employee</strong></td><td class="shipmentTB3" width="100px">: <?php print ucfirst($com_sm_name[0]); ?></td><td></td><td class="shipmentTB3" width="160px"><strong>Commission Report No</strong></td><td class="shipmentTB3" width="100px">: <?php print str_pad($hc_id, 7, "0", STR_PAD_LEFT); ?></td></tr>
		<tr><td class="shipmentTB3"><strong></strong></td><td class="shipmentTB3"></td><td></td><td class="shipmentTB3"><strong>Report Month</strong></td><td class="shipmentTB3">: <?php print $hc_month; ?></td></tr>
	</table>
</td></tr>
<tr><td valign="top">
	<table  width="100%" border="1" cellspacing="0">
	<tr><td align="center" class="shipmentTB4" style="font-size:14pt; color:white; background-color:black; -webkit-print-color-adjust: exact;" colspan="5">Sales Commission</td></tr>
	<tr style="color:white; background-color:grey; -webkit-print-color-adjust: exact;"><th class="shipmentTB3" >Invoice No</th><th class="shipmentTB3">Customer</th><th class="shipmentTB3">Salesman</th><th class="shipmentTB3" >Commission</th><th class="shipmentTB3" >Invoice Status</th></tr>
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
	print '<table width="100%">';
	print '	<tr><td align="center" class="shipmentTB4" style="font-size:14pt; color:white; background-color:black; -webkit-print-color-adjust: exact;" colspan="5">Recovery Commission</td></tr>';
	print '<tr style="color:white; background-color:grey; -webkit-print-color-adjust: exact;"><th class="shipmentTB3" >Invoice No</th><th class="shipmentTB3" >Customer</th><th class="shipmentTB3">Recovery Agent</th><th class="shipmentTB3" >Commission</th><th class="shipmentTB3" >Invoice Status</th></tr>';
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

	print '<tr><td style="background-color:maroon; color:white; -webkit-print-color-adjust: exact;" class="shipmentTB3" colspan="8">Late Payment Collection</td></tr>';
	
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
</td>
</table>
</div>


<br />
	<table align="center">
	<tr><td align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
		<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
		<img src="images/print.png" alt="icon" /><br />
		Print
		</span></a>
	</div>
	</td></tr>
	</table>

<?php
                include_once  'template/footer.php';
?>