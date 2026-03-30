<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
				generateRtnInvoice();
				generalPrint();
                $paper_size=paper_size(2);
                if($paper_size=='A4'){
                	$page_height=760;
                }
                if($paper_size=='A5'){
                	$page_height=430;
                }

?>

<div style="background-image:url('/images/inv_7_template.png'); background-repeat:no-repeat; background-position: left 25px top 12px;" >
<div id="print_top"></div>
<table height="96px" style="font-family:Arial">
<tr height="10px"><td colspan="3"></td></tr>
<tr height="92px"><td width="170px"></td>
<td width="165px" valign="top">
	<span style="font-size:18pt">RETURN<br />INVOICE</span><br />
	<br />
	<span style="font-size:10pt">RETURN NO:[<?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?>]</span>
</td><td rowspan="2" width="158px" valign="top">
	<table style="font-size:9pt; font-weight:bold" cellspacing="0">
	<tr><td colspan="2" height="8px"></td></tr>
	<tr><td>INV DATE</td><td>: <?php print $bill_date; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>INV TIME</td><td>: <?php print $bill_time; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT DATE</td><td>: <?php print substr($print_time,0,10); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT TIME</td><td>: <?php print substr($print_time,11,5); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>LOCATION</td><td style="font-size:8pt">: <?php print $tm_shop; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>TERMINAL</td><td>: <?php print $key_dev_name; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>SALESMAN</td><td>: <?php print ucfirst($bill_salesman); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	</table>
</td></tr>
<tr><td height="55px"></td><td></td></tr>

<tr height="4px"><td colspan="5"></td></tr>
</table>
<table><tr><td height="15px"></td></tr></table>

<table border="0" cellspacing="0" cellpadding="0" style="padding-left:15px">
<tr><td width="33px"></td><td width="444px">

	<table border="0" style="font-size:6pt; font-family:Verdana" height="365px" width="100%" align="center"> 
	<tr><td valign="top">
		<table align="center" height="365px" width="100%" border="1" cellspacing="0" >
		<tr style="font-family:Arial; font-size:10pt"><th height="20px" >ITEM</th><th width="80px">QTY</th><th width="80px">EXTRA PAY</th></tr>
	<?php
		for($i=0;$i<sizeof($bill_id);$i++){
			print '<tr style="font-size:10pt" height="30px"><td align="left" style="border-bottom:0; border-top:0; padding-left:20px;">'.$bill_item[$i].'</td><td style="border-bottom:0; border-top:0; padding-right:30px" align="right">'.$bill_qty[$i].'</td><td style="border-bottom:0; border-top:0; padding-right:10px" align="right">'.number_format($extra_pay[$i]).'</td></tr>';
			print '<tr><td height="10px" style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td></tr>';
		}
	?>	<tr><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td></tr>
		<tr><td colspan="2" height="10px" style="padding-left:20px">Total Extra Pay</td><td align="right" style="padding-right:10px"><?php print number_format(array_sum($extra_pay)); ?></td></tr>
		</table>

	</td></tr>
	</table>
<table><tr><td height="30px"></td></tr></table>
<table width="100%"><tr><td><hr /></td></tr></table>
	<table border="0" style="font-size:9pt; font-family:Verdana">
	<tr><td width="10px"></td><td><strong>CUSTOMER:</strong><br /><?php print ucwords($bill_cust).'<br />'; ?></td><td width="80px"></td><td align="right">
		<table style="font-size:9pt; font-family:Verdana">
			<tr><td><strong>Signature:</strong></td><td><br /><br /><br />..........................</td></tr>
		</table>
	</td></tr>
	</table>
</td><td width="10px"></td></tr></table>
<table><tr><td height="60px"></td></tr></table>

</div>
