<html>
<head>
<!-- <link href='https://fonts.googleapis.com/css?family=Libre Barcode 128' rel='stylesheet'> -->
<script src="js/qrcode.min.js"></script>
<style type="text/css">
      div.page{
        page-break-after: always;
        page-break-inside: avoid;
      }
</style>
</head>
<body>

<?php
	for($i=0;$i<sizeof($cust_id);$i++){
		if($i==0) print '<div class="page">'; 
		elseif(($i%2)==0) print '</div><div class="page">';
		print '<table align="center" style="font-family:Calibri;" width="750px" border="0">';
		print '<tr><td>';
			print '<table border="1" cellspacing="0" cellpadding="0" width="100%" style="font-family:Calibri; font-size:12.4pt">
					<tr><td width="280px" style="padding-left:20px; padding-right:20px">';
				print '<table cellspacing="0" width="100%" style="font-size:12.4pt" border="0"><tr><td>';
					print '<strong>FROM</strong><br />';
					print $from_name.'<br />';
					print $from_address.'<br />';
					print $from_mob.'<br />';
				//	print '<img src="https://barcode.tec-it.com/barcode.ashx?data='.$order_no[$i].'&code=Code128&dpi=72&dataseparator=" />';
				//	print '<span style="font-family: \'Libre Barcode 128\';font-size: 60px;" >'.$order_no[$i].'</span >';
				// print '</td><td align="center">';
					print '<div id="qrcode'.$i.'" ></div>';
					// print '<script type="text/javascript">var qrcode=new QRCode("qrcode'.$i.'");qrcode.makeCode('."'$cust_id[$i]'".');</script>';
					// print '<script type="text/javascript">
					// 		var qrcode = new QRCode("qrcode'.$i.'", {
					// 		    text: '."'$to_name[$i]'".',
					// 		    width: 97,
					// 		    height: 97,
					// 		    colorDark : "#000000",
					// 		    colorLight : "#ffffff",
					// 		    correctLevel : QRCode.CorrectLevel.H
					// 		});
					// 	</script>';
				print '</td></tr></table>';
	
			print '</td><td style="padding-left:20px; padding-right:20px" >';
			print '<strong>TO</strong><br />';
			print $to_name[$i].'<br />';
			print $to_address[$i].'<br />';
			print $to_mob[$i];
			print '</td></tr></table>';
		print '</td></tr>';
		if(($i%2)==0) print '<tr><td height="5px"></td></tr>';
		print '</table>';
//		print '<tr><td><div style="page-break-after: always;"></div></td></tr>';
	
//		if(((($i+1)%7)==0))  print '</table><p style="page-break-before: always"></p><table align="center" style="font-family:Calibri;" width="1000px">';
	}
?>
	</div>
	
</body>
</html>