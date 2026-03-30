<?php
                include_once  'template/header.php';
?>

<!-- ------------------Item List----------------------- -->
	<table align="center" height="100%" style="font-family:Calibri">
	<tr><th align="center" style="background-color:#DDDDDD;">Salesman</th><th colspan="3" align="left" style="background-color:#EEEEEE; color:navy; padding-left:30px;"><?php print ucfirst($salesman_name); ?></th></tr>
	<tr><td colspan="4" style="border:0; background-color:white">&nbsp;</td></tr>
	<tr style="background-color:#DDDDDD;"><th width="100px">Invoice No</th><th width="100px">Date</th><th width="150px">Invoice Price</th><th width="150px">Invoice Profit</th></tr>
	<?php
	$inv=0;
	for($i=0;$i<sizeof($invoice_no);$i++){
			print '<tr style="background-color:#EEEEEE;"><td align="center"><a href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="50px" align="center" >'.$time[$i].'</td><td align="right" style="padding-right:10px;">'.number_format($invoice_total[$i]).'</td><td align="right" style="padding-right:10px;">'.number_format($invoice_profit[$i]).'</td></tr>';
	}
	?>	
	</table>

<?php
                include_once  'template/footer.php';
?>