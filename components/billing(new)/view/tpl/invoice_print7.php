<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
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
                	$bill_title1='INVOICE';
                	$title1_size=18;
                	$bill_title2='SALES';
                	$sub_title='INVOICE NO';
                	$advance='';
                }else if($bi_type==3){
                	$bill_title1='INVOICE';
                	$title1_size=18;
                	$bill_title2='REPAIR';
                	$sub_title='REPAIR NO';
                	$advance='Advance';
                }else if($bi_type==4 || $bi_type==5){
                	if($bm_status<3){
	                	$bill_title1='CUST ORDER';
	                	$title1_size=18;
	                	$bill_title2='';
	                	$sub_title='ORDER NO';
	                	$advance='Advance'; 
                	}else{
	                	$bill_title1='INVOICE';
	                	$title1_size=18;
	                	$bill_title2='SALES';
	                	$sub_title='INVOICE NO';
	                	$advance='';
                	}
                }
                $dn=$_GET['dn'];
                if($dn=='yes'){
                	$bill_title1='<strong>DELIVERY NOTE</strong>';
                	$title1_size=14;
                	$bill_title2='';
                	$sub_title='DN NO';
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
	<tr><td>INV DATE</td><td>: <?php print $bi_date; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT DATE</td><td>: <?php print substr($print_time,0,10); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT TIME</td><td>: <?php print substr($print_time,11,8); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>LOCATION</td><td style="font-size:8pt">: <?php print $tm_shop; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>TERMINAL</td><td style="font-size:8pt">: <?php print $key_dev_name; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT COPY</td><td>: <?php if($bm_print_st==0) print 'Original'; else print 'Re-Print'; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>SALESMAN</td><td>: <?php print ucfirst($up_salesman); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<?php if($bi_type==3) print '<tr><td>TECHNICIAN</td><td>: '.ucfirst($up_packedby).'</td></tr>'; ?>
	</table>
</td></tr>
<tr><td height="55px"></td><td></td></tr>

<tr height="4px"><td colspan="5"></td></tr>
</table>
<table><tr><td height="15px"></td></tr></table>

<table border="0" cellspacing="0" cellpadding="0" style="padding-left:10px">
<tr><td width="33px"></td><td width="454px" >
	<table border="1" cellspacing="0" style="font-size:8pt; font-family:Verdana;" height="395px" width="100%">
	<tr height="11px" style="font-weight:bold"><td width="23px" align="center">LINE</td><td width="188px" align="center">DESCRIPTION</td><td width="22px" align="center">QTY</td><td width="30px" align="center">TAG PRICE</td><td width="32px" align="center">DIS<br />(Rs)</td><td width="20px" align="center">DIS<br />(%)</td><td width="30px" align="center">Dis. PRICE</td><td width="48px" align="center">TOTAL</td></tr>
	<?php
	$total_gross=$total_discount=0;
	for($i=0;$i<sizeof($bill_id);$i++){
		if($bi_return_odr[$i]==0){
			if(($bi_price[$i]+$bi_discount[$i])==0)	$discount=0; else $discount=($bi_discount[$i]/($bi_price[$i]+$bi_discount[$i]))*100;
			print '<tr height="16px" style="border-bottom-color:black; font-size:9pt; font-family:'."'Lucida Console'".';"><td align="right">'.($i+1).'&nbsp;</td><td>'.$bi_desc[$i].'</td><td align="right">'.number_format($bi_qty[$i]).'&nbsp;</td><td align="right">'.number_format($bi_price[$i]+$bi_discount[$i]).'</td><td align="right">'.number_format($bi_discount[$i]).'&nbsp;</td><td align="right">'.round($discount,2).'%</td><td align="right">'.number_format($bi_price[$i]).'</td><td align="right">'.number_format($bi_qty[$i]*$bi_price[$i]).'</td></tr>';
			$total_discount+=$bi_discount[$i]*$bi_qty[$i];
			$total_gross+=$bi_qty[$i]*($bi_price[$i]+$bi_discount[$i]);
		}
	}
		if($bm_bocom_type==2) $comment=$bm_bocom; else $comment='';
		print '<tr style="border-bottom-color:black; font-size:9pt; font-family:'."'Lucida Console'".';"><td></td><td>'.$comment.'</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	?>
	
		<tr height="40px"><td colspan="7" valign="top">
			<table cellspacing="0" style="font-size:9pt; font-weight:bold;" width="100%" border="0" >
				<tr><td></td><td align="right">Bill Total</td></tr>
				<tr><td></td><td align="right">Payment: Cash</td></tr>
				<tr><td></td><td align="right">Payment: Bank</td></tr>
				<tr><td><?php print $chequedate.'<span style="padding-right:30px">'.$chq0_fullNo.'</span>'; ?></td><td align="right">Payment: Cheque</td></tr>
				<tr><td></td><td align="right">Remaining Balance</td></tr>
			</table>
		</td><td>
			<table cellspacing="0" style="font-size:9pt; font-weight:bold;" width="100%" border="0" >
				<tr><td align="right"><?php print number_format($total); ?></td></tr>
				<tr><td align="right"><?php if($pay_type==1) print number_format($cash_amount); else print '0'; ?></td></tr>
				<tr><td align="right"><?php if($pay_type==3) number_format($cash_amount); else print '0'; ?></td></tr>
				<tr><td align="right"><?php print number_format($chque_amount); ?></td></tr>
				<tr><td align="right"><?php print number_format($total-$cash_amount-$chque_amount); ?></td></tr>
			</table>
		</td></tr>
	
	</table>
<table><tr><td height="28px"></td></tr></table>
	<table border="0" style="font-size:9pt; font-family:Verdana">
	<tr><td width="10px"></td><td><strong>CUSTOMER:</strong> <?php print substr(ucfirst($bi_cust0),0,20).'<br />'.str_replace('Mobile',"<br />Mobile",$cu_details); ?></td><td width="80px"></td><td align="right">
		<table style="font-size:9pt; font-family:Verdana">
			<tr><td><strong>Signature:</strong></td><td><br /><br /><br />..........................</td></tr>
		</table>
	</td></tr>
	</table>
</td><td width="10px"></td></tr></table>
<table><tr><td height="60px"></td></tr></table>

</div>
