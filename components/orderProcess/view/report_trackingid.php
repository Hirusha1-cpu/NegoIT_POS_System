<html>
<head>
</head>
<body>
<table align="center" style="font-family:Calibri;" border="1" cellspacing="5px" >
<?php
	$k=0;
	$j=0;
	$cust='';
			print '<tr>';
	for($i=0;$i<sizeof($invoice_no);$i++){
		if(($k>0)&&(($k%3)==0)){
			$j++;
			print '</tr>';
			if(($j%6)==0) print '</table><p style="page-break-before: always"></p><table align="center" style="font-family:Calibri;" border="1" cellspacing="5px" >';
			//print '<tr><td style="height:1px"></td></tr>';
			print '<tr>';
		}
			print '<td>';
				print '<table align="center" border="0" cellspacing="0" width="100%" height="100%" ><tr><td>';
					print '<table style="padding-left:10px; padding-right:10px; padding-top:10px; ">';
					print '<tr><td>Invoice No</td><td>';
					for($n=0;$n<sizeof($invoice_no[$i]);$n++){
						print ': '.str_pad($invoice_no[$i][$n], 7, "0", STR_PAD_LEFT).'<br />';
					}
					print '</td></tr>';
					print '<tr><td>Invoice Date</td><td>: '.substr($inv_date[$i],0,10).'</td></tr>';
					print '<tr><td>Weight</td><td>: '.$weight[$i].' Kg</td></tr>';
					print '<tr><td>Courier ID</td><td>: '.$tracking_id[$i].'</td></tr>';
					print '<tr><td colspan="2"><strong>'.$cust_name[$i].'</strong></td></tr>';
					print '</table>';
				print '</td></tr></table>';
			print '</td>';
		$k++;
	}
	print '</tr>';
?>
</table>
</body>
</html>