<table align="center" style="font-family:Calibri">
<tr bgcolor="#CCCCCC"><th>Shipment No</th><th>Item</th><th width="100px">Cost</th><th>Wholesale Price</th><th>Retail Price</th><th>Qty</th><th>Store</th></tr>
<?php
	for($i=0;$i<sizeof($itt_id);$i++){
		if($itt_shipment[$i]!=0) $link1='<a href="index.php?components=inventory&action=one_shipment&shipment_no='.$itt_shipment[$i].'">'.str_pad($itt_shipment[$i], 7, "0", STR_PAD_LEFT).'</a>'; else $link1='';
		print '<tr bgcolor="#EEEEEE"><td align="center">'.$link1.'</td><td style="padding-left:10px; padding-right:10px">'.$itt_itm[$i].'</td><td align="right" style="padding-right:10px">'.number_format($itt_cprice[$i]).'</td><td align="right" style="padding-right:10px">'.number_format($itt_wprice[$i]).'</td><td align="right" style="padding-right:10px">'.number_format($itt_rprice[$i]).'</td><td align="right" style="padding-right:10px">'.$itt_qty[$i].'</td><td align="Center" style="padding-right:10px; padding-left:10px">'.$itt_store[$i].'</td></tr>';
	}

?>

</table>