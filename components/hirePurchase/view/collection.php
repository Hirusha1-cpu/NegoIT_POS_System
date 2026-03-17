<?php
                include_once  'template/header.php';
                $recovery_agent=$_GET['rag_id'];
?>
<script type="text/javascript">
const zeroPad = (num, places) => String(num).padStart(places, '0');

function setFuture($value){
	var rag_id=document.getElementById("rag_id").value;
	window.location = 'index.php?components=hire_purchase&action=collection&future='+$value+'&rag_id='+rag_id;
}

function setRecoveryAgent(rag_id){
	window.location = 'index.php?components=hire_purchase&action=collection&rag_id='+rag_id;
}
</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<input type="hidden" id="rag_id" value="<?php print $recovery_agent; ?>" />
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?>

<table align="center" style="font-family:Calibri; background-color:#467898; color:white;">
<tr><td width="150px" align="center">Recovery Agent</td><td>
	<select onchange="setRecoveryAgent(this.value)" style="font-size:16" >
		<option value="">-SELECT-</option>
		<?php 
		for($i=0;$i<sizeof($rag_id);$i++){
			if($rag_id[$i]==$recovery_agent) $select='selected="selected"'; else $select='';
			print '<option value="'.$rag_id[$i].'" '.$select.' >'.ucfirst($rag_name[$i]).'</option>';
		} ?>
		</select>
</td></tr>
</table>

<table align="center">
<tr style="font-family:Calibri; background-color:#800000; color:white"><th>Due Payments</th><th></th><th>Upcomming Payments &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<select onchange="setFuture(this.value)" >
	<option <?php if($future=="UpComming") print 'selected="selected"'; ?> >UpComming</option>
	<option <?php if($future=="Today") print 'selected="selected"'; ?> >Today</option>
	</select>

</th></tr>
<tr><td valign="top">
	<table style="font-family:Calibri; font-size:12pt">
	<tr style="background-color:#467898; color:white;"><th></th><th>Invoice No</th><th>Instalment Type</th><th>Due Date</th><th>Instalment Amount</th><th>Remaining Amount</th></tr>
	<?php 
	for($i=0;$i<sizeof($downcol_inv);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'">';
		print '<td class="shipmentTB3"><input type="checkbox" /></td><td class="shipmentTB3"><a href="index.php?components=hire_purchase&action=home&invoice_no='.$downcol_inv[$i].'" style="text-decoration:none">'.str_pad($downcol_inv[$i], 7, "0", STR_PAD_LEFT).'</a></td>';
		print '<td class="shipmentTB3" align="center">'.$downcol_type[$i].'</td>';
		print '<td class="shipmentTB3" align="center">'.$downcol_inst[$i].'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format($downcol_amo[$i]).'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format($downcol_remaining[$i]).'</td>';
		print '</tr>';
	}
	?>
	</table>
</td><td width="100px"></td><td valign="top">
	<table style="font-family:Calibri; font-size:12pt">
	<tr style="background-color:#467898; color:white;"><th></th><th>Invoice No</th><th>Instalment Type</th><th>Due Date</th><th>Instalment Amount</th><th>Remaining Amount</th></tr>
	<?php 
	for($i=0;$i<sizeof($upcol_inv);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'">';
		print '<td class="shipmentTB3"><input type="checkbox" /></td><td class="shipmentTB3"><a href="index.php?components=hire_purchase&action=home&invoice_no='.$upcol_inv[$i].'" style="text-decoration:none">'.str_pad($upcol_inv[$i], 7, "0", STR_PAD_LEFT).'</a></td>';
		print '<td class="shipmentTB3" align="center">'.$upcol_type[$i].'</td>';
		print '<td class="shipmentTB3" align="center">'.$upcol_inst[$i].'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format($upcol_amo[$i]).'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format($upcol_remaining[$i]).'</td>';
		print '</tr>';
	}
	?>
	</table>
</td></tr>
</table>	




<?php
                include_once  'template/footer.php';
?>