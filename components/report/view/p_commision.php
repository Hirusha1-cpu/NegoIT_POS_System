<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<script>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($salesman_list);$x++){ print '"'.$salesman_list[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
	</script>

<!-- ------------------Item List----------------------- -->

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Salesman Commision based on Cash\Chque Collection</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >From Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print date("Y-m-d",time()); ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >To Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print date("Y-m-d",time()); ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<hr>
</div>
<br /><br />
<div id="print">
<form action="index.php?components=report&action=payment_commision" method="post" onsubmit="return validatePComm()">
	<table style="font-family:Calibri; font-weight:bold; font-size:12pt" align="center">
	<tr><td>Salesman : <input type="text" name="salesman" id="tags1" value="<?php print $salesman; ?>" /></td><td width="30px"></td><td>From Date : <input type="date" name="from_date" id="from_date" style="width:130px" value="<?php print $from_date; ?>" /></td><td width="30px"></td><td>To Date : <input type="date" name="to_date" id="to_date" style="width:130px" value="<?php print $to_date; ?>" /></td><td width="30px"></td><td><input type="submit" value="Generate" style="height:50px" /></td></tr>
	</table>
</form>
<p align="center" style="font-size:10pt">This report was generated based on list of payments received after the credit balance reached to 0</p>
<p align="center" style="font-size:10pt">Note: If a payment is NOT associated with a bill, it will show in <span style="color:red">RED</span> color in the bellow report. We highly advice you to check the non-associated payments against commissions</p>
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