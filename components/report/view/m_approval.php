<?php
                include_once  'template/m_header.php';
?>
	<script src="js/zigo.js"></script>

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
		<table style="font-family:Calibri" >
		<tr><td colspan="7" align="center" style="font-size:14pt; color:navy; font-weight:bold">Pending Loan Approval</td></tr>
		<tr style="background-color:#467898;color :white;" ><th class="shipmentTB3">Employee</th><th class="shipmentTB3">Loan Amount</th><th class="shipmentTB3">Duration</th><th class="shipmentTB3">Start Date</th><th class="shipmentTB3">End Date</th><th class="shipmentTB3">Paid Off Amount</th><th class="shipmentTB3">Action</th></tr>
		<?php for($i=0;$i<sizeof($loan_id);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr bgcolor="'.$color.'" ><td class="shipmentTB3"><a href="index.php?components=fin&action=loan_one&id='.$loan_id[$i].'" style="text-decoration:none">'.ucfirst($loan_emp[$i]).'</a></td><td class="shipmentTB3" align="right">'.number_format($loan_amount[$i],2).'</td><td class="shipmentTB3" align="right">'.$loan_duration[$i].'</td><td class="shipmentTB3" align="center">'.$loan_start[$i].'</td><td class="shipmentTB3" align="center">'.$loan_end[$i].'</td><td class="shipmentTB3" align="right">'.number_format($loan_paidoff[$i],2).'</td><td class="shipmentTB3"><input type="button" value="Approve" onclick="setLoanStatus('."'$loan_id[$i]','2'".')" />&nbsp;&nbsp;&nbsp;<input type="button" value="Reject" onclick="setLoanStatus('."'$loan_id[$i]','3'".')" /></td></tr>';
		} ?>
		</table>
		<hr />
		<table style="font-family:Calibri" >
		<tr><td colspan="4" align="center" style="font-size:14pt; color:navy; font-weight:bold">Pending Shipment Deletion Approval</td></tr>
		<tr style="background-color:#467898;color :white;" ><th class="shipmentTB3">Shipment No</th><th class="shipmentTB3">Shipment Date</th><th class="shipmentTB3">Submited By</th><th class="shipmentTB3">Action</th></tr>
		<?php for($i=0;$i<sizeof($ship_id);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr bgcolor="'.$color.'" ><td class="shipmentTB3" align="center"><a href="index.php?components=inventory&action=one_shipment&shipment_no='.$ship_id[$i].'" style="text-decoration:none">'.str_pad($ship_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3" align="center">'.$ship_date[$i].'</td><td class="shipmentTB3">'.ucfirst($ship_submit_by[$i]).'</td><td class="shipmentTB3"><input type="button" value="Approve" onclick="setShipmentStatus('."'$ship_id[$i]','2'".')" />&nbsp;&nbsp;&nbsp;<input type="button" value="Reject" onclick="setShipmentStatus('."'$ship_id[$i]','0'".')" /></td></tr>';
		} ?>
		</table>
	</div></td><td></td><td></td></tr>
	</div>	
	</div>	

  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>