<?php
                include_once  'template/header.php';
?>

<!-- ------------------Item List----------------------- -->
	<form action="index.php?components=report&action=salesman" method="post" >
	<table align="center" height="100%" cellspacing="0" style="font-family:Calibri">
	<tr><td colspan="2" align="right">
			<strong>Month </strong>: </td><td align="right"><input type="month" name="date" style="width:150px" value="<?php print $date; ?>" />
			<input type="submit" value="GET" />
	</td></tr>
	</table>
	</form>
<br /><br />
	<table align="center" height="100%"  style="font-family:Calibri">
	<tr style="background-color:#DDDDDD;"><th width="100px">Salesman</th><th>Number of Invoices</th><th width="150px">Total Commision</th></tr>
	<?php
	$inv=0;
	for($i=0;$i<sizeof($salesman_id);$i++){
			print '<tr style="background-color:#EEEEEE;"><td class="shipmentTB3"><a href="index.php?components=report&action=salesman_invoices&month='.$date.'&id='.$salesman_id[$i].'">'.ucfirst($salesman_name[$i]).'</a></td><td align="right" style="padding-right:20px;">'.number_format($invoice_count[$i]).'</td><td align="right" align="right" style="padding-right:10px;">'.number_format($commision[$i]).'</td></tr>';
	}
	?>	
	</table>

<?php
                include_once  'template/footer.php';
?>