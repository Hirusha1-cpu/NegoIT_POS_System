<?php
	include_once  '../../modle/bill2Module.php';
	include_once  '../../../../template/common.php';
	generatePayment();
	$systemid=inf_systemid(2);
	$logo = getStoreLogo(2);
?>
<div id="print_top"></div>

<table width="100%" border="0">
<tr><td rowspan="2" style="font-family:Arial; font-size:11pt" valign="top">
<img src="../../../../images/cplogo<?php print $logo; ?>.png" height="30px" /><br />
&nbsp;Tel: <?php print $tm_tel; ?>
	<hr />
	<table style="font-size:10pt;">
	<tr><td><strong>Customer: </strong> <br />
	<?php
	print '<a style="color:navy; text-decoration:none;" href="../../../../index.php?components=bill2&action=cust_details&id='.$cust_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" style="text-decoration:none" >'.ucwords($cust_name).'</a>';
	print '<br />';
	print '<span style="font-size:9pt;">'.str_replace('&#13;','&nbsp;&nbsp;&nbsp;&nbsp;',$cu_details).'</span>';
	?>
	</td></tr>
	</table>
</td><td></td><td align="right"><span style="font-family:'Arial Black'; font-size:18pt">PAYMENT</span><br /></td></tr>
<tr><td></td><td align="right">
	<table style="font-family:Arial; font-size:11pt">
	<tr><td>PAYMENT</td><td> # [<?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?> ]</td></tr>
	<tr><td>TIME</td><td> : <?php print substr($payment_time,0,5); ?></td></tr>
	<tr><td>DATE</td><td> : <?php print $payment_date; ?></td></tr>
	</table>
</td></tr>
</table>


<table align="center" width="450px" cellspacing="0" border="0" ><tr height="350px"><td style="vertical-align:top">
<hr /><br />
	<table align="center" width="350px" border="1" cellspacing="0" >
	<?php if($invoice_no!=0)
	print '<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >For Invoice </td><td align="right" style="padding-right:15px"><strong>'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'</strong></td></tr>';
	?>
	<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >Payment : <?php print $payment_type_n; ?></td><td align="right" style="padding-right:15px"><strong><?php print 'Rs. '.number_format($amount); ?></strong></td></tr>
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
</td></tr><tr><td style="font-family:Arial; font-size:10pt">
<?php if($comment!='')print '<hr />Comment: '.$comment; ?>
</td></tr></table>
	<br />
	<table align="center" width="100%" border="1" cellspacing="0" >
	<tr style="font-size:9pt;"><td>
	Make all checks payable to <strong><?php print $tm_company; ?></strong><br />
	If you have and questions concerning this invoice, please contact <?php print $tm_tel; ?> <br />
	<br/>
		<table align="center" style="font-family:Arial; font-size:10pt">
			<tr><td>Issued By</td><td>  : <?php print ucfirst($salesman); ?></td><td width="45px"></td><td>Paied By</td><td>  : <?php print ucfirst($cust_name); ?><td></tr>
			<tr><td>Name</td><td>  ..............................</td><td></td><td>Name</td><td>  ..............................</td></tr>
			<tr><td>Signature</td><td>  ..............................</td><td></td><td>Signature</td><td>  ..............................</td></tr>
			<tr><td>Date</td><td>  ..............................</td><td></td><td>Date</td><td>  ..............................</td></tr>
			<tr><td colspan="5" align="center" style="font-size:9pt"><br /><br /><?php print $tm_company; ?>, <?php print $tm_address; ?></td></tr>
		</table>
	</td></tr>
	</table>