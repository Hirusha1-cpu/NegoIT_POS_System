<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
				generatePayment();
				generalPrint();
                $paper_size=paper_size(2);
                if($paper_size=='A4'){
                	$page_height=820;
                	if($chq0_date!='')$chequedate='[ Cheque Date: '.$chq0_date.' ]&nbsp;&nbsp;&nbsp;&nbsp;'; else $chequedate='';
                }
                if($paper_size=='A5'){
                	$page_height=520;
                	$chequedate='';
                }
                	$bill_title1='PAYMENT';
                	$title1_size=18;
                	$bill_title2='';
                	$sub_title='PAYMENT NO';
                	$advance='';

?>

<div style="background-image:url('/images/inv_7_template.png'); background-repeat:no-repeat; background-position: left 25px top 12px;" >
<div id="print_top"></div>
<table height="96px" style="font-family:Arial">
<tr height="14px"><td colspan="3"></td></tr>
<tr height="92px"><td width="170px"></td>
<td width="165px" valign="top">
	<span style="font-size:<?php print $title1_size; ?>pt"><?php print $bill_title1; ?></span><br />
	<span style="font-size:12pt"><?php print $bill_title2; ?></span><br />
	<br />
	<span style="font-size:10pt"><?php print $sub_title; ?>:[<?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?>]</span>
</td><td rowspan="2" width="158px" valign="top">
	<table style="font-size:9pt; font-weight:bold" cellspacing="0">
	<tr><td colspan="2" height="8px"></td></tr>
	<tr><td>PAY DATE</td><td>: <?php print $payment_date; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT DATE</td><td>: <?php print substr($print_time,0,10); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT TIME</td><td>: <?php print substr($print_time,11,8); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>LOCATION</td><td style="font-size:8pt">: <?php print $tm_shop; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>TERMINAL</td><td style="font-size:8pt">: <?php print $key_dev_name; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT COPY</td><td>: </td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>SALESMAN</td><td>: <?php print ucfirst($salesman); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	</table>
</td></tr>
<tr><td height="55px"></td><td></td></tr>

<tr height="4px"><td colspan="5"></td></tr>
</table>
<table><tr><td height="15px"></td></tr></table>

<table border="0" cellspacing="0" cellpadding="0" style="padding-left:10px">
<tr><td width="33px"></td><td width="444px" >

	<table align="center" width="450px" cellspacing="0" border="0" >
		<tr height="310px"><td style="vertical-align:top">
		<br />
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
		</td></tr>
		<tr><td style="font-family:Arial; font-size:10pt">
			<table width="100%"><tr><td><hr /></td></tr></table>
			Comment: <?php print $comment; ?>
		</td></tr>
	</table>

	<table><tr><td height="30px"></td></tr></table>
	<table width="100%"><tr><td><hr /></td></tr></table>
	<table border="0" style="font-size:9pt; font-family:Verdana">
	<tr><td width="10px"></td><td><strong>CUSTOMER:</strong> <?php print substr(ucfirst($cust_name),0,20).'<br />'.$cu_mobile.'<br />'.$cu_nic; ?></td><td width="80px"></td><td align="right">
		<table style="font-size:9pt; font-family:Verdana">
			<tr><td><strong>Signature:</strong></td><td><br /><br /><br />..........................</td></tr>
		</table>
	</td></tr>
	</table>
</td><td width="10px"></td></tr></table>
<table><tr><td height="60px"></td></tr></table>

</div>
