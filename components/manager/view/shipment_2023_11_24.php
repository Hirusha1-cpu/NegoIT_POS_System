<?php
                include_once  'template/header.php';
                if(isset($_GET['id'])) $shipment_no=$_GET['id']; else $shipment_no='';
?>
<script type="text/javascript">
function validateShipDisc(){
	var $count=0;
	var $msg="Please Fully Fill the Form";
    if(document.getElementById('date_dis').value=='') $count++;
    if(document.getElementById('ref_dis').value=='') $count++;
    if(document.getElementById('amount_dis').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function validateShipPay(){
	var $count=0;
	var $msg="Please Fully Fill the Form";
    if(document.getElementById('date_pay').value=='') $count++;
    if(document.getElementById('ref_pay').value=='') $count++;
    if(document.getElementById('from_account').value=='') $count++;
    if(document.getElementById('amount_pay').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function deleteShipPay(id){
	var shipment_no=document.getElementById('shipment_no').value;
	var pass=document.getElementById('pass_'+id).value;
	if(pass==''){
		window.alert('Please Enter the Password');
	}else{
	var check= confirm("Do you want to Delete Record?");
	 if (check== true)
		window.location = 'index.php?components=<?php print $components; ?>&action=delete_ship_payment&shipment_no='+shipment_no+'&pass='+pass+'&id='+id;
	}
}

</script>
<!-- ------------------Item List----------------------- -->
<!-- ----------------------------------------Print Start----------------------------------------------------------------------------------------- -->
<?php
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?>
<input type="hidden" id="shipment_no" value="<?php print $shipment_no; ?>" />

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Shipment Details</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0;" width="100px">Date</td><td align="center" width="150px"><?php print dateNow(); ?></td></tr>
	</table><hr />
</div>
<div id="print" style="display:none">
	<table border="1" cellspacing="0">
	<tr bgcolor="#EEEEEE"><td width="170px" >&nbsp;&nbsp;&nbsp;<strong>Shipment No</strong></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php print str_pad($sm1_id, 7, "0", STR_PAD_LEFT); ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<tr bgcolor="#EEEEEE"><td width="170px" >&nbsp;&nbsp;&nbsp;Shipment Date</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print $sm1_date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<tr bgcolor="#EEEEEE"><td width="170px" >&nbsp;&nbsp;&nbsp;Supplier</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print $sm1_sup; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<tr bgcolor="#EEEEEE"><td width="170px" >&nbsp;&nbsp;&nbsp;Invoice No</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print $sm1_inv; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<tr bgcolor="#EEEEEE"><td width="170px" >&nbsp;&nbsp;&nbsp;Invoice Date</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print $sm1_invdate; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<tr bgcolor="#EEEEEE"><td width="170px" >&nbsp;&nbsp;&nbsp;Invoice Due Date</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print $sm1_invdue; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<tr bgcolor="#EEEEEE"><td width="170px" >&nbsp;&nbsp;&nbsp;Invoice Amount</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print number_format($sm1_amount); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	</table>
<br />
<br />
	<table border="1" cellspacing="0">
	<tr bgcolor="#CCCCCC"><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ITEM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EARNINGS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SOLD %&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>
	<?php
	$total_cost=0;
	$total_earnings=0;
	for($i=0;$i<sizeof($ms2_des);$i++){
	print '<tr bgcolor="#EEEEEE"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$ms2_des[$i].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($ms2_qty[$i]*$ms2_c_price[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		if($ms2_unic[$i]==1){
			print '<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($sm3_soldprice[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$sm3_soldqty[$i].' / '.$ms2_qty[$i].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
			$total_earnings+=$sm3_soldprice[$i];
		}else print '<td></td><td></td></tr>';
		$total_cost+=($ms2_qty[$i]*$ms2_c_price[$i]);
	}
	print '<tr bgcolor="#CCCCCC"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>TOTAL</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.number_format($total_cost).'</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.number_format($total_earnings).'</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td></tr>';
	?>
	</table>
</div>
<!-- ----------------------------------------Print End----------------------------------------------------------------------------------------- -->
<br />
<table align="center" width="90%" style="font-family:Calibri" border="0" >
<tr><td width="50%" style="vertical-align:top"><br />
<div style="background-color:#DDDDDD;  border-radius: 15px; padding-left:10px; padding-right:10px; padding-top:10px; padding-bottom:10px">
<div style="background-color:#FAFAFA;  border-radius: 15px; padding-left:10px; padding-right:10px; padding-top:10px; padding-bottom:10px">
	<table align="center">
	<tr bgcolor="#EEEEEE"><td class="shipmentTB1">Shipment No</td><td  class="shipmentTB2"><?php print str_pad($sm1_id, 7, "0", STR_PAD_LEFT); ?></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB1">Shipment Date</td><td  class="shipmentTB2"><?php print $sm1_date; ?></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB1">Supplier</td><td  class="shipmentTB2"><?php print $sm1_sup; ?></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB1">Invoice No</td><td  class="shipmentTB2"><?php print $sm1_inv; ?></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB1">Invoice Date</td><td  class="shipmentTB2"><?php print $sm1_invdate; ?></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB1">Invoice Due Date</td><td  class="shipmentTB2"><?php print $sm1_invdue; ?></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB1">Invoice Amount</td><td  class="shipmentTB2"><?php print number_format($sm1_amount); ?></td></tr>
	</table>
	<br />
	<br />
	<table align="center">
	<tr bgcolor="#CCCCCC"><th>Item</th><th>Cost</th><th>Earnings</th><th>Sold %</th></tr>
	<?php
	$total_cost=0;
	$total_earnings=0;
	for($i=0;$i<sizeof($ms2_des);$i++){
	print '<tr bgcolor="#EEEEEE"><td class="shipmentTB2">'.$ms2_des[$i].'</td><td class="shipmentTB2" align="right">'.number_format($ms2_qty[$i]*$ms2_c_price[$i]).'</td>';
		if($ms2_unic[$i]==1){
			print '<td class="shipmentTB2" align="right">'.number_format($sm3_soldprice[$i]).'</td><td class="shipmentTB2" align="right">'.$sm3_soldqty[$i].' / '.$ms2_qty[$i].'</td></tr>';
			$total_earnings+=$sm3_soldprice[$i];
		}else print '<td></td><td></td></tr>';
		$total_cost+=($ms2_qty[$i]*$ms2_c_price[$i]);
	}
	print '<tr bgcolor="#CCCCCC"><td class="shipmentTB2"><strong>Total</strong></td><td class="shipmentTB2" align="right"><strong>'.number_format($total_cost).'</strong></td><td class="shipmentTB2" align="right"><strong>'.number_format($total_earnings).'</strong></td><td></td></tr>';
	print '<tr><td colspan="4"><br /></td></tr>';
	
	for($i=0;$i<sizeof($sp_id);$i++){
		if($sp_type[$i]==1){ $type='Discount'; $color1='#467898'; }
		if($sp_type[$i]==2){ $type='Payment'; $color1='#987848'; }
		print '<tr bgcolor="'.$color1.'" ><td class="shipmentTB2"><a style="color:white; text-decoration:none" href="index.php?components=fin&action=one_journal&id='.$sp_journal[$i].'" title="Click Here to see the Journal Entry">'.$type.'</a></td><td class="shipmentTB2" align="right" ><a style="color:white; text-decoration:none" href="index.php?components=fin&action=one_journal&id='.$sp_journal[$i].'" title="Ref: '.$sp_ref[$i].'">'.number_format((-1*$sp_amount[$i])).'</a></td><td class="shipmentTB2" align="right"><a style="color:white; text-decoration:none" href="index.php?components=fin&action=one_journal&id='.$sp_journal[$i].'" title="Record Added Date: '.$sp_sys_date[$i].'">'.$sp_pay_date[$i].'</a></td><td class="shipmentTB2" style="color:white; text-decoration:none"><input type="text" placeholder="Password" id="pass_'.$sp_id[$i].'" style="width:60px" /> <a style="cursor:pointer" onclick="deleteShipPay('.$sp_id[$i].')"><img src="images/action_delete.gif" /></a></td></tr>';
		$total_cost-=$sp_amount[$i];
	}
	print '<tr bgcolor="#CCCCCC"><td class="shipmentTB2"><strong>Total Balance</strong></td><td class="shipmentTB2" align="right"><strong>'.number_format($total_cost).'</strong></td><td class="shipmentTB2" align="right"></td><td></td></tr>';
	?>
	</table>
	<br />
	<?php if(($sm1_id!='')&&($total_cost!=0) ){ ?>
		<table align="center">
		<tr><td valign="top">
			<form action="index.php?components=<?php print $components; ?>&action=add_ship_payment&case=dis&id=<?php print $shipment_no; ?>" method="post" onsubmit="return validateShipDisc()">
			<table align="center" bgcolor="#EEEEEE">
			<tr><td class="shipmentTB4" colspan="2" bgcolor="#467898" align="center" style="color:white">Shipment Post Discount/Commission</td></tr>
			<tr><td class="shipmentTB3" bgcolor="#CCCCCC">Date</td><td><input type="date" id="date_dis" name="date" style="width:125px" /></td></tr>
			<tr><td class="shipmentTB3" bgcolor="#CCCCCC">Ref No.</td><td><input type="text" id="ref_dis" name="ref" style="width:125px" /></td></tr>
			<tr><td class="shipmentTB3" bgcolor="#CCCCCC">Amount</td><td><input type="text" id="amount_dis" name="amount" style="width:125px; text-align:right" /></td></tr>
			<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><input type="submit" value="Add" /></td></tr>
			</table>
			</form>
		</td><td width="10px"></td><td>
			<form action="index.php?components=<?php print $components; ?>&action=add_ship_payment&case=pay&id=<?php print $shipment_no; ?>" method="post" onsubmit="return validateShipPay()">	
			<table align="center" bgcolor="#EEEEEE">
			<tr><td class="shipmentTB4" colspan="2" bgcolor="#987848" align="center" style="color:white">Shipment Payment</td></tr>
			<tr><td class="shipmentTB3" bgcolor="#CCCCCC">Date</td><td><input type="date" id="date_pay" name="date" style="width:125px" /></td></tr>
			<tr><td class="shipmentTB3" bgcolor="#CCCCCC">Ref No.</td><td><input type="text" id="ref_pay" name="ref" style="width:125px" /></td></tr>
			<tr><td class="shipmentTB3" bgcolor="#CCCCCC">From Account</td><td>
				<select id="from_account" name="from_account" >
					<option value="">-SELECT-</option>
					<?php for($i=0;$i<sizeof($acc_id);$i++){
						print '<option value="'.$acc_id[$i].'">'.$acc_name[$i].'</option>';
					} ?>
					</select>
			</td></tr>
			<tr><td class="shipmentTB3" bgcolor="#CCCCCC">Amount</td><td><input type="text" id="amount_pay" name="amount" style="width:125px; text-align:right" /></td></tr>
			<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><input type="submit" value="Pay" /></td></tr>
			</table>
			</form>
		</td></tr>
		</table>
	<?php } ?>
	<br />
	<table align="center"><tr><td align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px;">
		<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
		<img src="images/print.png" alt="icon" /><br />
		Print
		</span></a>
	</div>
	</td></tr></table>
</div>
</div>
</td><td style="vertical-align:top">
<table align="center" width="86%" style="background-color:#EEEEEF; border-radius: 5px; padding-left:10px; padding-right:10px"><tr><td align="center">List of Last 50 Shipments</td></tr></table>
	<form method="post" action="index.php?components=<?php print $components; ?>&action=shipment">
	<table align="center">
	<tr bgcolor="#CCCCCC"><th>Shipment No</th><th>Invoice Date</th><th>Invoice No</th><th>Suplier</th></tr>
	<tr bgcolor="#CCCCCC">
	<th><input type="text" name="ship_no" placeholder="Search.." class="shipmentTB3" style="width:80px" value="<?php print $filter_shipno; ?>" /></th>
	<th><input type="text" name="inv_date" placeholder="Search.." class="shipmentTB3" style="width:80px" value="<?php print $filter_invdate; ?>" /></th>
	<th><input type="text" name="inv_no" placeholder="Search.." class="shipmentTB3" style="width:80px" value="<?php print $filter_invno; ?>" /></th>
	<th><input type="text" name="inv_sup" placeholder="Search.." class="shipmentTB3" style="width:80px" value="<?php print $filter_invsup; ?>" /></th></tr>
	<?php for($i=0;$i<sizeof($sm_id);$i++){
			if($sm_fully_paid[$i]==1) $color1='#55CC55'; else $color1='#EEEEEE';
		print '<tr bgcolor="'.$color1.'"><td style="padding-left:10px; padding-right:10px" align="center"><a href="index.php?components='.$components.'&action=shipment&id='.$sm_id[$i].'">'.str_pad($sm_id[$i], 7, "0", STR_PAD_LEFT).'</a></td>
		<td style="padding-left:10px; padding-right:10px" align="center">'.$sm_inv_date[$i].'</td>
		<td style="padding-left:10px; padding-right:10px" align="center">'.$sm_inv_no[$i].'</td>
		<td style="padding-left:10px; padding-right:10px">'.$su_name[$i].'</td>
		</tr>';
	}	
	?>
	</table>
	<input type="submit" style="display:none"/>
	</form>
</td></tr>
</table>

<?php
                include_once  'template/footer.php';
?>