<?php
                include_once  'template/header.php';
?>
	
	<script type="text/javascript">
	function updateDisc($bill_id){
		$status=document.getElementById('status').value;
		$bill_no=document.getElementById('bill_no').value;
		$new_discount=document.getElementById('discount_'+$bill_id).value;
		if($status=='Deleted'){
			window.alert('This Invoice is a Deleted Invoice. You cannot Edit this !');
		}else{
			window.location = 'index.php?components=settings&action=bill_update&bill_no='+$bill_no+'&bill_id='+$bill_id+'&new_discount='+$new_discount;
		}
	}	
	</script>
<!-- ------------------Item List----------------------- -->
<table align="center" style="font-size:11pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
	}
?></td></tr></table>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Unlocked Bills</h2>
</div>
<form method="get" action="index.php">
<input type="hidden" name="components" value="settings" />
<input type="hidden" name="action" value="bill_edit" />
<table align="center" bgcolor="#EEEEEE" style="font-size:12pt; font-family:Calibri">
	<tr><td width="100px" height="50px"></td><td style="color:navy"><strong>Invoice No</strong></td><td width="150px" align="center"><input type="number" name="bill_no" id="bill_no" value="<?php if(isset($_GET['bill_no'])) print $bill_no; ?>" style="width:100px; text-align:right;" /></td><td><input type="submit" value="Search" /></td><td width="100px"></td></tr>
</table>
</form>
<br>
<div id="print">
<?php if(isset($_GET['bill_no'])){ ?>
<input type="hidden" id="status" value="<?php print $bm_status; ?>" />
	<table align="center" style="font-size:12pt; font-family:Calibri" width="600px"><tr><td>
		<table width="100%" align="center" bgcolor="#F1F1F1">
		<tr><td class="shipmentTB3">Shop</td><td><?php print $st_name; ?></td><td align="right" class="shipmentTB3">INVOICE # [<?php print  str_pad($bill_no, 7, "0", STR_PAD_LEFT); ?> ]</td></tr>
		<tr><td class="shipmentTB3">Salesman</td><td><?php print ucfirst($up_name); ?></td><td align="right" class="shipmentTB3">Date : <?php print $bm_date; ?></td></tr>
		<tr><td class="shipmentTB3">Customer</td><td><?php print ucfirst($cu_name); ?></td><td align="right"></td></tr>
		<tr><td class="shipmentTB3">Bill Status</td><td><?php print $bm_status; ?></td><td align="right"></td></tr>
		<tr><td class="shipmentTB3">Bill Lock</td><td><?php print $bm_lock; ?></td><td align="right"></td></tr>
		<tr><td class="shipmentTB3">SMS</td><td><?php print $bm_sms; ?></td><td align="right"></td></tr>
		</table>
		
			<table align="center" width="100%" border="1" cellspacing="0" >
			<tr style="font-family:Arial; font-size:10pt"><th width="40px">QTY</th><th>DESCRIPTION</th><th width="40px">UNIT <br />PRICE</th><th width="135px" >DISCOUNT</th><th width="40px" >SOLD<br />PRICE</th><th width="60px" >TOTAL</th></tr>
		<?php
			$total=0;
			for($i=0;$i<sizeof($itm_des);$i++){
				print '<tr style="font-size:10pt" height="20px"><td class="shipmentTB3" align="right">'.number_format($bi_qty[$i]).'</td><td class="shipmentTB3">'.$itm_des[$i].'</td><td width="50px" align="right" class="shipmentTB3">'.number_format($bi_uprice[$i]+$bi_discount[$i]).'</td><td align="right" class="shipmentTB3"><input type="text" name="discount_'.$bi_id[$i].'" id="discount_'.$bi_id[$i].'" value="'.$bi_discount[$i].'" style="width:50px; text-align:right;" /><input type="button" value="Update" onclick="updateDisc('.$bi_id[$i].');" /></td><td align="right" class="shipmentTB3">'.number_format($bi_uprice[$i]).'</td><td align="right" class="shipmentTB3">'.number_format($bi_uprice[$i]*$bi_qty[$i]).'</td></tr>';
				$total+=$bi_uprice[$i]*$bi_qty[$i];
			}
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="5" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="5" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Payment: Cash</td><td align="right">'.number_format($cash_amount).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="5" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$chequedate.'<span style="padding-right:30px">'.$chq0_fullNo.'</span>Payment: Cheque</td><td align="right">'.number_format($chque_amount).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="5" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td><td align="right">'.number_format($total-$cash_amount-$chque_amount).'&nbsp;&nbsp;</td></tr>';	
		?>	
			</table>
	</td></tr></table>
<?php } ?>
</div>	
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
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