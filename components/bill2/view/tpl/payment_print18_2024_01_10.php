<?php
    include_once  '../../modle/bill2Module.php';
    include_once  '../../../../template/common.php';
    generatePayment();
    $currency = getCurrency(2);
    $decimal = getDecimalPlaces(2);
?>
<div id="print_top"></div>

<table width="100%">
	<tr>
		<td rowspan="2" style="font-family:Arial; font-size:11pt">
			<strong><?php print $tm_company; ?></strong>
			<br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
		</td>
		<td></td>
		<td align="right">
			<span style="font-family:'Arial Black'; font-size:20pt">PAYMENT</span>
			<br />
			<span style="font-size:12pt; font-family:Arial"></span>
			<br />
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="right">
			PAYMENT # [<?php print  str_pad($payment_id, 7, "0", STR_PAD_LEFT); ?>]
			<br />
			<span  style="font-family:Arial; font-size:11pt">
				DATE: <?php print $payment_date; ?>
				<br /><br />
			</span>
		</td>
	</tr>
</table>

<table align="center" width="450px" cellspacing="0" border="0" >
	<tr height="350px">
		<td style="vertical-align:top">
			<hr />
			<br />
			<table align="center" width="350px" border="1" cellspacing="0" >
				<?php if($invoice_no!=0)
				print '<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >For Invoice </td><td align="right" style="padding-right:15px"><strong>'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'</strong></td></tr>';
				?>
				<tr style="font-family:Arial; font-size:10pt">
					<td height="30px" style="padding-left:15px" >Payment : <?php print $payment_type_n; ?></td>
					<td align="right" style="padding-right:15px"><strong><?php print $currency.'. '.number_format($amount, $decimal); ?></strong></td>
				</tr>
			</table>
			<br />
			<?php if($payment_type==2){ ?>
			<table align="center" width="350px" border="1" cellspacing="0" >
				<tr style="font-family:Arial; font-size:10pt"><td colspan="2"><strong>Cheque Details</strong></td></tr>
				<tr style="font-family:Arial; font-size:10pt"><td width="150px" height="30px" style="padding-left:15px" >Bank</td><td align="right" style="padding-right:15px"><?php print $chque_bank; ?></td></tr>
				<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >Branch</td><td align="right" style="padding-right:15px"><?php print $chque_branch; ?></td></tr>
				<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >Cheque No</td><td align="right" style="padding-right:15px"><strong><?php print $chque_no; ?></strong></td></tr>
				<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >Cheque Date</td><td align="right" style="padding-right:15px"><?php print $chque_date; ?></td></tr>
			</table>
			<?php }else if($payment_type==3){ ?>
			<table align="center" width="350px" border="1" cellspacing="0" >
				<tr style="font-family:Arial; font-size:10pt"><td width="150px" height="30px" style="padding-left:15px" >Bank</td><td align="right" style="padding-right:15px"><?php print $bank_trans; ?></td></tr>
			</table>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td style="font-family:Arial; font-size:10pt">
			<?php if($comment!='')print '<hr />Comment: '.$comment; ?>
		</td>
	</tr>
</table>

<br />
<table align="center" width="100%" border="1" cellspacing="0" >
	<tr style="font-size:8pt;">
		<td>
			Make all checks payable to <strong><?php print $tm_company; ?></strong><br />
			If you have and questions concerning this invoice, please contact <?php print $tm_tel; ?> <br />
			<br/>
			<table align="center">
				<tr><td style="font-family:Arial; font-size:9pt">Issued By</td><td>  : <?php print ucfirst($salesman); ?></td><td width="45px"></td><td style="font-family:Arial; font-size:9pt">Paied By</td><td>  : <?php print ucfirst($cust_name); ?><td></tr>
				<tr><td style="font-family:Arial; font-size:9pt">Name</td><td>  ..............................</td><td></td><td style="font-family:Arial; font-size:9pt">Name</td><td>  ..............................</td></tr>
				<tr><td style="font-family:Arial; font-size:9pt">Signature</td><td>  ..............................</td><td></td><td style="font-family:Arial; font-size:9pt">Signature</td><td>  ..............................</td></tr>
				<tr><td style="font-family:Arial; font-size:9pt">Date</td><td>  ..............................</td><td></td><td style="font-family:Arial; font-size:9pt">Date</td><td>  ..............................</td></tr>
			</table>
		</td>
	</tr>
</table>