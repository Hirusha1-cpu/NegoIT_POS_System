<?php
                include_once  '../../modle/bill2Module.php';
                include_once  '../../../../template/common.php';
				generatePayment();
				generalPrint();
?>
<style type="text/css">
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>

<!-- <table background="/images/inv_4_template.jpg" border="0"><tr><td> -->
<div id="print_top"></div>
<table height="96px" style="font-family:Arial">
<tr height="20px"><td colspan="5"></td></tr>
<tr><td width="0px"></td><td><div class="no-print"><img src="/images/inv_logo4.png" width="105px" /></div></td><td width="7px"></td><td width="2px" bgcolor="gray"></td><td align="center" width="220px"><div class="no-print"><?php print '<span style="font-size:15pt">'.$tm_company.'</span><br /><span style="font-size:10pt">'.$tm_address.'</span><br /><span style="font-size:10pt">Telephone: '.$tm_tel.'</span>'; ?></div></td></tr>
<tr height="4px"><td colspan="5"></td></tr>
</table>
<table><tr><td height="27px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0" style="font-size:10pt; font-family:Verdana;" bgcolor="#ADCDCC"><tr><td width="0px" bgcolor="white"></td><td width="143px"></td><td style="font-size:16pt"><div class="no-print"><strong>TYPE</strong></div></td><td width="100px"></td><td>PAYMENT</td><td width="40px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0" style="font-size:9pt; font-family:Verdana">
<tr height="16px"><td width="0px"></td><td width="60px" style="font-size:7pt"><div class="no-print"><strong>Inv No </strong></div></td><td><div class="no-print">:</div></td><td width="210px"><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td><td style="font-size:7pt" width="50px"><div class="no-print"><strong>Terminal</strong></div></td><td><div class="no-print">:</div></td><td><?php print $key_dev_name; ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Date </strong></div></td><td><div class="no-print">:</div></td><td><?php print $payment_date; ?></td><td style="font-size:7pt" ><div class="no-print"><strong></strong></div></td><td></td><td></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Cashier </strong></div></td><td><div class="no-print">:</div></td><td></td><td style="font-size:7pt" width="50px"><div class="no-print"><strong>Salesman</strong></div></td><td><div class="no-print">:</div></td><td><?php print ucfirst($salesman); ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Location </strong></div></td><td><div class="no-print">:</div></td><td><?php print $tm_shop; ?></td><td style="font-size:7pt"><div class="no-print"><strong>Technicien</strong></div></td><td><div class="no-print">:</div></td><td></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Customer </strong></div></td><td><div class="no-print">:</div></td><td><?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id='.$cust_id.'&action2=finish_payment&id2='.$_GET['id'].'" target="_parent" >'.substr(ucfirst($cust_name),0,20).'</a>'; ?></td><td style="font-size:7pt" ><div class="no-print"><strong>Print Date</strong></div></td><td><div class="no-print">:</div></td><td style="font-size:8pt"><?php print substr($print_time,0,10); ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt">&nbsp;&nbsp;<strong>Print </strong></td><td>:</td><td></td><td style="font-size:7pt" ><div class="no-print"><strong>Print Time</strong></div></td><td><div class="no-print">:</div></td><td style="font-size:8pt"><?php print substr($print_time,11,8); ?></td></tr>
</table>
<table><tr><td height="4px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0">
<tr><td width="0px"></td><td width="400px">
	<table border="0" style="font-size:6pt; font-family:Verdana" height="128px">
	<tr height="11px" style="background-color:navy; color:white;"><td width="23px" align="center"><div class="no-print">Line</div></td><td width="188px" align="center"><div class="no-print">Description</div></td><td width="22px" align="center"><div class="no-print"></div></td><td width="30px" align="center"><div class="no-print"></div></td><td width="32px" align="center"><div class="no-print"></div></td><td width="20px" align="center"><div class="no-print"></div></td><td width="48px" align="center"><div class="no-print">Amount</div></td></tr>
	<?php
	/*
	$total_gross=$total_discount=0;
	for($i=0;$i<sizeof($bill_id);$i++){
		print '<tr height="16px" style="background-color:#A2C5CE; border-bottom-color:black; font-size:9pt; font-family:'."'Lucida Console'".';"><td align="right">'.($i+1).'&nbsp;</td><td>'.$bi_desc[$i].'</td><td align="right">'.number_format($bi_qty[$i]).'&nbsp;</td><td align="right">'.number_format($bi_price[$i]).'</td><td align="right">'.number_format($bi_discount[$i]).'&nbsp;</td><td align="right">'.number_format(($bi_discount[$i]/($bi_price[$i]+$bi_discount[$i]))*100).'</td><td align="right">'.number_format($bi_qty[$i]*$bi_price[$i]).'</td></tr>';
		$total_discount+=$bi_discount[$i]*$bi_qty[$i];
		$total_gross+=$bi_qty[$i]*($bi_price[$i]+$bi_discount[$i]);
	}
		if($bm_bocom_type==2) $comment=$bm_bocom; else $comment='';
		print '<tr style="background-color:#A2C5CE; border-bottom-color:black; font-size:9pt; font-family:'."'Lucida Console'".';"><td></td><td>'.$comment.'</td><td></td><td></td><td></td><td></td><td></td></tr>';
	*/ ?>
	<tr style="background-color:#A2C5CE; border-bottom-color:black; font-size:9pt; font-family:'Lucida Console'"><td></td><td align="center"><strong>PAYMENT</strong><br />[<?php print $payment_type_n; ?>]<?php if($payment_type==3) print '<br />['.$bank_trans.']'; ?></td><td></td><td></td><td></td><td></td><td align="center"><strong><?php print number_format($amount); ?></strong></td></tr>
	</table>
	<table border="0" cellspacing="0" style="font-size:7pt; font-family:'Draft 10cpi'">
	<tr height="17px"><td width="0px"></td><td width="40px" style="font-family:Verdana;"><div class="no-print"><strong>Cash</strong></div></td><td><div class="no-print">:</div></td><td width="65px" align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php if($payment_type==1) print number_format($amount); ?></td><td width="160px"></td><td width="70px" style="font-size:6pt; font-family:Verdana;"><div class="no-print"><strong></strong></div><td><div class="no-print">:</div></td><td align="right" width="42px" style="font-size:9pt; font-family:'Lucida Console';"></td></tr>
	<tr height="17px"><td></td><td style="font-family:Verdana;"><div class="no-print"><strong>Credit</strong></div></td><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';">-</td><td></td><td style="font-size:6pt; font-family:Verdana;"><div class="no-print"><strong></strong></div><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"></td></tr>
	<tr height="17px"><td></td><td style="font-family:Verdana;"><div class="no-print"><strong>Cheque</strong></div></td><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php if($payment_type==2) print number_format($amount); ?></td><td></td><td style="font-size:6pt; font-family:Verdana;"><div class="no-print"><strong></strong></div><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"></td></tr>
	<tr height="17px"><td></td><td style="font-family:Verdana;"><div class="no-print"><strong>Chq No</strong></div></td><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php print $chque_no.'-'.$chque_bank_code.'-'.$chque_branch; ?></td><td></td><td style="font-size:6pt; font-family:Verdana;"><div class="no-print"><strong></strong></div><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"></td></tr>
	</table>
<table><tr><td height="28px"></td></tr></table>
	<table border="0" style="font-size:6pt; font-family:Verdana">
	<tr><td width="10px"></td><td><div class="no-print"><strong>Customer Signature</strong></div></td><td width="140px"></td><td><div class="no-print"><strong>Checked By</strong></div></td></tr>
	</table>
<table><tr><td height="10px"></td></tr></table>
</td><td width="30px"></td></tr></table>
<!-- </td></tr></table> -->
