<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<script>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($salesman_list);$x++){ print '"'.$salesman_list[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
	</script>

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
	<form action="index.php?components=report&action=payment_commision" method="post" onsubmit="return validatePComm()">
		<table style="font-family:Calibri; font-weight:bold; font-size:12pt" align="center" width="90%">
		<tr><td>Salesman : </td><td><input type="text" name="salesman" id="tags1" value="<?php print $salesman; ?>" /></td><td rowspan="3" align="right"><input type="submit" value="Generate" style="height:100%" /></td></tr>
		<tr><td>From Date : </td><td><input type="date" name="from_date" id="from_date" style="width:130px" value="<?php print $from_date; ?>" /></td></tr>
		<tr><td>To Date : </td><td><input type="date" name="to_date" id="to_date" style="width:130px" value="<?php print $to_date; ?>" /></td></tr>
		</table>
	</form>
	<hr>
	<p align="justify" style="font-size:10pt">This report was generated based on list of payments received after the credit balance reached to 0</p>
	<p align="justify" style="font-size:10pt">Note: If a payment is NOT associated with a bill, it will show in <span style="color:red">RED</span> color in the bellow report. We highly advice you to check the non-associated payments against commissions</p>
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt">
		<tr><th>Date</th><th>Customer</th><th>Before<br/>Credit Balance</th><th>Type</th><th>Bill<br>Invoice No</th><th>Payment<br>Invoice No</th><th>Collection</th><th>After<br/>Credit Balance</th><th>Commision</th></tr>
	<?php 
	$total_com=0;
	for($i=0;$i<sizeof($com_date);$i++){
		$total_com+=$com_com[$i];
		print '<tr style="color:'.$txt_color[$i].'"><td align="center">'.$com_date[$i].'</td><td>'.$com_cust[$i].'</td><td align="right">'.number_format($com_balance_before[$i]).'&nbsp;&nbsp;</td><td>'.$com_pay_type[$i].'</td><td align="center">';
		for($k=0;$k<sizeof($com_bill_inv[$i]);$k++){
			print '<a href="index.php?components=billing&action=finish_bill&id='.$com_bill_inv[$i][$k].'">'.str_pad($com_bill_inv[$i][$k], 7, "0", STR_PAD_LEFT).'</a><br>';
		}
		print '</td><td align="center"><a href="index.php?components=billing&action=finish_payment&id='.$com_pay_inv[$i].'">'.str_pad($com_pay_inv[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="right">'.number_format($com_pay_amount[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format($com_balance_after[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format($com_com[$i]).'&nbsp;&nbsp;</td></tr>';
	}
		print '<tr><td colspan="8" align="right"><strong>Total Commision &nbsp;&nbsp;&nbsp;</strong></td><td align="right"><strong>'.number_format($total_com).'&nbsp;&nbsp;</strong></td></tr>';
	?>
	</table>

  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>