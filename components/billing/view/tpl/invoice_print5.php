<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
                $bill_title='';
				generateInvoice('bi.id');
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
                if($bi_type==1 || $bi_type==2){
                	$bill_title='&nbsp;SALES';
                	$sub_title='INVOICE NO';
                	$advance='';
                }else if($bi_type==3){
                	$bill_title='INVOICE';
                	$sub_title='REPAIR NO';
                	$advance='Advance';
                }else if($bi_type==4 || $bi_type==5){
                	if($bm_status<3){
                	$bill_title='CUST ORDER';
                	$sub_title='ORDER NO';
                	$advance='Advance'; 
                	}else{
                	$bill_title='INVOICE';
                	$sub_title='INVOICE NO';
                	$advance='';
                	}
                }
                $dn=$_GET['dn'];
                if($dn=='yes'){
                	$bill_title='DELIVERY NOTE';
                	$sub_title='DELIVERY NOTE NO';
                	$page_height-=110;
                }
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
<tr height="10px"><td colspan="5"></td></tr>
<tr><td width="0px"></td><td><div class="no-print"><img src="/images/inv_logo4.png" width="105px" /></div></td><td width="7px"></td><td width="2px" bgcolor="gray"></td><td align="center" width="220px"><div class="no-print"><?php print '<span style="font-size:15pt">'.$tm_company.'</span><br /><span style="font-size:10pt">'.$tm_address.'</span><br /><span style="font-size:10pt">Telephone: '.$tm_tel.'</span>'; ?></div></td></tr>
<tr height="4px"><td colspan="5"></td></tr>
</table>
<table><tr><td height="15px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0" style="font-size:10pt; font-family:Verdana;" bgcolor="#ADCDCC"><tr><td width="0px" bgcolor="white"></td><td width="143px"></td><td style="font-size:16pt"><div class="no-print"><strong>TYPE</strong></div></td><td width="140px"></td><td><?php print $bill_title; ?></td><td width="5px"></td></tr></table>
<table><tr><td height="5px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0" style="font-size:9pt; font-family:Verdana">
<tr height="16px"><td width="0px"></td><td width="60px" style="font-size:7pt"><div class="no-print"><strong>Inv No </strong></div></td><td><div class="no-print">:</div></td><td width="210px"><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td><td style="font-size:7pt" width="50px"><div class="no-print"><strong>Terminal</strong></div></td><td><div class="no-print">:</div></td><td><?php print $key_dev_name; ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Date </strong></div></td><td><div class="no-print">:</div></td><td><?php print $bi_date; ?></td><td style="font-size:7pt" ><div class="no-print"><strong></strong></div></td><td></td><td></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Cashier </strong></div></td><td><div class="no-print">:</div></td><td></td><td style="font-size:7pt" width="50px"><div class="no-print"><strong>Salesman</strong></div></td><td><div class="no-print">:</div></td><td><?php print ucfirst($up_salesman); ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Location </strong></div></td><td><div class="no-print">:</div></td><td><?php print $tm_shop; ?></td><td style="font-size:7pt"><div class="no-print"><strong>Technicien</strong></div></td><td><div class="no-print">:</div></td><td><?php print ucfirst($up_packedby); ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Customer </strong></div></td><td><div class="no-print">:</div></td><td><?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" >'.substr(ucfirst($bi_cust0),0,20).'</a>'; ?></td><td style="font-size:7pt" ><div class="no-print"><strong>Print Date</strong></div></td><td><div class="no-print">:</div></td><td style="font-size:8pt"><?php print substr($print_time,0,10); ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt">&nbsp;&nbsp;<strong>Print </strong></td><td>:</td><td><?php if($bm_print_st==0) print 'Original'; else print 'Re-Print'; ?></td><td style="font-size:7pt" ><div class="no-print"><strong>Print Time</strong></div></td><td><div class="no-print">:</div></td><td style="font-size:8pt"><?php print substr($print_time,11,8); ?></td></tr>
</table>
<table><tr><td height="4px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0">
<tr><td width="0px"></td><td width="400px">
	<table border="0" style="font-size:6pt; font-family:Verdana" height="128px">
	<tr height="11px" style="background-color:navy; color:white;"><td width="23px" align="center"><div class="no-print">Line</div></td><td width="188px" align="center"><div class="no-print">Item Description</div></td><td width="22px" align="center"><div class="no-print">Qty</div></td><td width="30px" align="center"><div class="no-print">Price</div></td><td width="32px" align="center"><div class="no-print">D. Rs</div></td><td width="20px" align="center"><div class="no-print">D.%</div></td><td width="48px" align="center"><div class="no-print">Amount</div></td></tr>
	<?php
	$total_gross=$total_discount=0;
	for($i=0;$i<sizeof($bill_id);$i++){
		if($bi_return_odr[$i]==0){
			if(($bi_price[$i]+$bi_discount[$i])==0)	$discount=0; else $discount=($bi_discount[$i]/($bi_price[$i]+$bi_discount[$i]))*100;
			print '<tr height="16px" style="background-color:#A2C5CE; border-bottom-color:black; font-size:9pt; font-family:'."'Lucida Console'".';"><td align="right">'.($i+1).'&nbsp;</td><td>'.$bi_desc[$i].'</td><td align="right">'.number_format($bi_qty[$i]).'&nbsp;</td><td align="right">'.number_format($bi_price[$i]).'</td><td align="right">'.number_format($bi_discount[$i]).'&nbsp;</td><td align="right">'.round($discount,2).'</td><td align="right">'.number_format($bi_qty[$i]*$bi_price[$i]).'</td></tr>';
			$total_discount+=$bi_discount[$i]*$bi_qty[$i];
			$total_gross+=$bi_qty[$i]*($bi_price[$i]+$bi_discount[$i]);
		}
	}
		if($bm_bocom_type==2) $comment=$bm_bocom; else $comment='';
		print '<tr style="background-color:#A2C5CE; border-bottom-color:black; font-size:9pt; font-family:'."'Lucida Console'".';"><td></td><td>'.$comment.'</td><td></td><td></td><td></td><td></td><td></td></tr>';
	?>
	</table>
	<?php if($pay_type==3) $cash_name='[BankTr]'; else $cash_name='[Cash]'; ?>
	<table border="0" cellspacing="0" style="font-size:7pt; font-family:'Draft 10cpi'">
	<tr height="17px"><td width="0px"></td><td width="40px" style="font-family:Verdana;"><div class="no-print"><strong>Cash</strong></div></td><td><div class="no-print">:</div></td><td width="65px" align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php print number_format($cash_amount); print $cash_name; ?>&nbsp;&nbsp;</td><td width="110px"></td><td width="70px" style="font-size:6pt; font-family:Verdana;"><div class="no-print"><strong>Gross Amount</strong></div><td><div class="no-print">:</div></td><td align="right" width="42px" style="font-size:9pt; font-family:'Lucida Console';"><?php print number_format($total_gross); ?></td></tr>
	<tr height="17px"><td></td><td style="font-family:Verdana;"><div class="no-print"><strong>Credit</strong></div></td><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php print number_format($total-$cash_amount-$chque_amount); ?>[Credit]</td><td></td><td style="font-size:6pt; font-family:Verdana;"><div class="no-print"><strong>Discount</strong></div><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php print number_format($total_discount); ?></td></tr>
	<tr height="17px"><td></td><td style="font-family:Verdana;"><div class="no-print"><strong>Cheque</strong></div></td><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php print number_format($chque_amount); ?>[Cheque]</td><td></td><td style="font-size:6pt; font-family:Verdana;"><div class="no-print"><strong>Additional Amo</strong></div><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"></td></tr>
	<tr height="17px"><td></td><td style="font-family:Verdana;"><div class="no-print"><strong>Chq No</strong></div></td><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php print $chq0_fullNo; ?></td><td></td><td style="font-size:6pt; font-family:Verdana;"><div class="no-print"><strong>Net Amount</strong></div><td><div class="no-print">:</div></td><td align="right" style="font-size:9pt; font-family:'Lucida Console';"><?php print number_format($total); ?></td></tr>
	</table>
<table><tr><td height="28px"></td></tr></table>
	<table border="0" style="font-size:6pt; font-family:Verdana">
	<tr><td width="10px"></td><td><div class="no-print"><strong>Customer Signature</strong></div></td><td width="140px"></td><td><div class="no-print"><strong>Checked By</strong></div></td></tr>
	</table>
<table><tr><td height="10px"></td></tr></table>
</td><td width="30px"></td></tr></table>
<!-- </td></tr></table> -->
