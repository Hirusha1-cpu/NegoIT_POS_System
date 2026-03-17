<?php
	if($sm_action){
		if($sm_status==0)	$sm_action0='<div id="div_delete"><input type="button" value="Request for Delete" onclick="authDeleteShipment('.$shipment_no.')" style="width:130px; height:40px; background-color:#CC0000; color:white; font-weight:bold" /></div>';
		if($sm_status==1)	$sm_action0='<span style="color:#0033CC; font-weight:bold; font-size:14pt">Requested For Deletion</span>';
		if($sm_status==2)	$sm_action0='<div id="div_delete"><input type="button" value="Delete Shipment" onclick="deleteShipment('.$shipment_no.')" style="width:120px; height:40px; background-color:#CC0000; color:white; font-weight:bold" /></div>';
	}else{
		if($sm_status==3)	$sm_action0='<span style="color:red; font-weight:bold; font-size:14pt">Deleted</span>';
		else $sm_action0='<span style="color:#0033CC; font-weight:bold; font-size:14pt">Locked</span>';
	}
?>
	<div id="loading" style="display:none"><img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please Wait</span></div>
	<div id="printheader">
	<table align="center" bgcolor="#E5E5E5" border="1" bordercolor="white" cellspacing="0" style="font-family:Calibri">
		<tr><td align="center" colspan="2" style="font-size:16pt; color:navy; font-weight:bold">Shipment Details</td></tr>
		<tr><td width="200px" style="padding-left:10px">Date of Shipment</td><td width="100px" style="padding-left:10px"><?php print $sm_date; ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Shipment No</td><td width="100px" style="padding-left:10px"><?php print str_pad($shipment_no, 7, "0", STR_PAD_LEFT); ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Location</td><td width="100px" style="padding-left:10px"><?php print ucfirst($ins_store[0]); ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Placed By</td><td width="100px" style="padding-left:10px"><?php print ucfirst($ins_addedby[0]); ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Supplier</td><td width="100px" style="padding-left:10px"><?php print ucfirst($sm_supplier); ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Invoice No</td><td width="100px" style="padding-left:10px"><?php print $sm_invoice_no; ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Invoice Date</td><td width="100px" style="padding-left:10px"><?php print $sm_invoice_date; ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Invoice Due</td><td width="100px" style="padding-left:10px"><?php print $sm_invoice_duedate; ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Shipment Type</td><td width="100px" style="padding-left:10px"><?php print $sm_unic; ?></td></tr>
		<tr><td width="200px" style="padding-left:10px">Action/Status</td><td width="100px" align="center"><?php print $sm_action0; ?></td></tr>
	</table>
	</div>
	<table align="center" bgcolor="#E5E5E5" border="1" bordercolor="white" cellspacing="0" style="font-family:Calibri">
		<tr><td width="200px" style="padding-left:10px; background-color:#6699FF;" align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
			<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
			<img src="images/print.png" alt="icon" />
			</span></a>
			</div>
		</td><td width="100px" align="center" style="background-color:#6699FF;">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:90px;">
				<?php print '<a class="shortcut-button" href="index.php?components='.$components.'&action=export_shipment&shipment_no='.$_GET['shipment_no'].'"><span style="text-decoration:none; font-family:Arial; color:navy;">'; ?>
				<img src="images/excel.jpg" style="width:50px" alt="icon" />
				</span></a>
			</div>
		</td></tr>
	</table>