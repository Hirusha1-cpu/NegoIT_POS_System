<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
				generateInvoice('bi.id');
                $paper_size=paper_size(2);
                if($paper_size=='A4'){
                	$page_height=820;
                	if($chq0_date!='')$chequedate='[ Cheque Date: '.$chq0_date.' ]&nbsp;&nbsp;&nbsp;&nbsp;'; else $chequedate='';
                }
                if($paper_size=='A5'){
                	$page_height=465;
                	$chequedate='';
                }
                if($bi_type==1 || $bi_type==2){
                	$bill_title='INVOICE';
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
            $systemid=inf_systemid(2);
			$decimal=0;
			if($systemid==14) $decimal=2; 
?>
<div id="print_top"></div>
<table width="100%" border="0">
<tr><td rowspan="2" style="font-family:Arial; font-size:11pt" valign="top">
<img src="../../../../images/cplogo<?php print $systemid; ?>.png" height="30px" /><br />
&nbsp;Tel: <?php print $tm_tel; ?>
	<hr />
	<table style="font-size:10pt;">
	<tr><td>Customer: <br />
	<?php 
	print '<a style="color:navy; text-decoration:none;" href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" style="text-decoration:none" >'.ucwords($bi_cust0).'</a>'; 
	print '<br />';
	print '<span style="font-size:9pt;">'.str_replace('&#13;','&nbsp;&nbsp;&nbsp;&nbsp;',$cu_details).'</span>';
	?>
	
	</td></tr>
	</table>
</td><td></td><td align="right"><span style="font-family:'Arial Black'; font-size:18pt"><?php print $bill_title; ?></span><br /><span style="font-size:12pt; font-family:Arial"><?php if($bi_type==2 || $bi_type==5) print '<strong>Service Invoice</strong>'; else if($bi_type==3) print '<strong>Repair Invoice</strong>'; ?></span><br /></td></tr>
<tr><td></td><td align="right">
	<table style="font-family:Arial; font-size:11pt">
	<tr><td><?php print $sub_title; ?></td><td> # [<?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?> ]</td></tr>
	<tr><td>TIME</td><td> : <?php print substr($bi_time,0,5); ?></td></tr>
	<tr><td>DATE</td><td> : <?php print $bi_date; ?></td></tr>
	</table>
</td></tr>
</table>

	<table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0" border="1" style="font-family:Calibri;">
	<tr style="font-family:Arial; font-size:10pt"><th width="40px" height="20px" >Code</th><th width="40px">QTY</th><th>DESCRIPTION</th><?php if($dn=='no'){ ?><th width="40px">UNIT <br />PRICE</th><th width="60px" >TOTAL</th><?php } ?></tr>
<?php
	for($i=0;$i<sizeof($bill_id);$i++){
		if($bill_cross_tr[$i]>0) $color1="#CCCCCC"; else 	$color1="auto";
		if($bi_return_odr[$i]==0){
			print '<tr style="font-size:10pt; background-color:'.$color1.'" height="20px"><td align="center" style="border-bottom:0; border-top:0;">'.$bi_drawer[$i].'</td><td style="border-bottom:0; border-top:0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($bi_qty[$i]).'</td><td style="border-bottom:0; border-top:0; padding-left:10px">'.$bi_desc[$i].'</td>';
			if($dn=='no') print '<td width="50px" style="border-bottom:0; border-top:0;" align="right">'.number_format($bi_price[$i],$decimal).'&nbsp;&nbsp;</td><td align="right" style="border-bottom:0; border-top:0;">'.number_format(($bi_qty[$i]*$bi_price[$i]),$decimal).'&nbsp;&nbsp;</td>';
			print '</tr>';
		}
	}
		print '<tr style="font-size:10pt"><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td>';
		if($dn=='no') print '<td width="50px" style="border-bottom:0; border-top:0;"></td><td align="right" style="border-bottom:0; border-top:0;"></td>';
		print '</tr>';
		
	if($dn=='no'){
		if($pay_type==3) $cash_name='Bank Transfer'; else $cash_name='Cash';
		print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total,$decimal).'&nbsp;&nbsp;</td></tr>';	
		print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: '.$cash_name.'</td><td align="right">'.number_format($cash_amount,$decimal).'&nbsp;&nbsp;</td></tr>';	
		print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$chequedate.'<span style="padding-right:30px">'.$chq0_fullNo.'</span>'.$advance.' Payment: Cheque</td><td align="right">'.number_format($chque_amount,$decimal).'&nbsp;&nbsp;</td></tr>';	
		print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td><td align="right">'.number_format(($total-$cash_amount-$chque_amount),$decimal).'&nbsp;&nbsp;</td></tr>';	
	}
?>	
	</table>
	<table align="center" width="100%" border="1" cellspacing="0" style="font-family:Arial;">
	<tr style="font-size:8pt;"><td>
		<?php if($dn=='no'){ ?>
		<table align="center" width="100%" style="font-size:9pt">
			<tr height="30px"><td width="65px">Salesman : </td><td>  <?php print ucfirst($up_salesman); ?></td><td></td><td width="80px">Name</td><td width="130px">  ..............................</td></tr>
			<tr height="30px"><td colspan="2"></td><td></td><td>Signature</td><td>  ..............................</td></tr>
			<tr><td colspan="5" align="center"><br /><?php print $tm_company; ?>, <?php print $tm_address; ?></td></tr>
		</table>
		<?php }else{ ?>
		<table align="center" width="100%"  style="font-size:9pt">
			<tr><td width="65px">Order Packed By : </td><td>  <?php print ucfirst($bm_packed_by).'&nbsp;&nbsp;&nbsp;&nbsp;: '.$up_mobile; ?></td><td></td><td width="80px">Customer : </td><td width="130px"><?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" >'.ucfirst($bi_cust0).'</a>'; ?><td></tr>
			<tr height="50px"><td>Name</td><td>............................................</td><td></td><td>Name</td><td>  ............................................</td></tr>
			<tr height="50px"><td>Signature </td><td>............................................</td><td></td><td>Signature</td><td>  ............................................</td></tr>
			<tr height="50px"><td>Date</td><td>............................................</td><td></td><td>Date</td><td>  ............................................</td></tr>
			<tr><td colspan="5" align="center"><br /><?php print $tm_company; ?>, <?php print $tm_address; ?></td></tr>
		</table>
		<?php } ?>
	</td></tr>
	</table>