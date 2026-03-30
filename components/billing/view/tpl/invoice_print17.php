<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
				generateInvoice('itm.description');
				generateReturnList();
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
                }

?>
<div id="print_top"></div>
	<table border="0" cellspacing="0" ><tr><td>
		  <table width="230px" align="center" style="font-family:Arial, Helvetica, sans-serif">
		  <tr><td><span style="font-family:'Arial'; font-size:20pt"><?php print $bill_title; ?></span></td></tr>
		  <tr><td style="font-size:10pt"><?php
		  	if($tm_company!='OMS'){
		  		print '<strong>'.$tm_company.'</strong>'; 
		  	}else{
		  		print '<span style="font-size:22pt; font-weight:bold">&#937;</span><span style="font-size:16pt; font-weight:bold">MS</span><br />';
		  		print '<span style="font-size:6pt">Zigo Mobile (pvt) Ltd</span>';
		  	}
		   ?><br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td style="font-size:8pt">
			<?php print $sub_title; ?> # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:8pt">
			DATE: <?php print $bi_date; ?><br /><br />
			</span>
			</td></tr>
			<tr><td height="8px"></td></tr>
		  </table>
		
			<table align="center" width="230px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="4"><hr></td></tr>
			<tr style="font-family:Arial; font-size:8pt; text-align:center"><td>DESCRIPTION</td><?php if($dn=='no'){ ?><td>UNIT<br />PRICE</td><?php } ?><td>QTY</td><?php if($dn=='no'){ ?><td>TOTAL</td><?php } ?></tr>
			<tr><td colspan="4"><hr></td></tr>
		<?php
			 for($i=0;$i<sizeof($bill_id);$i++){
			 	 if($bill_cross_tr[$i]>0) $color1="#CCCCCC"; else 	$color1="auto";
				 if($bi_return_odr[$i]==0){
					 print '<tr style="background-color:'.$color1.'"><td colspan="4">'.$bi_desc[$i].'</td></tr>';
					 print '<tr style="background-color:'.$color1.'"><td>'.$bi_code[$i].'</td>';
					 if($dn=='no') print '<td align="right">'.$bi_price[$i].'</td>';
					 print '<td align="right">'.$bi_qty[$i].'</td>';
					 if($dn=='no') print '<td align="right">'.number_format($bi_qty[$i]*$bi_price[$i],2).'&nbsp;</td>';
					 if($dn=='yes') print '<td></td><td></td>';
					 print '</tr>';
					 if($dn=='no') print '<tr><td colspan="4">&nbsp;</td></tr>';
				 }
			}
			 if($dn=='no'){
				if($pay_type==3) $cash_name='Bank Transfer'; else $cash_name='Cash';
				print '	<tr><td colspan="4"><hr></td></tr>';
				print '<tr style="font-size:10pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total,2).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: '.$cash_name.'</td><td align="right">'.number_format($cash_amount,2).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Cheque</td><td align="right">'.number_format($chque_amount,2).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td><td align="right">'.number_format(($total-$cash_amount-$chque_amount),2).'&nbsp;&nbsp;</td></tr>';	
				if($chq0_fullNo!='') 
				print '	<tr><td colspan="4"><hr></td></tr>';
				print '	<tr><td colspan="4" align="center"><span style="padding-right:30px">'.$chq0_fullNo.'</span></td></tr>';
			}
		?>	
			</table>
		<?php  if($dn=='no'){ ?>
			<table align="center" width="230px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="2"><hr></td></tr>
			<tr><td width="60px">Salesman2 </td><td>: <?php print ucfirst($up_salesman); ?></td></tr>
			<tr><td colspan="2"><?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" >'.ucfirst($bi_cust).'</a>'; ?></td></tr>
			<tr><td height="30px">Cust.Tax ID </td><td>: <?php print $cu_nickname; ?></td></tr>
			<tr><td height="30px">Signature </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Name </td><td>: ...............................................</td></tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2" align="center">IT WAS A PLEASURE TO SERVE YOU<br><br>THANK YOU</td></tr>
			<tr><td colspan="2"><br></td></tr>
			</table>
		<?php }else{ ?>
			<table align="center" width="230px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="2"><hr></td></tr>
			<tr><td width="60px">Order Packed By </td><td>: <?php print ucfirst($bm_packed_by); ?></td></tr>
			<tr><td height="30px">Signature </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Name </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Date </td><td>: ...............................................</td></tr>
			<tr><td height="30px" colspan="2"><hr /></td></tr>
			<tr><td>Customer :</td><td>: <?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" >'.ucfirst($bi_cust0).'</a>'; ?></td></tr>
			<tr><td height="30px">Signature </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Name </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Date </td><td>: ...............................................</td></tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2" align="center">IT WAS A PLEASURE TO SERVE YOU<br><br>THANK YOU</td></tr>
			<tr><td colspan="2"><br></td></tr>
			</table>
		<?php } ?>
<?php		
if($return_odr){
	print '<table align="center" width="230px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
	print '<tr><td colspan="4"><hr></td></tr>';
	print '<tr><td colspan="4" align="center"><strong>NEW REPLACEMENTS FOR RETURN ITEMS</strong></td></tr>';
	print '<tr><td colspan="4"><hr></td></tr>';
	print '<tr><td>DESCRIPTION</td><td>U\PRICE</td><td>QTY</td><td>TOTAL</td></tr>';
	print '<tr><td colspan="4"><hr></td></tr>';
	$total2=0;
	 for($i=0;$i<sizeof($bill_id);$i++){
		 if($bi_return_odr[$i]==1){
		 $total2+=$bi_qty[$i]*$bi_price[$i];
		 print '<tr><td colspan="4">'.$bi_desc[$i].'</td></tr>';
		 print '<tr><td>'.$bi_code[$i].'</td><td>'.number_format($bi_price[$i]).'</td><td align="right">'.number_format($bi_qty[$i]).'&nbsp;</td><td align="right">'.number_format($bi_qty[$i]*$bi_price[$i]).'&nbsp;</td></tr>';
		 print '<tr><td colspan="4"><br></td></tr>';
		 }
	 }
	print '<tr><td colspan="4"><hr></td></tr>';
	print '<tr><td colspan="3">Total New Replacement Amount:</td><td>'.number_format($total2).'</td></tr>';
	print '<tr><td colspan="4"><hr></td></tr>';
	print '</table>';
}
if(sizeof($removed_code)>0){
	print '<table align="center" width="230px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
	print '<tr><td>Remaining Return Item Credit:</td><td>'.number_format($return_cr_bal).'</td></tr>';
	print '<tr><td colspan="2"><hr></td></tr>';
	print '</table>';
}
if(sizeof($rt_code)>0){
	print '<table align="center" width="230px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
	print '<tr><td colspan="2"><hr></td></tr>';
	print '<tr><td colspan="2" align="center"><strong>REPLACEMENTS FOR RETURN ITEMS</strong></td></tr>';
	print '<tr><td colspan="2"><hr></td></tr>';
	print '<tr><td>DESCRIPTION</td><td>QTY</td></tr>';
	print '<tr><td colspan="2"><hr></td></tr>';
	for($i=0;$i<sizeof($rt_code);$i++){
		print '<tr><td colspan="2">'.$rt_desc[$i].'</td></tr>';
		print '<tr><td>'.$rt_code[$i].'</td><td align="right">'.number_format($rt_qty[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
		print '<tr><td colspan="2"><br></td></tr>';
	}
	print '<tr><td colspan="2"><hr></td></tr>';
	print '</table>';
}
if(sizeof($rt_pending_code)>0){
	print '<table align="center" width="230px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
	print '<tr><td colspan="2"><hr></td></tr>';
	print '<tr><td colspan="2" align="center"><strong>PENDING RETURN ITEMS</strong></td></tr>';
	print '<tr><td colspan="2"><hr></td></tr>';
	print '<tr><td>DESCRIPTION</td><td>QTY</td></tr>';
	print '<tr><td colspan="2"><hr></td></tr>';
	for($i=0;$i<sizeof($rt_pending_code);$i++){
		print '<tr><td colspan="2">'.$rt_pending_desc[$i].'</td></tr>';
		print '<tr><td>'.$rt_pending_code[$i].'</td><td align="right">'.number_format($rt_pending_qty[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
		print '<tr><td colspan="2"><br></td></tr>';
	}
	print '</table>';
}
if(sizeof($removed_code)>0){
	print '<table align="center" width="230px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
	print '<tr><td colspan="2"><hr></td></tr>';
	print '<tr><td colspan="2" align="center"><strong>CANCELED RETURN ITEMS</strong></td></tr>';
	print '<tr><td colspan="2"><hr></td></tr>';
	for($i=0;$i<sizeof($removed_code);$i++){
		print '<tr><td colspan="2">'.$removed_desc[$i].'</td></tr>';
		print '<tr><td>'.$removed_code[$i].'</td><td align="right">'.number_format($removed_qty[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
		print '<tr><td colspan="2"><br></td></tr>';
	}
	print '<tr><td colspan="2"><hr></td></tr>';
	print '</table>';
}
?>
		</td></tr></table>
<br />